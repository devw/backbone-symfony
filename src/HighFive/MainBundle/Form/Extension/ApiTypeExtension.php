<?php

namespace HighFive\MainBundle\Form\Extension;

use HighFive\MainBundle\Form\EventListener\BindApiRequestListener;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ApiTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['api_binding']) {
            $builder->addEventSubscriber(new BindApiRequestListener());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'api_binding' => false,
        ));
        $resolver->setAllowedTypes(array(
            'api_binding' => 'bool',
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedType()
    {
        return 'form';
    }
}
