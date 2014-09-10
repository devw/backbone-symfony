<?php

namespace HighFive\MainBundle\Form\Handler;

use HighFive\MainBundle\HighFiveEvents;
use HighFive\MainBundle\Event\UserEvent;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Form\Handler\RegistrationFormHandler as BaseHandler;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use HighFive\MainBundle\Entity\Organization;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class RegistrationFormHandler extends BaseHandler
{
    private $entityManager;
    private $dispatcher;

    public function __construct(FormInterface $form, Request $request, UserManagerInterface $userManager,
        MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator, ObjectManager $entityManager, EventDispatcherInterface $dispatcher)
    {
        parent::__construct($form, $request, $userManager, $mailer, $tokenGenerator);

        $this->entityManager = $entityManager;
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritDoc}
     */
    protected function onSuccess(UserInterface $user, $confirmation)
    {
        /** @var $user \HighFive\MainBundle\Entity\User */
        $organization = $user->getOrganization();
        $this->entityManager->persist($organization);

        $this->dispatcher->dispatch(HighFiveEvents::REGISTRATION_REGISTER, new UserEvent($user));

        parent::onSuccess($user, $confirmation);
    }

    /**
     * {@inheritDoc}
     */
    protected function createUser()
    {
        /** @var $user \HighFive\MainBundle\Entity\User */
        $user = parent::createUser();
        $user->setOrganization(new Organization());
        $user->addRole('ROLE_ADMIN');

        return $user;
    }
}
