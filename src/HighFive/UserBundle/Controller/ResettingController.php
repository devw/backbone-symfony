<?php

namespace HighFive\UserBundle\Controller;

use FOS\UserBundle\Controller\ResettingController as BaseResettingController;
use FOS\UserBundle\Model\UserInterface;

class ResettingController extends BaseResettingController
{
    protected function getRedirectionUrl(UserInterface $user)
    {
        return $this->container->get('router')->generate('frontend');
    }
}
