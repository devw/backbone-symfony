<?php

namespace HighFive\MainBundle\Avatar;

use HighFive\MainBundle\Entity\User;

interface AvatarResolverInterface
{
    /**
     * Gets the url of the avatar of the user.
     *
     * @param User $user
     *
     * @return string
     */
    public function getAvatarUrl(User $user);
}
