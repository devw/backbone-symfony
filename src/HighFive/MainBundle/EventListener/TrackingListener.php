<?php

namespace HighFive\MainBundle\EventListener;

use HighFive\MainBundle\HighFiveEvents;
use HighFive\MainBundle\Entity\User;
use HighFive\MainBundle\Event\InvitationEvent;
use HighFive\MainBundle\Event\UserEvent;
use HighFive\MainBundle\Tracking\KissmetricsTracker;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Listener responsible to track the customer behavior
 */
class TrackingListener implements EventSubscriberInterface
{
    private $tracker;
    private $securityContext;

    public function __construct(KissmetricsTracker $tracker, SecurityContextInterface $securityContext)
    {
        $this->tracker = $tracker;
        $this->securityContext = $securityContext;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            HighFiveEvents::INVITATION_SENT => 'onInvitationSent',
            HighFiveEvents::REGISTRATION_INVITED => 'onInvitedRegistration',
            HighFiveEvents::REGISTRATION_REGISTER => 'onRegistration',
            KernelEvents::REQUEST => array(array('onKernelRequest'), array('onEarlyKernelRequest', 256)),
        );
    }

    public function onInvitationSent(InvitationEvent $event)
    {
        $this->tracker->record('Sending invitations', array(
            'already_registered' => count($event->getRegisteredEmails()),
            'already_invited' => count($event->getInvitedEmails()),
            'invited_again' => count($event->getResentEmails()),
            'newly_invited' => count($event->getSentEmails()),
        ));

        foreach (array_merge($event->getResentEmails(), $event->getSentEmails()) as $email) {
            $this->tracker->recordForIdentity('Received invitation', $email);
        }
    }

    public function onInvitedRegistration(UserEvent $event)
    {
        $this->tracker->identify($event->getUser()->getEmail());
        $this->tracker->record('Signup with invitation');
    }

    public function onRegistration(UserEvent $event)
    {
        $this->tracker->identify($event->getUser()->getEmail());
        $this->tracker->record('Signup with a new account');
    }

    /**
     * Sets the identity from the cookies set by the JS API if available.
     *
     * This ensures that the data are tracked for anonymous users too.
     *
     * @param GetResponseEvent $event
     */
    public function onEarlyKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();

        if ($request->cookies->has('km_ni')) {
            $this->tracker->identify($request->cookies->get('km_ni'));

            return;
        }

        if ($request->cookies->has('km_ai')) {
            $this->tracker->identify($request->cookies->get('km_ai'));
        }
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $token = $this->securityContext->getToken();
        if (null === $token) {
            return;
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            return;
        }
        /** @var $user User */

        if ($this->tracker->getIdentity() !== $user->getEmail()) {
            $this->tracker->alias($user->getEmail(), $this->tracker->getIdentity());
        }

        $this->tracker->identify($user->getEmail());
    }
}
