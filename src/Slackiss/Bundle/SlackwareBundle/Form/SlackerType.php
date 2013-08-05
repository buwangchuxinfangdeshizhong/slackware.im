<?php

namespace Slackiss\Bundle\SlackwareBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SlackerType extends AbstractType
{
	private $isEdit;
	public function __construct($isEdit=false)
	{
		$this->isEdit = $isEdit;
	}
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nickname',"text",array(
                'label'=>'昵称(必填)',
                'required'=>true,
                'attr'=>array(
                    'class'=>'input-block-level',
                    'placeholder'=>'昵称'
                )
            ))
            ->add('city','text',array(
                'label'=>'我的位置(必填)',
                'required'=>true,
                'attr'=>array(
                    'class'=>'input-block-level',
                    'placeholder'=>'比如：北京西直门'
                )
            ));
		    if($this->isEdit){
				$builder->add('image','file',array(
					'label'=>'个人头像设置',
					'required'=>false,
					'attr'=>array(

					)
				));
			}else{
				$builder->add('image','file',array(
					'label'=>'个人头像设置(必须上传头像)',
					'required'=>true,
					'attr'=>array(

					)
				));
			}
			$builder
				  ->add('description','textarea',array(
                'label'=>'Slacker介绍(必填)',
                'required'=>true,
                'attr'=>array(
                    'class'=>'input-block-level',
                    'rows'=>6,
                    'placeholder'=>'请用简洁的话介绍一下自己，不要超过400个字'
                )
            ))
            ->add('website','text',array(
                'label'=>'个人站点',
                'required'=>false,
                'attr'=>array(
                    'class'=>'input-block-level',
                    'placeholder'=>'个人网站或者个人博客'
                )
            ))
            ->add('twitter','text',array(
                'label'=>'Twitter地址',
                'required'=>false,
                'attr'=>array(
                    'class'=>'input-block-level',
                    'placeholder'=>'http://twitter.com/someslacker'
                )
            ))
            ->add('googleplus','text',array(
                'label'=>'Google Plus地址',
                'required'=>false,
                'attr'=>array(
                    'class'=>'input-block-level',
                    'placeholder'=>'http://plus.google.com/someslacker'
                )
            ))
            ->add('facebook','text',array(
                'label'=>'Facebook地址',
                'required'=>false,
                'attr'=>array(
                    'class'=>'input-block-level',
                    'placeholder'=>'http://facebook.com/someslacker'
                )
            ))
            ->add('weibo','text',array(
                'label'=>'微博地址',
                'required'=>false,
                'attr'=>array(
                    'class'=>'input-block-level',
                    'placeholder'=>'http://weibo.com/someslacker'
                )
            ))
            ->add('github','text',array(
                'label'=>'Github地址',
                'required'=>false,
                'attr'=>array(
                    'class'=>'input-block-level',
                    'placeholder'=>'http://github.com/someslacker'
                )
            ))
            ->add('css','textarea',array(
                'label'=>'自定义CSS',
                'required'=>false,
                'attr'=>array(
                    'class'=>'input-block-level',
                    'rows'=>4,
                    'placeholder'=>'如果你想改变自己的Slacker档案的样式，可以在这里自定义CSS，不需要添加<style></style>标签'
                )
            ))

            ->add('保存Slacker档案','submit',array(
                'attr'=>array(
                    'style'=>'display:block;margin-top:20px'
                )
            ));
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Slackiss\Bundle\SlackwareBundle\Entity\Member'
        ));
    }

    public function getName()
    {
        return 'slackiss_bundle_slackwarebundle_slackertype';
    }
}
