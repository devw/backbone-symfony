<?php

namespace HighFive\MainBundle\Tests\Notification;

use HighFive\MainBundle\Notification\NotificationManager;

class NotificationManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testSend()
    {
        $manager = new NotificationManager();

        for ($i = 0; $i < 2; $i++) {
            $handler = $this->getMock('HighFive\MainBundle\Notification\HandlerInterface');
            $handler->expects($this->once())
                ->method('send');

            $manager->addHandler($handler);
        }

        $manager->send('test', $this->getMock('HighFive\MainBundle\Entity\User'), new \stdClass());
    }
}
