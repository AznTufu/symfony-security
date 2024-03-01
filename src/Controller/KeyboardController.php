<?php

namespace App\Controller;

use App\Entity\Keyboard;
use App\Form\KeyboardType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class KeyboardController extends AbstractController
{
    #[Route('/keyboard', name: 'app_keyboard')]
    public function index(EntityManagerInterface $em, Request $r, SluggerInterface $slugger): Response
    {
        $un_clavier = new Keyboard();
        $form = $this->createForm(KeyboardType::class, $un_clavier);

        $form->handleRequest($r);

        if($form->isSubmitted() && $form->isValid()){
            $slug = $slugger->slug($un_clavier->getName()) . '-' . uniqid();
            $un_clavier->setSlug($slug);
            $em->persist($un_clavier);
            $em->flush();
        }

        $keyboards = $em->getRepository(Keyboard::class)->findAll();

        return $this->render('keyboard/index.html.twig', [
            'keyboards' => $keyboards,
            'form' => $form->createView()
        ]);
    }
    
    #[Route('/keyboard/delete/{id}', name: 'app_keyboard_delete')]
    public function delete(Request $r, EntityManagerInterface $em, Keyboard $keyboard) {
        if($this->isCsrfTokenValid('delete'.$keyboard->getId(), $r->request->get('csrf'))){
            $em->remove($keyboard);
            $em->flush();
        }

        return $this->redirectToRoute('app_keyboard');
    }

    #[Route('/keyboard/{slug}', name: 'app_keyboard_show')]
    public function show(Keyboard $keyboard): Response
    {
        return $this->render('keyboard/show.html.twig', [
            'keyboard' => $keyboard
        ]);
    }

    #[Route('/keyboard/edit/{slug}', name: 'app_keyboard_edit')]
    public function edit(Request $request, Keyboard $keyboard, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(KeyboardType::class, $keyboard);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();
    
            return $this->redirectToRoute('app_keyboard');
        }

        return $this->render('keyboard/edit.html.twig', [
            'keyboard' => $keyboard,
            'form' => $form->createView()
        ]);
    }
}
