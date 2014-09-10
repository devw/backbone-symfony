<?php

namespace HighFive\MainBundle\Avatar;

use HighFive\MainBundle\Entity\User;

class AvatarResolver implements AvatarResolverInterface
{
    private $defaultAvatar;

    /**
     * @param string $defaultAvatar The default gravatar image
     */
    public function __construct($defaultAvatar)
    {
        $this->defaultAvatar = $defaultAvatar;
    }

    /**
     * {@inheritDoc}
     */
    public function getAvatarUrl(User $user)
    {
        $hash = md5(strtolower(trim($user->getEmail())));
        $url = 'https://secure.gravatar.com/avatar/' . $hash . '?' . http_build_query(array_filter(array('d' => $this->defaultAvatar)), '', '&');

        return $url;
    }
}
