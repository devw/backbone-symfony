<?php

namespace HighFive\MainBundle;

/**
 * Contains all events thrown in the HighFive MainBundle
 */
final class HighFiveEvents
{
    /**
     * The CONSOLE_INIT event occurs before running commands
     *
     * The event listener method receives a HighFive\MainBundle\Event\ConsoleEvent
     * instance.
     *
     * @var string
     */
    const CONSOLE_INIT = 'high_five.console.init';

    /**
     * The CONSOLE_TERMINATE event occurs after running commands
     *
     * The event listener method receives a HighFive\MainBundle\Event\ConsoleTerminateEvent
     * instance.
     *
     * @var string
     */
    const CONSOLE_TERMINATE = 'high_five.console.terminate';

    /**
     * The INVITATION_SENT event occurs when invitations have been sent
     *
     * The event listener method receives a HighFive\MainBundle\Event\InvitationEvent
     * instance.
     *
     * @var string
     */
    const INVITATION_SENT = 'high_five.invitation.sent';

    /**
     * The MESSAGE_REPLY event occurs before saving a new reply
     *
     * The event listener method receives a HighFive\MainBundle\Event\MessageEvent
     * instance.
     *
     * @var string
     */
    const MESSAGE_REPLY = 'high_five.message.reply';

    /**
     * The RECOGNITION_CREATE event occurs before saving a new recognition
     *
     * The event listener method receives a HighFive\MainBundle\Event\RecognitionEvent
     * instance.
     *
     * @var string
     */
    const RECOGNITION_CREATE = 'high_five.recognition.create';

    /**
     * The REGISTRATION_INVITED event occurs when the user registers using an invitation
     *
     * The event listener method receives a HighFive\MainBundle\Event\UserEvent
     * instance.
     *
     * @var string
     */
    const REGISTRATION_INVITED = 'high_five.registration.invited';

    /**
     * The REGISTRATION_REGISTER event occurs when the user registers first.
     *
     * The event listener method receives a HighFive\MainBundle\Event\UserEvent
     * instance.
     *
     * @var string
     * @todo Remove this event once FOSUserBundle provides its own event.
     */
    const REGISTRATION_REGISTER = 'high_five.recognition.register';
}
