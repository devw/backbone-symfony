<?php

namespace HighFive\MainBundle\Form\Type;

use HighFive\MainBundle\Form\DataTransformer\NullToZeroTransformer;
use HighFive\MainBundle\Form\DataTransformer\RecognitionToPointsTransformer;
use HighFive\MainBundle\Form\EventListener\RecognitionListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReplyType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $child = $builder->create('points', 'integer', array('property_path' => 'recognition', 'required' => false))
            ->addViewTransformer(new NullToZeroTransformer(), true)
            ->addModelTransformer(new RecognitionToPointsTransformer());

        $builder
            ->add($child)
            ->add('message', 'textarea')
            ->addEventSubscriber(new RecognitionListener())
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HighFive\MainBundle\Entity\Message',
            'csrf_protection' => false,
            'api_binding' => true,
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'reply_api';
    }
}
