<?php

namespace HighFive\MainBundle\Tests\Notification;

use HighFive\MainBundle\Entity\Notification;
use HighFive\MainBundle\Notification\NotificationManager;
use HighFive\MainBundle\Notification\WebHandler;

class WebHandlerTest extends \PHPUnit_Framework_TestCase
{
    private $entity;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $em;

    /**
     * @var WebHandler
     */
    private $handler;

    protected function setUp()
    {
        $this->em = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->em->expects($this->any())
            ->method('persist')
            ->will($this->returnCallback(array($this, 'storePersistedEntity')));

        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $registry->expects($this->any())
            ->method('getManager')
            ->will($this->returnValue($this->em));

        $this->handler = new WebHandler($registry);
    }

    protected function tearDown()
    {
        $this->entity = null;
        $this->handler = null;
        $this->em = null;
    }

    public function storePersistedEntity($entity)
    {
        $this->entity = $entity;
    }

    public function testReply()
    {
        $recipient = $this->getMock('HighFive\MainBundle\Entity\User');

        $messageSender = $this->getMock('HighFive\MainBundle\Entity\User');
        $messageSender->expects($this->any())
            ->method('getFirstName')
            ->will($this->returnValue('Chuck'));
        $messageSender->expects($this->any())
            ->method('getLastName')
            ->will($this->returnValue('Norris'));

        $message = $this->getMock('HighFive\MainBundle\Entity\Message');
        $message->expects($this->any())
            ->method('getSender')
            ->will($this->returnValue($messageSender));

        $this->handler->send(NotificationManager::TYPE_REPLY, $recipient, $message);

        $this->assertNotNull($this->entity);

        /** @var $entity \HighFive\MainBundle\Entity\Notification */
        $entity = $this->entity;

        $this->assertSame($recipient, $entity->getRecipient());
        $this->assertNull($entity->getSender());
        $this->assertEquals(Notification::REPLY, $entity->getMessage());
        $this->assertEquals(array('first_name' => 'Chuck', 'last_name' => 'Norris'), $entity->getParameters());
    }

    public function testReceivePoints()
    {
        $recipient = $this->getMock('HighFive\MainBundle\Entity\User');
        $sender = $this->getMock('HighFive\MainBundle\Entity\User');

        $recognitionSender = $this->getMock('HighFive\MainBundle\Entity\User');
        $recognitionSender->expects($this->any())
            ->method('getFirstName')
            ->will($this->returnValue('Chuck'));
        $recognitionSender->expects($this->any())
            ->method('getLastName')
            ->will($this->returnValue('Norris'));

        $recognition = $this->getMock('HighFive\MainBundle\Entity\Recognition');
        $recognition->expects($this->any())
            ->method('getSender')
            ->will($this->returnValue($recognitionSender));
        $recognition->expects($this->any())
            ->method('getPoints')
            ->will($this->returnValue(20));

        $this->handler->send(NotificationManager::TYPE_RECEIVE_POINTS, $recipient, $recognition, $sender);

        $this->assertNotNull($this->entity);

        /** @var $entity \HighFive\MainBundle\Entity\Notification */
        $entity = $this->entity;

        $this->assertSame($recipient, $entity->getRecipient());
        $this->assertSame($sender, $entity->getSender());
        $this->assertEquals(Notification::RECEIVE_POINTS, $entity->getMessage());
        $this->assertEquals(array('first_name' => 'Chuck', 'last_name' => 'Norris', 'points' => 20), $entity->getParameters());
    }

    public function testUnsupportedType()
    {
        $this->em->expects($this->never())
            ->method('persist');

        $this->handler->send('unsupported', $this->getMock('HighFive\MainBundle\Entity\User'), new \stdClass());
    }
}
