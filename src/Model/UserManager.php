<?php
namespace App\Model;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager {

	/** @var UserRepository */
	protected $repository;

	/** @var EntityManagerInterface */
	protected $entityManager;

	/** @var UserPasswordEncoderInterface */
	protected $encoder;

	public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
	{
		$this->entityManager = $entityManager;
		$this->repository = $this->entityManager->getRepository(User::class);
		$this->encoder = $passwordEncoder;
	}

	public function getList()
	{
		return $this->repository->findAll();
	}

	public function getById(int $id)
	{
		return $this->repository->find($id);
	}

	public function createNewUser(Request $request, User $user, User $sessionUser) :? bool
	{		
		$formData = $request->request->get('user');

		$role = $formData['type'] == 'admin' ? 'ROLE_ADMIN' : 'ROLE_USER';

		$user->setRoles($role);
		$user->setCreatedBy($sessionUser->getId());
		$user->setUpdatedBy(0);
		$user->setDeletedBy(0);
		$user->setCreatedAt(new \DateTime('now'));
		$user->setUpdatedAt(null);
		$user->setDeletedAt(null);
		$user->setPassword(
			$this->encoder->encodePassword(
				$user,
				$formData['password']
			)
		);

		$this->entityManager->persist($user);
		$this->entityManager->flush();

		return true;
	}

	public function updateUser(Request $request, User $user, User $sessionUser) :? bool
	{		
		$formData = $request->request->get('edit_user');		

		$role = $formData['type'] == 'admin' ? 'ROLE_ADMIN' : 'ROLE_USER';

		$user->setRoles($role);
		$user->setUpdatedBy($sessionUser->getId());
		$user->setUpdatedAt(new \DateTime('now'));

		$this->entityManager->persist($user);
		$this->entityManager->flush();

		return true;
	}
}