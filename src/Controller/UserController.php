<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @Route("/user/admin")
 */
class UserController extends AbstractController
{
  private $passwordEncoder;

   public function __construct(UserPasswordEncoderInterface $passwordEncoder)
   {
         $this->passwordEncoder = $passwordEncoder;
   }

    /**
     * @Route("/", name="user_index", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->najdiUzivatelov(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function show(Int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $uz  = $entityManager->getRepository(User::class)->find($id);
        $aUz = $this->getDoctrine()
            ->getRepository(User::class)
            ->najdiUzivatela($id);

        if (!$uz) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

       if($uz->getPassword() == ''):
        $uz->setPassword($this->passwordEncoder->encodePassword($uz,$aUz[0]['username']));
        $entityManager->persist($uz);
        $entityManager->flush();

        

       endif;

        return $this->render('user/show.html.twig', [
            'uz' => $uz,
        ]);
    }
}
