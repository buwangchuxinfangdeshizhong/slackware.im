<?php

namespace Slackiss\Bundle\SlackwareBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FAQType extends AbstractType
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
                'label'=>'内容(支持img,h1,h2,h3,h4,h5,hr标签)',
                'required'=>true,
                'attr'=>array(
                    'class'=>'input-block-level',
                    'rows'=>16
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Slackiss\Bundle\SlackwareBundle\Entity\FAQ'
        ));
    }

    public function getName()
    {
        return 'slackiss_bundle_slackwarebundle_faqtype';
    }
}
