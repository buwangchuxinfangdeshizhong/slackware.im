<?php

namespace Slackiss\Bundle\SlackwareBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NoticeEmailSettingType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sendEmail',null,[
                'label'=>'是否接收站内通知',
            ])
            ->add('email1','text',[
                'label'=>'通知邮箱1',
                'required'=>false,
                'attr'=>[
                    'class'=>'input-block-level'
                ]
            ])
            ->add('email2','text',[
                'label'=>'通知邮箱2',
                'required'=>false,
                'attr'=>[
                    'class'=>'input-block-level'
                ]
            ])
            ->add('email3','text',[
                'label'=>'通知邮箱3',
                'required'=>false,
                'attr'=>[
                    'class'=>'input-block-level'
                ]
            ])
            ->add('email4','text',[
                'label'=>'通知邮箱4',
                'required'=>false,
                'attr'=>[
                    'class'=>'input-block-level'
                ]
            ])
            ->add('保存','submit')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Slackiss\Bundle\SlackwareBundle\Entity\NoticeEmailSetting'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'slackiss_bundle_slackwarebundle_noticeemailsetting';
    }
}
