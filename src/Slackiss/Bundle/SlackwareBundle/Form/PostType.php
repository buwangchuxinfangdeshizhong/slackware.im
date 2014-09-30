<?php

namespace Slackiss\Bundle\SlackwareBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title','text',array(
                'label'=>'讨论标题(必填)',
                'required'=>true,
                'attr'=>array(
                    'class'=>'input-block-level',
                    'placeholder'=>'讨论标题'
                )
            ))
            ->add('category',null,[
                'label'=>'版块',
                'required'=>true,
                'property'=>'name',
                'attr'=>[
                    'class'=>'input-block-level'
                ]
            ])
            ->add('content','textarea',array(
                'label'=>'讨论内容(必填)',
                'required'=>true,
                'attr'=>array(
                    'class'=>'input-block-level',
                    'rows'=>10,
                    'placeholder'=>'讨论内容:支持<code></code>标签用于贴代码.'
                )
            ))
            ->add('image','file',array(
                'label'=>'附加图片',
                'required'=>false,
                'attr'=>array(
                )
            ))
            ->add('创建讨论','submit',array(
                'attr'=>array(
                    'style'=>'margin-top:20px;'
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Slackiss\Bundle\SlackwareBundle\Entity\Post'
        ));
    }

    public function getName()
    {
        return 'slackiss_bundle_slackwarebundle_posttype';
    }
}
