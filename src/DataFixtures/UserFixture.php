<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
	protected $encoder;
	public function __construct(UserPasswordEncoderInterface $passwordEncoder)
	{
		$this->encoder = $passwordEncoder;
	}

    public function load(ObjectManager $manager)
    {
		$user = new User();
		$user->setName('Super Admin');
		$user->setEmail('admin@admin.com');
		$user->setPassword(
			$this->encoder->encodePassword(
				$user,
				'123456'
			)
		);
		$user->setProfilePhoto('https://gravatar.com/avatar/1c8e8a6e8d1fe52b782b280909abeb38?s=400&d=robohash&r=x');
		$user->setType('admin');
		$user->setRoles('ROLE_ADMIN');
		$user->setStatus(1);
		$user->setCreatedBy(0);
		$user->setCreatedAt(new \DateTime('now'));

        $manager->persist($user);
        $manager->flush();
    }
}
