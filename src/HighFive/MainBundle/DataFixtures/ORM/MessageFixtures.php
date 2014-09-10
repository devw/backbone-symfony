<?php

namespace HighFive\MainBundle\DataFixtures\ORM;

use HighFive\MainBundle\Entity\Message;
use HighFive\MainBundle\Entity\Organization;
use HighFive\MainBundle\Entity\Recognition;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class MessageFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $this->createMessages($manager, $faker, $this->getReference('organization'), array('admin', 'normal', '0', '1', '2'));
        $this->createMessages($manager, $faker, $this->getReference('organization2'), range(5, 9));

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
        return array(__NAMESPACE__ . '\OrganizationFixtures', __NAMESPACE__ . '\UserFixtures');
    }

    private function createMessages(ObjectManager $manager, Generator $faker, Organization $organization, array $availableUsers)
    {
        $messages = array(null);
        for ($i = 0; $i < 20; $i++) {
            $sender = $faker->randomElement($availableUsers);
            $availableRecipients = array_values(array_diff($availableUsers, array($sender)));

            $message = new Message();
            $message->setOrganization($organization)
                ->setSender($this->getReference(sprintf('user-%s', $sender)))
                ->setRecipient($this->getReference(sprintf('user-%s', $faker->randomElement($availableRecipients))))
                ->setCreatedAt($faker->dateTimeThisYear)
                ->setParent($faker->randomElement($messages))
                ->setMessage($faker->text);

            if ($faker->boolean) {
                $recognition = new Recognition();
                $recognition->setSender($message->getSender())
                    ->setRecipient($message->getRecipient())
                    ->setCreatedAt($message->getCreatedAt())
                    ->setPoints($faker->randomElement(range(10, 50, 10)));
                $message->setRecognition($recognition);
                $manager->persist($recognition);

                $recognition->getRecipient()->addPoints($recognition->getPoints());
                $recognition->getSender()->addGivenPoints($recognition->getPoints());
            }

            if (null === $message->getParent()) {
                $messages[] = $message;
            } else {
                $messages[] = null;
            }

            $manager->persist($message);
        }
    }
}
