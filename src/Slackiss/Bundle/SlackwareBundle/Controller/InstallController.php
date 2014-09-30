<?php

namespace Slackiss\Bundle\SlackwareBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/install")
 */
class InstallController extends Controller
{
    /**
     * @Route("/",name="install")
     * @Template()
     * @Method({"GET"})
     */
    public function indexAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_install');
        return $param;
    }

    /**
     * @Route("/zero",name="install_zero")
     * @Template()
     * @Method({"GET"})
     */
    public function zeroAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_install');
        return $param;
    }

    /**
     * @Route("/one",name="install_one")
     * @Template()
     * @Method({"GET"})
     */
    public function oneAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_install');
        return $param;
    }
 
   /**
     * @Route("/two",name="install_two")
     * @Template()
     * @Method({"GET"})
     */
    public function twoAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_install');
        return $param;
    }
    /**
     * @Route("/three",name="install_three")
     * @Template()
     * @Method({"GET"})
     */
    public function threeAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_install');
        return $param;
    }

    /**
     * @Route("/four",name="install_four")
     * @Template()
     * @Method({"GET"})
     */
    public function fourAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_install');
        return $param;
    }

    /**
     * @Route("/five",name="install_five")
     * @Template()
     * @Method({"GET"})
     */
    public function fiveAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_install');
        return $param;
    }
}

