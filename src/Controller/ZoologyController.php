<?php
// src/Controller/ZoologyController.php
namespace App\Controller;

use App\Form\ZoologyType;
use App\Entity\Zoology;
use App\Entity\Lkppristupnost;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;

class ZoologyController extends AbstractController
{
  /**
   * @Route("/zoology/new", name="newzoology")
   *
   */
  public function new(EntityManagerInterface $em, Request $request)
  {
    //založ nový záznam
    $zoology = new Zoology();
    $form = $this->createForm(ZoologyType::class, $zoology);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
	   $zoology = $form->getData();	
	   $zoology->setSfGuardUserId($this->getUser()->getSfGuardUserId());
  //         dd($zoology);
	   $em->persist($zoology);
           $em->flush();
          
           return $this->redirectToRoute('homepage');
    }


    return $this->render('zoology/new.html.twig', [
            'form' => $form->createView(),
        ]);
  }
}

?>
