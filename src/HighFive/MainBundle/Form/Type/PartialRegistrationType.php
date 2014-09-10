<?php

namespace HighFive\MainBundle\Form\Type;

use HighFive\MainBundle\Form\EventListener\NameGuesserListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PartialRegistrationType extends AbstractType
{
    private $nameGuesserListener;

    public function __construct(NameGuesserListener $nameGuesserListener)
    {
        $this->nameGuesserListener = $nameGuesserListener;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array('disabled' => true, 'label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
            ->add('plainPassword', 'password', array(
                'translation_domain' => 'FOSUserBundle',
                'label' => 'form.password',
            ))
            ->addEventSubscriber($this->nameGuesserListener)
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HighFive\MainBundle\Entity\User',
            'intention'  => 'partial_registration',
            'validation_groups' => array('FullRegistration'),
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'high_five_partial_registration';
    }
}
