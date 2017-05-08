<?php

namespace AddressBookBundle\Controller;

use AddressBookBundle\Entity\Address;
use AddressBookBundle\Form\AddressType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class AddressController
 * @package AddressBookBundle\Controller
 * @Security("has_role('ROLE_USER')")
 */
class AddressController extends Controller
{
    /**
     * @Route("/{id}/addAddress")
     * @Method("POST")
     */
    public function addAddressAction(Request $request, $id)
    {
        $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository("AddressBookBundle:Contact")
            ->findOneBy(['id' => $id, 'user' => $userId]);

        if (!$contact) {
            throw $this->createNotFoundException("Contact not found");
        }

        $address = new Address();

        $formAddress = $formAddress = $this->createForm(AddressType::class, $address);
        $formAddress->handleRequest($request);

        if ($formAddress->isSubmitted() && $formAddress->isValid()) {
            $address->setContact($contact);
            $contact->addAddress($address);

            $em->persist($address);
            $em->flush();
        }
        return $this->redirectToRoute('addressbook_contact_show', ['id' => $id]);
    }

    /**
     * @Route("/{id}/deleteAddress/")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository("AddressBookBundle:Contact")
            ->findOneBy(['id' => $id, 'user' => $userId]);
        if (!$contact) {
            throw $this->createNotFoundException("Contact not found");
        }

        $address = $em->getRepository("AddressBookBundle:Address")
            ->find($request->request->get("address_id"));
        if (!$address) {
            throw $this->createNotFoundException("Address not found");
        }

        $em->remove($address);
        $contact->removeAddress($address);

        $em->flush();

        return $this->redirectToRoute('addressbook_contact_show', ['id' => $id]);
    }
}
