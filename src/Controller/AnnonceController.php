<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Annonce;
use App\Form\AnnonceType;

class AnnonceController extends AbstractController
{
    #[Route('/annonces/add', name: 'app_create_annonce')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('annonce/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/annonces/{id}', name: 'app_annonce')]
    public function annonce(EntityManagerInterface $entityManager, $id): Response
    {
        $annonce = $entityManager->getRepository(Annonce::class)->findOneById($id);

        if(!$annonce) {
            return $this->redirectToRoute('home');
        }

        return $this->render('annonce/annonce.html.twig', [
            'annonce' => $annonce
        ]);
    }

    #[Route('/annonces/update/{id}', name: 'app_update_annonce')]
    public function update(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $annonce = $entityManager->getRepository(Annonce::class)->findOneById($id);

        if(!$annonce) {
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('annonce/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/annonces/delete/{id}', name: 'app_delete_annonce')]
    public function delete(EntityManagerInterface $entityManager, $id): Response
    {
        $annonce = $entityManager->getRepository(Annonce::class)->findOneById($id);

        $entityManager->remove($annonce);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }
}
