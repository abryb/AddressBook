<?php

namespace AddressBookBundle\Controller;

use AddressBookBundle\Entity\ContactGroup;
use AddressBookBundle\Form\ContactGroupType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class GroupController
 * @Route("/groups")
 * @Security("has_role('ROLE_USER')")
 */
class GroupController extends Controller
{
    /**
     * @Route("/new")
     * @Template(":Groups:new.html.twig")
     */
    public function newAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $contactGroup = new ContactGroup();
        $contactGroup->setUser($user);
        $form = $this->createForm(ContactGroupType::class, $contactGroup);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($contactGroup);

            $em->flush();

            return $this->redirectToRoute('addressbook_group_show', ['id' => $contactGroup->getId()]);
        }

        return ['form' => $form->createView()];
    }
    /**
     * @Route("/")
     * @Template(":Groups:show_all.html.twig")
     */
    public function showAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $groups = $this->getDoctrine()->getManager()
            ->getRepository("AddressBookBundle:ContactGroup")->findBy(['user' => $user->getId()]);

        return ['groups' => $groups];
    }

    /**
     * @Route("/{id}")
     * @Template(":Groups:show_single.html.twig")
     */
    public function showSingleAction($id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $group = $this->getDoctrine()->getManager()
            ->getRepository("AddressBookBundle:ContactGroup")->findOneBy(['id' => $id, 'user' => $user->getId()]);
        if (!$group) {
            throw $this->createNotFoundException("Group not found");
        }

        return ['group' => $group];
    }

    /**
     * @Route("/{id}/delete/")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $contactGroup = $em->getRepository("AddressBookBundle:ContactGroup")->findOneBy(['id' => $id, 'user' => $user->getId()]);
        if (!$contactGroup) {
            throw $this->createNotFoundException("Group not found");
        }

        $contacts = $em->getRepository("AddressBookBundle:Contact")
            ->loadAllWithGroup($id);

        foreach ($contacts as $contact) {
            $contact->getGroups()->removeElement($contactGroup);
        }

        $em->remove($contactGroup);
        $em->flush();

        return $this->redirectToRoute('addressbook_group_show');
    }
}
