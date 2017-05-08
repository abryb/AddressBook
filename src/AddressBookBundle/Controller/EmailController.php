<?php

namespace AddressBookBundle\Controller;

use AddressBookBundle\Entity\Email;
use AddressBookBundle\Form\EmailType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class EmailController
 * @package AddressBookBundle\Controller
 * @Security("has_role('ROLE_USER')")
 */
class EmailController extends Controller
{
    /**
     * @Route("/{id}/addEmail")
     * @Method("POST")
     */
    public function addEmailAction(Request $request, $id)
    {
        $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository("AddressBookBundle:Contact")->findOneBy(['id' => $id, 'user' => $userId]);

        if (!$contact) {
            throw $this->createNotFoundException("Contact not found");
        }

        $email = new Email();
        $formEmail = $formEmail = $this->createForm(EmailType::class, $email);
        $formEmail->handleRequest($request);

        if ($formEmail->isSubmitted() && $formEmail->isValid()) {
            $email->setContact($contact);
            $contact->addEmail($email);

            $em->persist($email);
            $em->flush();
        }
        return $this->redirectToRoute('addressbook_contact_show', ['id' => $id]);
    }

    /**
     * @Route("/{id}/deleteEmail/")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository("AddressBookBundle:Contact")->findOneBy(['id' => $id, 'user' => $userId]);
        if (!$contact) {
            throw $this->createNotFoundException("Contact not found");
        }

        $email = $em->getRepository("AddressBookBundle:Email")
            ->find($request->request->get("email_id"));
        if (!$email) {
            throw $this->createNotFoundException("Email not found");
        }

        $contact->removeEmail($email);

        $em->remove($email);
        $em->flush();

        return $this->redirectToRoute('addressbook_contact_show', ['id' => $id]);
    }
}
