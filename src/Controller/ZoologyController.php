<?php
// src/Controller/ZoologyController.php
namespace App\Controller;

use App\Form\ZoologyType;
use App\Entity\Zoology;
use App\Entity\Lkppristupnost;
use App\Entity\LkpzoospeciesAves;
use App\Repository\ZoologyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/zoology")
 */
class ZoologyController extends AbstractController
{

  /**
   * @Route("/", name="zoology_index", methods={"GET"})
   *
   */
  public function index(ZoologyRepository $zoologyRepository): Response
  {
    return $this->render('zoology/index.html.twig', [
      'zoology' => $zoologyRepository->findAll(),
    ]);

  }
    /**
     * @Route("/{id}", name="zoology_show", requirements={"id":"\d+"}, methods={"GET"})
     */
    public function show(Zoology $zoozaznam): Response
    {
        return $this->render('zoology/show.html.twig', [
            'zoozaznam' => $zoozaznam,
        ]);
    }

  /**
   * @Route("/new", name="newzoology")
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
    /**
     * @Route("/{id}/edit", name="zoology_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Zoology $zoozaznam): Response
    {
        $form = $this->createForm(ZoologyType::class, $zoozaznam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('zoology_index');
        }

        return $this->render('zoology/edit.html.twig', [
            'zoozaznam' => $zoozaznam,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}", name="zoology_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Zoology $zoozaznam): Response
    {
        if ($this->isCsrfTokenValid('delete'.$zoozaznam->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($zoozaznam);
            $entityManager->flush();
        }

        return $this->redirectToRoute('zoology_index');
    }
}

?>
