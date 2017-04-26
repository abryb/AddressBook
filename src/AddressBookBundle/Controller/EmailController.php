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


class EmailController extends Controller
{
    /**
     * @Route("/{id}/addEmail")
     * @Method("POST")
     */
    public function addEmailAction(Request $request, $id)
    {
        $contact = $this->getDoctrine()->getRepository("AddressBookBundle:Contact")->find($id);
        if (!$contact) {
            throw $this->createNotFoundException("Contact not found");
        }

        $email = new Email();

        $formEmail = $formEmail = $this->createForm(EmailType::class, $email);

        $formEmail->handleRequest($request);

        if ($formEmail->isSubmitted() && $formEmail->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $email->setContact($contact);
            $contact->addEmail($email);

            $em->persist($email);

            $em->flush();

            return $this->redirectToRoute('addressbook_contact_show', ['id' => $contact->getId()]);
        }

        return $this->redirectToRoute("addressbook_contact_modify", ['id' => $id]);
    }
}
