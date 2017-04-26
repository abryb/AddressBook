<?php

namespace AddressBookBundle\Controller;

use AddressBookBundle\Entity\Address;
use AddressBookBundle\Form\AddressType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class AddressController extends Controller
{
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

    /**
     * @Route("/{id}/deleteAddress/")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $contact = $this->getDoctrine()->getRepository("AddressBookBundle:Contact")->find($id);
        if (!$contact) {
            throw $this->createNotFoundException("Contact not found");
        }

        $address = $this->getDoctrine()->getRepository("AddressBookBundle:Address")
            ->find($request->request->get("address_id"));
        if (!$address) {
            throw $this->createNotFoundException("Address not found");
        }

        $em = $this->getDoctrine()->getManager();

        $em->remove($address);
        $contact->removeAddress($address);

        $em->flush();

        return $this->redirectToRoute('addressbook_contact_show', ['id' => $id]);
    }
}
