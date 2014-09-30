<?php

namespace Slackiss\Bundle\SlackwareBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Slackiss\Bundle\SlackwareBundle\Entity\Image;
use Slackiss\Bundle\SlackwareBundle\Form\ImageType;

/**
 * Image controller.
 *
 * @Route("/manage/image")
 */
class ImageController extends Controller
{

    /**
     * Lists all Image entities.
     *
     * @Route("/", name="manage_image_")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $request->query->get('page',1);
        $query = $em->getRepository('SlackissSlackwareBundle:Image')
                    ->createQueryBuilder('i')
                    ->orderBy('i.id','desc')
                    ->getQuery();
        $entities = $this->get('knp_paginator')->paginate($query,$page,20);

        return array(
            'nav_active'=>'nav_active_faq',
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Image entity.
     *
     * @Route("/", name="manage_image__create")
     * @Method("POST")
     * @Template("SlackissSlackwareBundle:Image:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Image();
        $current = $this->get('security.context')->getToken()->getUser();
        $form = $this->createForm(new ImageType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setMember($current);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('manage_image_', array('id' => $entity->getId())));
        }

        return array(
            'nav_active'=>'nav_active_faq',
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Image entity.
     *
     * @Route("/new", name="manage_image__new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Image();
        $form   = $this->createForm(new ImageType(), $entity);

        return array(
            'nav_active'=>'nav_active_faq',
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Deletes a Image entity.
     *
     * @Route("/delete/{id}", name="manage_image__delete")
     * @Method({"DELETE","GET"})
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SlackissSlackwareBundle:Image')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Image entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('manage_image_'));
    }

}
