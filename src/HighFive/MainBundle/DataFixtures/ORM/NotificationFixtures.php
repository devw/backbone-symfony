<?php

namespace HighFive\MainBundle\DataFixtures\ORM;

use HighFive\MainBundle\Entity\Notification;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class NotificationFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $this->createNotifications($manager, $faker, array('admin', 'normal', '0', '1', '2'));
        $this->createNotifications($manager, $faker, range(5, 9));

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return array(__NAMESPACE__ . '\UserFixtures');
    }

    private function createNotifications(ObjectManager $manager, Generator $faker, array $availableUsers)
    {
        $messages = array(Notification::REPLY, Notification::RECEIVE_POINTS);

        for ($i = 0; $i < 40; $i++) {
            $recipient = $faker->randomElement($availableUsers);
            $availableSenders = array_values(array_diff($availableUsers, array($recipient)));

            $notification = new Notification();
            $notification->setMessage($faker->randomElement($messages))
                ->setRecipient($this->getReference(sprintf('user-%s', $recipient)))
                ->setRead($faker->boolean(30))
                ->setParameters(array('first_name' => $faker->firstName, 'last_name' => $faker->lastName, 'points' => $faker->randomElement(range(0, 50))));

            if ($faker->boolean) {
                $notification->setSender($this->getReference(sprintf('user-%s', $faker->randomElement($availableSenders))));
            }

            $manager->persist($notification);
        }
    }
}
