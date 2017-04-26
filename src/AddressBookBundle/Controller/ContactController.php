<?php

namespace AddressBookBundle\Controller;

use AddressBookBundle\Entity\Address;
use AddressBookBundle\Entity\Contact;
use AddressBookBundle\Form\AddressType;
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
     * @Method("POST")
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
     * @Template(":Contact:modify.html.twig")
     */
    public function modifyAction(Request $request, $id)
    {
        $contact = $this->getDoctrine()->getRepository("AddressBookBundle:Contact")->find($id);
        $address = new Address();
        $address->setContact($contact);


        if (!$contact) {
            throw $this->createNotFoundException("Contact not found");
        }

        $formContact = $this->createForm(ContactType::class, $contact);

        $formAddress = $this->createForm(AddressType::class, $address, [
            'action' => $this->generateUrl("addressbook_contact_addaddress", ['id' => $contact->getId()]),
            'method' => 'POST']);

        $formContact->handleRequest($request);

        if ($formContact->isSubmitted() && $formContact->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->flush();

            return $this->redirectToRoute('addressbook_contact_show', ['id' => $contact->getId()]);
        }

        return [
            'formContact' => $formContact->createView(),
            'formAddress' => $formAddress->createView()
        ];
    }

    /**
     * @Route("/{id}/addAddress")
     * @Method("POST")
     */
    public function addAddressAction(Request $request, $id)
    {
        $contact = $this->getDoctrine()->getRepository("AddressBookBundle:Contact")->find($id);
        if (!$contact) {
            throw $this->createNotFoundException("Contact not found");
        }

        $address = new Address();

        $formAddress = $formAddress = $this->createForm(AddressType::class, $address);

        $formAddress->handleRequest($request);

        if ($formAddress->isSubmitted() && $formAddress->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $address->setContact($contact);
            $contact->addAddress($address);

            $em->persist($address);

            $em->flush();

            return $this->redirectToRoute('addressbook_contact_show', ['id' => $contact->getId()]);
        }

        return $this->redirectToRoute("addressbook_contact_modify", ['id' => $id]);
    }

}
