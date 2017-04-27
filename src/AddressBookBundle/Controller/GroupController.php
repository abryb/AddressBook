<?php

namespace AddressBookBundle\Controller;

use AddressBookBundle\Entity\ContactGroup;
use AddressBookBundle\Form\ContactGroupType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GroupController
 * @Route("/groups")
 */
class GroupController extends Controller
{
    /**
     * @Route("/new")
     * @Template(":Groups:new.html.twig")
     */
    public function newAction(Request $request)
    {
        $contactGroup = new ContactGroup();

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
        $groups = $this->getDoctrine()->getManager()
            ->getRepository("AddressBookBundle:ContactGroup")->findAll();

        return ['groups' => $groups];
    }

    /**
     * @Route("/{id}")
     * @Template(":Groups:show_single.html.twig")
     */
    public function showSingleAction($id)
    {
        $group = $this->getDoctrine()->getManager()
            ->getRepository("AddressBookBundle:ContactGroup")->find($id);

        return ['group' => $group];
    }
}
