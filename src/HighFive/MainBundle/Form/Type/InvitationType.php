<?php

namespace HighFive\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Email;

class InvitationType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'attr' => array('data-tid' => 'emails'),
            'type' => 'email',
            'allow_add' => true,
            'options' => array(
                'required' => false,
                'attr' => array('placeholder' => 'invitation.placeholder.email'),
            ),
            'constraints' => new All(new Email(array(
                'message' => 'invitation.invalid_email',
            ))),
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'collection';
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'high_five_invitation';
    }
}
