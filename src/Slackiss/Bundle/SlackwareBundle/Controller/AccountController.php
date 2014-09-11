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
 * @Route("/member/account")
 */
class AccountController extends Controller
{

    /**
     * @Route("/",name="member_account")
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $param =  array();
        $current = $this->get('security.context')->getToken()->getUser();
        $form = $this->getForm(['username'=>$current->getUsername(),
                                'email'   =>$current->getEmail()
                              ]);
        $param['form'] = $form->createView();
        return $param;
    }

    /**
     * @Route("/update",name="member_account_update")
     * @Method({"POST"})
     * @Template("SlackissSlackwareBundle:Account:index.html.twig")
     */
    public function updateAction(Request $request)
    {
        $param =  array();
        $em = $this->getDoctrine()->getManager();
        $current = $this->get('security.context')->getToken()->getUser();
        $originUsername = $current->getUsername();
        $originEmail    = $current->getEmail();
        $form = $this->getForm(['username'=>$current->getUsername(),
                               'email'   =>$current->getEmail()
        ]);
        $form->handleRequest($request);
        $userManager = $this->get('fos_user.user_manager');
        $data = $form->getData();
        if($form->isValid()
        &&!$userManager->findUserByUsername(trim($data['username']))
        &&!$userManager->findUserByEmail(trim($data['email']))
        ){
            $current->setUsername(trim($data['username']));
            $current->setEmail(trim($data['email']));
            $userManager->updateUser($current);
            $this->get('session')->getFlashBag()->add('success','保存成功');
            return $this->redirect($this->generateUrl('member_account'));
        }else{
            $this->get('session')->getFlashBag()->add('danger','请使用4-36个英文字母或数字作为用户名,同时请使用网站内还没有使用的电子信箱');
        }

        return $param;
    }

    protected function getForm($arr)
    {
        $form = $this->createFormBuilder($arr)
                     ->add('username','text',[
                         'required'=>true,
                         'label'=>'用户名(4-36位英文或数字)',
                         'attr'=>[
                             'class'=>'input-block-level',
                         ]
                     ])
                     ->add('email','email',[
                         'required'=>true,
                         'label'=>'电子信箱',
                         'attr'=>[
                             'class'=>'input-block-level',
                         ]
                     ])
                     ->add('submit','submit',[
                         'label'=>'保存'
                     ]);
        return $form->getForm();
    }

}
