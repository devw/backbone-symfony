<?php

namespace HighFive\MainBundle\DataFixtures\ORM;

use HighFive\MainBundle\Entity\Organization;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Workspace Fixtures
 */
class OrganizationFixtures extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $organization = new Organization();
        $organization->setName($faker->company);
        $manager->persist($organization);

        $organization2 = new Organization();
        $organization2->setName($faker->company);
        $manager->persist($organization2);

        $this->addReference('organization', $organization);
        $this->addReference('organization2', $organization2);

        $manager->flush();
    }
}
