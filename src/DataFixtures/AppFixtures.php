<?php

namespace App\DataFixtures;

use App\Entity\ContractType;
use App\Entity\Media;
use App\Entity\Sector;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\Date;

class AppFixtures extends Fixture
{

    public const NB_USER = 5;
    public const NB_TASK = 25;
    public const NB_SECTOR = 15;
    public const CONTRACT_TYPE = ['CDD', 'CDI', 'INTERIM', 'STAGE'];

    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Add Contract BDD
        $contracts = [];
        for ($i = 0; $i < count(self::CONTRACT_TYPE); $i++) {
            $contractType = new ContractType();
            $contractType->setName(self::CONTRACT_TYPE[$i]);
            $contracts[] = $contractType;
            $manager->persist($contractType);
        }

        // Add Task BDD
        $tasks = [];
        for ($i = 0; $i < self::NB_TASK; $i++) {
            $task = new Task();
            $task
                ->setName($faker->realText())
                ->setDateCreated($faker->dateTime())
                ->setIsCompleted($faker->boolean(70));
            $tasks[] = $task;
            $manager->persist($task);
        }

        // Add Sector BDD
        $sectors = [];
        for ($i = 0; $i < self::NB_SECTOR; $i++) {
            $sector = new Sector();
            $sector->setName($faker->jobTitle());
            $sectors[] = $sector;
            $manager->persist($sector);
        }

        //Add Admin
        $admin = new User();
        $plaintextPasswordAdmin = 'admin';
        $hashedPassword = $this->userPasswordHasher->hashPassword(
            $admin,
            $plaintextPasswordAdmin
        );
        $admin
            ->setEmail('rh@hb.com')
            ->setFirstname($faker->firstName())
            ->setLastname($faker->lastName())
            ->setPassword($hashedPassword)
            ->setRoles(["ROLE_ADMIN"])
            ->setDateCreated($faker->dateTime());
        $manager->persist($admin);

        //Add User
        $users = [];
        $plaintextPassword = 'user';
        for ($i = 0; $i < self::NB_USER; $i++) {
            $user = new User();

            $user
                ->setEmail($faker->email())
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setRoles(["ROLE_USER"])
                ->setDateCreated($faker->dateTime())
                ->setContractType($faker->randomElement($contracts))
                ->setSector($faker->randomElement($sectors))
                ->addTask($faker->randomElement($tasks));


            $hashedPassword = $this->userPasswordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);
            $manager->persist($user);
        }

        // Add Image BDD
        for ($i = 0; $i < self::NB_USER; $i++) {
            $image = new Media();
            $image
                ->setFilename($faker->imageUrl())
                ->setUser(($faker->randomElement($users)));
            $manager->persist($image);
        }

        $manager->flush();
    }
}
