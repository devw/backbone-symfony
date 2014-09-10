<?php

namespace HighFive\MainBundle\Twig;

use HighFive\MainBundle\Avatar\AvatarResolverInterface;
use HighFive\MainBundle\Entity\User;
use HighFive\MainBundle\Util\BaseUrlResolverInterface;

class HighFiveExtension extends \Twig_Extension
{
    private $avatarResolver;
    private $baseUrlResolver;
    private $supportAddress;

    public function __construct(AvatarResolverInterface $avatarResolver, BaseUrlResolverInterface $baseUrlResolver, $highFiveSupportAddress)
    {
        $this->avatarResolver = $avatarResolver;
        $this->baseUrlResolver = $baseUrlResolver;
        $this->supportAddress = $highFiveSupportAddress;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'high_five';
    }

    public function getFunctions()
    {
        return array(
            'get_avatar' => new \Twig_Function_Method($this, 'getAvatar'),
            'get_base_asset_url' => new \Twig_Function_Method($this, 'getBaseAssetUrl'),
        );
    }

    public function getGlobals()
    {
        return array(
            'highfive_support_mail' => $this->supportAddress,
        );
    }

    public function getAvatar(User $user)
    {
        return $this->avatarResolver->getAvatarUrl($user);
    }

    public function getBaseAssetUrl()
    {
        return $this->baseUrlResolver->getBaseAssetUrl();
    }
}
