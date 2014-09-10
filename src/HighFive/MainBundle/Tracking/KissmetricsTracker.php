<?php

namespace HighFive\MainBundle\Tracking;

use Buzz\Browser;
use Buzz\Message\RequestInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

class KissmetricsTracker
{
    const ENDPOINT = 'http://trk.kissmetrics.com/';

    private static $typeMap = array(
        'record' => 'e',
        'set' => 's',
        'alias' => 'a',
    );
    private $browser;
    private $logger;
    private $key;
    private $id;

    public function __construct(Browser $browser, $key, LoggerInterface $logger = null)
    {
        $this->browser = $browser;
        $this->key = $key;
        $this->logger = $logger;
    }

    public function getIdentity()
    {
        return $this->id;
    }

    public function identify($id)
    {
        $this->id = $id;
    }

    public function record($action, array $properties = array())
    {
        if (null === $this->id) {
            return;
        }

        $data = array_merge($properties, array('_n' => $action));

        $this->sendRequest('record', $data);
    }

    public function recordForIdentity($action, $identity, array $properties = array())
    {
        $data = array_merge($properties, array('_n' => $action, '_p' => $identity));

        $this->sendRequest('record', $data, false);
    }

    public function set(array $properties)
    {
        if (null === $this->id) {
            return;
        }

        $this->sendRequest('set', $properties);
    }

    public function alias($name, $alias)
    {
        $this->sendRequest('alias', array('_p' => $name, '_n' => $alias), false);
    }

    private function sendRequest($type, array $data, $updateIdentity = true)
    {
        if (!isset(self::$typeMap[$type])) {
            if (null !== $this->logger) {
                $this->logger->warn(sprintf('Invalid request type "%s"', $type));
            }

            return;
        }

        if (null === $this->key) {
            if (null !== $this->logger) {
                $this->logger->debug('Sending a tracking record without an api key', array('type' => $type, 'data' => $data));
            }

            return;
        }

        $url = self::ENDPOINT . self::$typeMap[$type];

        $data['_k'] = $this->key;
        if ($updateIdentity) {
            $data['_p'] = $this->id;
        }

        if (isset($data['_t'])) {
            $data['_d'] = 1;
        } else {
            $data['_t'] = time();
        }

        try {
            $this->browser->submit($url, $data, RequestInterface::METHOD_GET);
        } catch (\Exception $e) {
            if (null !== $this->logger) {
                $this->logger->info('Failed to submit the tracking request to Kissmetrics', array('url' => $url, 'data' => $data));
            }
        }
    }
}
