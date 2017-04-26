<?php

namespace AddressBookBundle\Controller;

use AddressBookBundle\Entity\Phone;
use AddressBookBundle\Form\PhoneType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class PhoneController extends Controller
{
    /**
     * @Route("/{id}/addPhone")
     * @Method("POST")
     */
    public function addPhoneAction(Request $request, $id)
    {
        $contact = $this->getDoctrine()->getRepository("AddressBookBundle:Contact")->find($id);
        if (!$contact) {
            throw $this->createNotFoundException("Contact not found");
        }

        $phone = new Phone();

        $formPhone = $formPhone = $this->createForm(PhoneType::class, $phone);

        $formPhone->handleRequest($request);

        if ($formPhone->isSubmitted() && $formPhone->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $phone->setContact($contact);
            $contact->addPhone($phone);

            $em->persist($phone);

            $em->flush();

            return $this->redirectToRoute('addressbook_contact_show', ['id' => $contact->getId()]);
        }

        return $this->redirectToRoute("addressbook_contact_modify", ['id' => $id]);
    }
}
