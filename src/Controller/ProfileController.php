<?php

namespace App\Controller;

use App\DTO\SearchDto;
use App\Entity\Utilisateur\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProfileType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $searchDto ??= new SearchDto();

        /** @var Utilisateur $user */
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setName($form->get('name')->getData());
            $user->setEmail($form->get('email')->getData());

            $currentPassword = $form->get('currentPassword')->getData();
            if (!empty($currentPassword) && !empty($form->get('newPassword')->getData())) {
                if ($passwordHasher->isPasswordValid($user, $currentPassword)) {
                    $newPassword = $form->get('newPassword')->getData();
                    if ($newPassword === $form->get('confirmPassword')->getData()) {
                        $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
                    } else {
                        $this->addFlash('error', 'New passwords do not match.');
                        return $this->redirectToRoute('app_profile');
                    }
                } else {
                    $this->addFlash('error', 'Current password is incorrect');
                    return $this->redirectToRoute('app_profile');
                }
            }

            $entityManager->flush();
            $this->addFlash('success', 'Profile updated successfully');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
            'searchDto' => $searchDto,
        ]);
    }
}
