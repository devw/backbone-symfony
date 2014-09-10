<?php

namespace HighFive\MainBundle\Model;

use HighFive\MainBundle\HighFiveEvents;
use HighFive\MainBundle\Entity\Message;
use HighFive\MainBundle\Entity\Recognition;
use HighFive\MainBundle\Entity\User;
use HighFive\MainBundle\Event\MessageEvent;
use HighFive\MainBundle\Event\RecognitionEvent;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MessageManager
{
    private $registry;
    private $dispatcher;

    public function __construct(ManagerRegistry $registry, EventDispatcherInterface $dispatcher)
    {
        $this->registry = $registry;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param int $id
     *
     * @return Message
     */
    public function find($id)
    {
        return $this->getMessageRepository()->find($id);
    }

    /**
     * Creates a new reply.
     *
     * @param User    $sender
     * @param Message $parent
     *
     * @return Message
     */
    public function createReply(User $sender, Message $parent)
    {
        $message = new Message();
        $message->setSender($sender)
            ->setOrganization($sender->getOrganization())
            ->setParent($parent)
            ->setRecipient($parent->getRecipient())
            ->setCreatedAt(new \DateTime());

        return $message;
    }

    /**
     * Creates a new recognition message.
     *
     * @param User $sender
     *
     * @return Message
     */
    public function createRecognitionMessage(User $sender)
    {
        $message = new Message();
        $message->setSender($sender)
            ->setOrganization($sender->getOrganization())
            ->setCreatedAt(new \DateTime());

        return $message;
    }

    /**
     * Creates a new announcement message.
     *
     * @param User $sender
     *
     * @return Message
     */
    public function createAnnouncement(User $sender)
    {
        $message = new Message();
        $message->setSender($sender)
            ->setRecipient($sender)
            ->setOrganization($sender->getOrganization())
            ->setCreatedAt(new \DateTime());

        return $message;
    }

    /**
     * Saves a new reply.
     *
     * @param Message $message
     * @param boolean $flush
     */
    public function saveReply(Message $message, $flush = true)
    {
        $this->dispatcher->dispatch(HighFiveEvents::MESSAGE_REPLY, new MessageEvent($message));

        $this->saveMessage($message, $flush);
    }

    /**
     * Saves a new message.
     *
     * @param Message $message
     * @param boolean $flush
     */
    public function saveMessage(Message $message, $flush = true)
    {
        $em = $this->registry->getManager();
        $em->persist($message);

        if (null !== $message->getRecognition()) {
            $this->saveRecognition($message->getRecognition());
        }

        if ($flush) {
            $em->flush();
        }
    }

    /**
     * Saves a new recognition.
     *
     * @param Recognition $recognition
     */
    private function saveRecognition(Recognition $recognition)
    {
        $this->dispatcher->dispatch(HighFiveEvents::RECOGNITION_CREATE, new RecognitionEvent($recognition));

        $this->registry->getManager()->persist($recognition);
    }

    /**
     * @return \HighFive\MainBundle\Doctrine\Repository\MessageRepository
     */
    private function getMessageRepository()
    {
        return $this->registry->getRepository('MainBundle:Message');
    }
}
