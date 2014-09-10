<?php

namespace HighFive\MainBundle\Util;

interface BaseUrlResolverInterface
{
    /**
     * Gets the base url for assets
     *
     * @return string
     */
    public function getBaseAssetUrl();
}
