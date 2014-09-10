<?php

namespace HighFive\MainBundle\EventListener;

use HighFive\MainBundle\HighFiveEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RequestContextAwareInterface;

/**
 * Listener responsible to complete the router initialization in the CLI.
 */
class CliRouterListener implements EventSubscriberInterface
{
    private $router;
    private $baseUrl;

    public function __construct(RequestContextAwareInterface $router, $baseUrl)
    {
        $this->router = $router;
        $this->baseUrl = $baseUrl;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            HighFiveEvents::CONSOLE_INIT => 'init',
        );
    }

    public function init()
    {
        $this->router->getContext()->setBaseUrl($this->baseUrl);
    }
}
