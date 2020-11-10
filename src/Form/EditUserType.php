<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class EditUserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{		
		$builder
			->add('name', TextType::class, [
				'required' => true,
			])
			->add('email', TextType::class, [
				'required' => true,
			])
			->add('password', PasswordType::class, [
				'required' => false,
				'disabled' => true,
				'data' => '*****'
			])
			->add('profile_photo')
			->add('type', ChoiceType::class, [
				'required' => true,
				'placeholder' => 'Select User Type',
				'choices'  => [
					'Admin'  => 'admin',
					'User' => 'user',
				]
			])->add('status', ChoiceType::class, [
				'required' => true,
				'choices'  => [
					'Active'    => 1,
					'Inactive'  => 0
				]
			]);
		;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => User::class,
		]);
	}
}
