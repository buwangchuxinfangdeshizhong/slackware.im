<?php

namespace Slackiss\Bundle\SlackwareBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostCommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content','textarea',array(
                'label'=>'评论内容(必填)',
                'required'=>true,
                'attr'=>array(
                    'class'=>'input-block-level',
                    'rows'=>6,
                    'placeholder'=>'评论内容:支持使用<pre></pre>标签贴代码'
                )
            ))
            ->add('image',null,array(
                'label'=>'附加图片',
                'required'=>false,
                'attr'=>array(
                )
            ))
            ->add('创建评论','submit',array(
                'attr'=>array(
                    'style'=>'margin-top:20px'
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Slackiss\Bundle\SlackwareBundle\Entity\PostComment'
        ));
    }

    public function getName()
    {
        return 'slackiss_bundle_slackwarebundle_postcommenttype';
    }
}
