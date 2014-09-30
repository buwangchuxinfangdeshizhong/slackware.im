<?php

namespace Slackiss\Bundle\SlackwareBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image','file',array(
                'label'=>'选择图片',
                'required'=>true,
            ))
            ->add('上传','submit',array(
                'attr'=>array(
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Slackiss\Bundle\SlackwareBundle\Entity\Image'
        ));
    }

    public function getName()
    {
        return 'slackiss_bundle_slackwarebundle_imagetype';
    }
}
