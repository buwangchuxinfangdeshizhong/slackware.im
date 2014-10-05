<?php

namespace Slackiss\Bundle\SlackwareBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventType extends AbstractType
{

    protected $isEdit;

    public function __construct($isEdit = false)
    {
        $this->isEdit = $isEdit;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title','text',array(
                'label'=>'活动名称',
                'required'=>true,
                'attr'=>array(
                    'class'=>'input-block-level',
                    'placeholder'=>'活动名称'
                )
            ))
            ->add('image','file',array(
                'label'=>'活动封面',
                'required'=>!$this->isEdit,
                'attr'=>array(

                )
            ))
            ->add('content','textarea',array(
                'label'=>'活动介绍',
                'required'=>true,
                'attr'=>array(
                    'class'=>'input-block-level',
                    'placeholder'=>'请详细介绍活动内容,流程,活动主办人的联系方式及其他相关信息',
                    'rows'=>6
                )
            ))
            ->add('eventdate','text',array(
                'label'=>'活动时间',
                'required'=>true,
                'attr'=>array(
                    'class'=>'input-block-level',
                    'placeholder'=>'例如:5月8日星期六早上8点到中午11点'
                )
            ))
            ->add('address','text',array(
                'label'=>'活动地点',
                'required'=>true,
                'attr'=>array(
                    'class'=>'input-block-level',
                    'placeholder'=>'例如:北京西直门座地铁出站向右转200米ABC咖啡馆'
                )
            ))
            ->add('fee','text',array(
                'label'=>'活动费用',
                'required'=>true,
                'attr'=>array(
                    'class'=>'input-block-level',
                    'placeholder'=>'例如:每个平均50元；再比如：免费'
                )
            ))
            ->add('contact','text',[
                'label'=>'现场联系方式(报名用户才能够查看)',
                'required'=>true,
                'attr'=>[
                    'class'=>'input-block-level',
                    'placeholder'=>'手机：15854100000 微信：slackware.im 联系人：张三丰'
                ]
            ])
            ->add('lastApplyDate','date',[
                'label'=>'最晚报名日期',
                'required'=>true,
                'attr'=>[
                    'class'=>'input-block-level last-apply-date',
                ]
            ])
            ->add('保存活动','submit',array(
                'attr'=>array(
                    'style'=>'margin-top:20px'
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Slackiss\Bundle\SlackwareBundle\Entity\Event'
        ));
    }

    public function getName()
    {
        return 'slackiss_bundle_slackwarebundle_eventtype';
    }
}
