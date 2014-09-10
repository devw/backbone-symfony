<?php

namespace HighFive\MainBundle\Form\Type;

use HighFive\MainBundle\Form\DataTransformer\NullToZeroTransformer;
use HighFive\MainBundle\Form\DataTransformer\RecognitionToPointsTransformer;
use HighFive\MainBundle\Form\EventListener\RecognitionListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RecognitionType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $child = $builder->create('points', 'integer', array('property_path' => 'recognition'))
            ->addViewTransformer(new NullToZeroTransformer(), true)
            ->addModelTransformer(new RecognitionToPointsTransformer());

        $builder
            ->add($child)
            ->add('message', 'textarea')
            ->add('recipient_id', 'high_five_entity', array('property_path' => 'recipient', 'class' => 'MainBundle:User'))
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
            'validation_groups' => array('Default', 'recognition'),
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'recognition_api';
    }
}
