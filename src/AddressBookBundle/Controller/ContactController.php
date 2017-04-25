<?php

namespace AddressBookBundle\Controller;

use AddressBookBundle\Entity\Contact;
use AddressBookBundle\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller
{
    /**
     * @Route("/")
     * @Template(":Contact:show_all.html.twig")
     */
    public function showAllAction()
    {
        $contacts = $this->getDoctrine()->getRepository("AddressBookBundle:Contact")->findBy([], ['surname' => 'ASC']);
        return ['contacts' => $contacts];
    }


    /**
     * @Route("/{id}", requirements={"id"="\d+"})
     * @Template(":Contact:show_single.html.twig")
     */
    public function showAction($id)
    {
        $contact = $this->getDoctrine()->getRepository("AddressBookBundle:Contact")->find($id);
        return ['contact' => $contact];
    }


    /**
     * @Route("/new")
     * @Template(":Contact:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($contact);
            $em->flush();

            return $this->redirectToRoute('addressbook_contact_show', ['id' => $contact->getId()]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction($id)
    {
        $contact = $this->getDoctrine()->getRepository("AddressBookBundle:Contact")->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($contact);
        $em->flush();

        return $this->redirectToRoute("addressbook_contact_showall");
    }

    /**
     * @Route("/{id}/modify")
     * @Template(":Contact:new.html.twig")
     */
    public function modifyAction(Request $request, $id)
    {
        $contact = $this->getDoctrine()->getRepository("AddressBookBundle:Contact")->find($id);

        if (!$contact) {
            throw $this->createNotFoundException("Contact not found");
        }

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->flush();

            return $this->redirectToRoute('addressbook_contact_show', ['id' => $contact->getId()]);
        }

        return ['form' => $form->createView()];
    }

}
