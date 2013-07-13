<?php

namespace Slackiss\Bundle\SlackwareBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChangeLogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content','textarea',array(
                'label'=>'变更日志',
                'required'=>true,
                'attr'=>array(
                    'class'=>'input-block-level',
                    'rows'=>8
                )
            ))
            ->add('type','choice',array(
                'label'     => '类别',
                'choices'   => array('1' => 'SlackwareCurrentX86',
                                     '2' => 'SlackwareCurrentX64',
                                     '3'=>'SlackwareCurrentARM',
                                     '4'=>'SlackwareCurrent390',
                                     '5'=>'SlackwareStableX86',
                                     '6'=>'SlackwareStableX64',
                                     '7'=>'SlackwareStable390',
                                     '8'=>'SlackwareStableARM',
                                     '9'=>'SlackwareStable39064',
                                     '10'=>'SlackwareCurrent390X64'
                ),
                'required'  => true,
            ))
            ->add('保存','submit')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Slackiss\Bundle\SlackwareBundle\Entity\ChangeLog'
        ));
    }

    public function getName()
    {
        return 'slackiss_bundle_slackwarebundle_changelogtype';
    }
}
