<?php

namespace Slackiss\Bundle\SlackwareBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Slackiss\Bundle\SlackwareBundle\Entity\FAQ;
use Slackiss\Bundle\SlackwareBundle\Form\FAQType;

/**
 * FAQ controller.
 *
 */
class FAQController extends Controller
{

    /**
     * Lists all FAQ entities.
     *
     * @Route("/faq/", name="faq")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $page = $request->query->get('page',1);
        $repo = $em->getRepository('SlackissSlackwareBundle:FAQ');
        $query = $repo->createQueryBuilder('f')
                      ->orderBy('f.id','desc')
                      ->getQuery();
        $entities = $this->get('knp_paginator')->paginate($query,$page,50);
        return array(
            'entities' => $entities,
            'nav_active'=>'nav_active_faq'
        );
    }

    /**
     * Creates a new FAQ entity.
     *
     * @Route("/manage/faq/create", name="faq_create")
     * @Method("POST")
     * @Template("SlackissSlackwareBundle:FAQ:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new FAQ();
        $form = $this->createForm(new FAQType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('faq_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'nav_active'=>'nav_active_faq'
        );
    }

    /**
     * Displays a form to create a new FAQ entity.
     *
     * @Route("/manage/faq/new", name="faq_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new FAQ();
        $form   = $this->createForm(new FAQType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'nav_active'=>'nav_active_faq'
        );
    }

    /**
     * Finds and displays a FAQ entity.
     *
     * @Route("/faq/{id}", name="faq_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SlackissSlackwareBundle:FAQ')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FAQ entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'nav_active'=>'nav_active_faq'
        );
    }

    /**
     * Displays a form to edit an existing FAQ entity.
     *
     * @Route("/manage/faq/{id}/edit", name="faq_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SlackissSlackwareBundle:FAQ')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FAQ entity.');
        }

        $editForm = $this->createForm(new FAQType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'nav_active'=>'nav_active_faq'
        );
    }

    /**
     * Edits an existing FAQ entity.
     *
     * @Route("/manage/faq/{id}/update", name="faq_update")
     * @Method("PUT")
     * @Template("SlackissSlackwareBundle:FAQ:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SlackissSlackwareBundle:FAQ')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FAQ entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new FAQType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('faq_show', array('id' => $entity->getId())));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'nav_active'=>'nav_active_faq'
        );
    }
    /**
     * Deletes a FAQ entity.
     *
     * @Route("/manage/faq/{id}/delete", name="faq_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SlackissSlackwareBundle:FAQ')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FAQ entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('faq'));
    }

    /**
     * Creates a form to delete a FAQ entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
