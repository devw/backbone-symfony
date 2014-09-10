<?php

namespace HighFive\MainBundle\Tests\Notification;

use HighFive\MainBundle\Notification\NotificationManager;
use HighFive\MainBundle\Notification\EmailHandler;

class EmailHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $type
     * @param mixed  $data
     * @param string $template
     * @param array  $parameters
     *
     * @dataProvider provideSupportedTypes
     */
    public function testSupportedTypes($type, $data, $template, array $parameters)
    {
        $recipient = $this->getMock('HighFive\MainBundle\Entity\User');
        $recipient->expects($this->any())
            ->method('getEmail')
            ->will($this->returnValue('chuck@norris.un'));

        $mailer = $this->getMock('HighFive\MainBundle\Mailer\MailerInterface');
        $mailer->expects($this->once())
            ->method('send')
            ->with('chuck@norris.un', $template, $parameters, 'notifications@example.org');

        $handler = new EmailHandler($mailer, 'notifications@example.org');

        $handler->send($type, $recipient, $data);
    }

    public function provideSupportedTypes()
    {
        $data = new \stdClass();

        return array(
            'reply' => array(NotificationManager::TYPE_REPLY, $data, 'MainBundle:Mail/Notification:reply.html.twig', array('message' => $data)),
            'receive points' => array(NotificationManager::TYPE_RECEIVE_POINTS, $data, 'MainBundle:Mail/Notification:recognition.html.twig', array('recognition' => $data)),
        );
    }

    public function testUnsupportedType()
    {
        $mailer = $this->getMock('HighFive\MainBundle\Mailer\MailerInterface');
        $mailer->expects($this->never())
            ->method('send');

        $handler = new EmailHandler($mailer, 'foo');

        $handler->send('unsupported', $this->getMock('HighFive\MainBundle\Entity\User'), new \stdClass());
    }
}
