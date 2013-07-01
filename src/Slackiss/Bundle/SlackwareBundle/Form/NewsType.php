<?php

namespace Slackiss\Bundle\SlackwareBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title','text',array(
                'label'=>'标题',
                'required'=>true,
                'attr'=>array(
                    'class'=>'input-block-level'
                )
            ))
            ->add('content','textarea',array(
                'label'=>'内容',
                'required'=>true,
                'attr'=>array(
                    'class'=>'input-block-level',
                    'rows'=>10
                )
            ))
            ->add('保存','submit',array(
                'attr'=>array(
                    'style'=>'margin-top:20px'
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Slackiss\Bundle\SlackwareBundle\Entity\News'
        ));
    }

    public function getName()
    {
        return 'slackiss_bundle_slackwarebundle_newstype';
    }
}
