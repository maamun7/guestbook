<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Form\EditUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\UserManager;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/user")
 */
class UserController extends AbstractController
{
	protected $userManager;

	public function __construct(UserManager $userManager)
	{
		$this->userManager = $userManager;
	}

	/**
	 * @Route("/", name="user_index", methods={"GET"})
	 */
	public function index(): Response
	{
		return $this->render('admin/user/index.html.twig', [
			'users' => $this->userManager->getList()
		]);
	}

	/**
	 * @Route("/new", name="user_new", methods={"GET","POST"})
	 */
	public function new(Request $request, ValidatorInterface $validator): Response
	{
		$user = new User();   

		$form = $this->createForm(UserType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid() ) {

			$errors = $validator->validate($user);

			if (count($errors) > 0) {
				$this->addFlash('warning', 'Provided data did not pass the validation !');

				return $this->redirectToRoute('user_new');				
			}		

			try {
				$this->userManager->createNewUser($request, $user, $this->getUser());			
				$this->addFlash('success', 'User has been created successfully !');
			} catch (\Exception $e) {
				$this->addFlash('error', 'Your last submission was not saved: ' . $e->getMessage());
				// @TODO : Saving failed, please take necessary steps 
			}
			
			return $this->redirectToRoute('user_index');
		}

		return $this->render('admin/user/new.html.twig', [
			'user' => $user,
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/{id}", name="user_show", methods={"GET"})
	 */
	public function show($id): Response
	{
		$user = $this->userManager->getById($id);

		if (empty($user) > 0) {
			$this->addFlash('warning', 'No data available !');

			return $this->redirectToRoute('user_index');				
		}

		return $this->render('admin/user/show.html.twig', [
			'user' => $user
		]);
	}

	/**
	 * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
	 */
	public function edit(Request $request, User $user, ValidatorInterface $validator): Response
	{
		if (empty($user) > 0) {
			$this->addFlash('warning', 'No data available !');

			return $this->redirectToRoute('user_index');
		}

		$form = $this->createForm(EditUserType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			
			$errors = $validator->validate($user);

			if (count($errors) > 0) {
				$this->addFlash('warning', 'Provided data did not pass the validation !');

				return $this->redirectToRoute('user_edit');
			}				

			try {
				$this->userManager->updateUser($request, $user, $this->getUser());
				
				$this->addFlash('success', 'User has been updated successfully !');
			} catch (\Exception $e) {
				$this->addFlash('error', 'Your last submission was not saved: ' . $e->getMessage());
				// @TODO : Saving failed, please take necessary steps 
			}

			return $this->redirectToRoute('user_index');
		}

		return $this->render('admin/user/edit.html.twig', [
			'user' => $user,
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/{id}", name="user_delete", methods={"DELETE"})
	 */
	public function delete(Request $request, User $user): Response
	{
		if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->remove($user);
			$entityManager->flush();
		}

		return $this->redirectToRoute('user_index');
	}
}
