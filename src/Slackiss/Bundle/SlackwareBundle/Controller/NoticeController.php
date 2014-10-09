<?php

namespace Slackiss\Bundle\SlackwareBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Slackiss\Bundle\SlackwareBundle\Form\NoticeEmailSettingType;

/**
 * @Route("/notice")
 */
class NoticeController extends Controller
{

    /**
     * @Route("/",name="notice")
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $param =  ['nav_active'=>'nav_active_notice'];
        $messageService = $this->get('slackiss_slackware.message');
        $current = $this->get('security.context')->getToken()->getUser();
        $page    = $request->query->get('page',1);
        $messages = $messageService->getMessages($current,$page,50);
        $param['messages'] = $messages;
        return $param;
    }

    /**
     * @Route("/setting",name="notice_email_setting")
     * @Method({"GET"})
     * @Template()
     */
    public function noticeSettingAction(Request $request)
    {
        $param =  ['nav_active'=>'nav_active_notice'];
        $current = $this->get('security.context')->getToken()->getUser();
        $setting = $this->get('slackiss_slackware.notice')->getSetting($current);
        $form = $this->getNoticeSettingForm($setting);
        $param['form'] = $form->createView();
        return $param;
    }

    /**
     * @Route("/updatesetting",name="notice_email_setting_update")
     * @Method({"POST"})
     * @Template("SlackissSlackwareBundle:Notice:noticeSetting.html.twig")
     */
    public function noticeSettingUpdateAction(Request $request)
    {
        $param =  array();
        $current = $this->get('security.context')->getToken()->getUser();
        $setting = $this->get('slackiss_slackware.notice')->getSetting($current);
        $form = $this->getNoticeSettingForm($setting);
        $form->handleRequest($request);
        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($setting);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success','保存成功');
            return $this->redirect($this->generateUrl('notice_email_setting'));
        }

        $param['form'] = $form->createView();
        return $param;
    }

    protected function getNoticeSettingForm($setting)
    {
        $formType = new NoticeEmailSettingType();
        $form = $this->createForm($formType, $setting,[
            'method'=>'POST',
            'action'=>$this->generateUrl('notice_email_setting_update')
        ]);
        return $form;
    }
}
