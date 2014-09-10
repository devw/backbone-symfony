<?php

namespace HighFive\MainBundle\Serializer\Handler;

use HighFive\MainBundle\Avatar\AvatarResolverInterface;
use HighFive\MainBundle\Entity\User;
use JMS\SerializerBundle\Serializer\Handler\SerializationHandlerInterface;
use JMS\SerializerBundle\Serializer\VisitorInterface;

class AvatarHandler implements SerializationHandlerInterface
{
    private $avatarResolver;

    public function __construct(AvatarResolverInterface $avatarResolver)
    {
        $this->avatarResolver = $avatarResolver;
    }

    public function serialize(VisitorInterface $visitor, $data, $type, &$handled)
    {
        if (!$data instanceof User) {
            return;
        }
        /** @var $data User */

        $data->setAvatarUrl($this->avatarResolver->getAvatarUrl($data));
    }
}
