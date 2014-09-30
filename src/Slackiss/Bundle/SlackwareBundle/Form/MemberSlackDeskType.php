<?php

namespace Slackiss\Bundle\SlackwareBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MemberSlackDeskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image','file',array(
                'label'=>'SlackDesk图片'
            ))
            ->add('description','textarea',array(
                'label'=>'SlackDesk故事(非必填)',
                'required'=>false,
                'attr'=>array(
                    'class'=>'input-block-level',
                    'rows'=>4,
                    'placeholder'=>'SlackDesk故事最多不能超过500字'
                )
            ))
            ->add('分享','submit',array(
                'attr'=>array('style'=>'margin-top:20px')
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Slackiss\Bundle\SlackwareBundle\Entity\SlackDesk'
        ));
    }

    public function getName()
    {
        return 'slackiss_bundle_slackwarebundle_slackdesktype';
    }
}
