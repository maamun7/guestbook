<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class DashboardController extends AbstractController
{
	/**
	 * @Route("/", methods="GET", name="admin_index")
	 */
	public function index(): Response
	{
		return $this->render('admin/dashboard/index.html.twig', [
			'controller_name' => 'DashboardController',
		]);
	}
}
