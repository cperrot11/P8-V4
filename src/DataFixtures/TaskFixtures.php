<?php

namespace App\DataFixtures;

use App\Entity\Task;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($a=1; $a<20; $a++){
            $task = new Task();

            $content = '<p>'. join($faker->paragraphs(2), '</p><p>').'</p>';

            $rand_user=rand(1,12);
            if ($rand_user>9) {
                //anonymous user
                $user = null;
            }
            else {
                $user = $this->getReference('user_ref'.$rand_user);
            }
            $task->setTitle($faker->unique()->sentence(2));
            $task->setUser($user);
            $task->setContent($content);
            $task->setCreatedAt($faker->dateTimeBetween($startDate = '-60 days', $endDate = 'now', $timezone = null));
            $task->toggle($faker->boolean());

            $manager->persist($task);
        }

        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
