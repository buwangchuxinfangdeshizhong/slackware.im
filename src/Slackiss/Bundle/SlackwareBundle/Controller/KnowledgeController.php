<?php

namespace Slackiss\Bundle\SlackwareBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Slackiss\Bundle\SlackwareBundle\Form\ItemType;
use Slackiss\Bundle\SlackwareBundle\Entity\Item;
use Slackiss\Bundle\SlackwareBundle\Entity\ItemCategory;

/**
 * @Route("/knowledge")
 */
class KnowledgeController extends Controller
{

    /**
     * @Route("/",name="knowledge")
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $param =  array();
        return $param;
    }

    /**
     * @Route("/member/new",name="knowledge_new")
     * @Method({"GET"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $param =  array();
        $em = $this->getDoctrine()->getManager();
        $current = $this->get('security.context')->getToken()->getUser();

        return $param;
    }


}
