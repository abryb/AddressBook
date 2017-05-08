<?php

namespace AddressBookBundle\Controller;

use AddressBookBundle\Entity\Address;
use AddressBookBundle\Entity\Contact;
use AddressBookBundle\Entity\Phone;
use AddressBookBundle\Entity\Email;
use AddressBookBundle\Form\AddressType;
use AddressBookBundle\Form\ContactType;
use AddressBookBundle\Form\EmailType;
use AddressBookBundle\Form\PhoneType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class ContactController
 * @package AddressBookBundle\Controller
 * @Security("has_role('ROLE_USER')")
 */
class ContactController extends Controller
{
    /**
     * @Route("/")
     * @Template(":Contact:show_all.html.twig")
     */
    public function showAllAction(Request $request)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository("AddressBookBundle:Contact");
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($request->query->get("search")) {
            $contacts = $repository->findContactsLike($request->query->get("search"), $user->getId());
        } else {
            $contacts = $repository->findBy(['user' => $user->getId()], ['surname' => 'ASC']);
        }
        return ['contacts' => $contacts];
    }

    /**
     * @Route("/{id}.{name}", requirements={"id"="\d+"}, defaults={"name" = "default"})
     * @Template(":Contact:show_single.html.twig")
     */
    public function showAction(Request $request, $id, $name)
    {
        $em = $this->getDoctrine()->getManager();
        $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $contact = $em->getRepository("AddressBookBundle:Contact")
            ->loadAllAboutContact($id, $userId);
        if (!$contact) {
            throw $this->createNotFoundException("Contact not found");
        }

        $contactName = $contact->getName()."-". $contact->getSurname();

        if ($name !== $contactName) {
            return $this->redirectToRoute("addressbook_contact_show", [
                "id" => $contact->getId(), "name" => $contactName]);
        }

        return ['contact' => $contact];
    }


    /**
     * @Route("/new")
     * @Template(":Contact:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $contact = new Contact();
        $contact->setUser($user);

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
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository("AddressBookBundle:Contact")->find($id);
        if (!$contact) {
            throw $this->createNotFoundException("Contact not found");
        }
        $em->remove($contact);
        $em->flush();

        return $this->redirectToRoute("addressbook_contact_showall");
    }

    /**
     * @Route("/{id}/modify/", requirements={"id" = "\d+"})
     * @Template(":Contact:modify.html.twig")
     */
    public function modifyAction(Request $request, $id)
    {
        $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $contact = $this->getDoctrine()->getRepository("AddressBookBundle:Contact")
            ->loadAllAboutContact($id, $userId);
        if (!$contact) {
            throw $this->createNotFoundException("Contact not found");
        }

        $address = new Address();
        $address->setContact($contact);

        $phone = new Phone();
        $phone->setContact($contact);

        $email = new Email();
        $email->setContact($contact);

        $formContact = $this->createForm(ContactType::class, $contact);

        $formAddress = $this->createForm(AddressType::class, $address, [
            'action' => $this->generateUrl("addressbook_address_addaddress", ['id' => $contact->getId()]),
            'method' => 'POST']);

        $formPhone = $this->createForm(PhoneType::class, $phone, [
            'action' => $this->generateUrl("addressbook_phone_addphone", ['id' => $contact->getId()]),
            'method' => 'POST']);

        $formEmail = $this->createForm(EmailType::class, $email, [
            'action' => $this->generateUrl("addressbook_email_addemail", ['id' => $contact->getId()]),
            'method' => 'POST']);


        $formContact->handleRequest($request);

        if ($formContact->isSubmitted() && $formContact->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($contact);
            $em->flush();

            return $this->redirectToRoute('addressbook_contact_show', ['id' => $contact->getId()]);
        }

        return [
            'formContact' => $formContact->createView(),
            'formAddress' => $formAddress->createView(),
            'formPhone' => $formPhone->createView(),
            'formEmail' => $formEmail->createView(),
            'contact' => $contact,
        ];
    }
}
