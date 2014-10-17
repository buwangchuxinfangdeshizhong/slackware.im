<?php

namespace Slackiss\Bundle\SlackwareBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('path','text',[
                'label'=>'标题',
                'required'=>true,
                'attr'=>[
                    'class'=>"input-block-level"
                ]
            ])
            ->add('content','textarea',[
                'label'=>'内容',
                'required'=>true,
                'attr'=>[
                    'class'=>'input-block-level',
                    'rows'=>26,
                    'id'=>'knowledge-editor'
                ]
            ])
            ->add('changelog','textarea',[
                'label'=>'变更日志',
                'required'=>true,
                'attr'=>[
                    'class'=>'input-block-level',
                    'rows'=>3
                ]
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Slackiss\Bundle\SlackwareBundle\Entity\Item'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'slackiss_bundle_slackwarebundle_item';
    }
}
