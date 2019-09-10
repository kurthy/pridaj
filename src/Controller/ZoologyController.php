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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/zoology")
 */
class ZoologyController extends AbstractController
{

  /**
   * @Route("/", name="zoology_index", methods={"GET"})
   * @Security("is_granted('ROLE_USER')")
   */
  public function index(ZoologyRepository $zoologyRepository): Response
  {
    $user = $this->getUser();
    return $this->render('zoology/index.html.twig', [
      'zoology' => $zoologyRepository->findBy(
        ['sf_guard_user_id' => $user->getSfGuardUserId()] 
      ),
    ]);

  }
    /**
     * @Route("/{id}", name="zoology_show", requirements={"id":"\d+"}, methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function show(Zoology $zoozaznam): Response
    {
        return $this->render('zoology/show.html.twig', [
            'zoozaznam' => $zoozaznam,
        ]);
    }

  /**
   * @Route("/new", name="newzoology")
   * @Security("is_granted('ROLE_USER')")
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
          
           return $this->redirectToRoute('zoology_index');
    }


    return $this->render('zoology/new.html.twig', [
            'form' => $form->createView(),
        ]);
  }
    /**
     * @Route("/{id}/edit", name="zoology_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_USER')")
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
     * @Security("is_granted('ROLE_USER')")
     */
    public function delete(Request $request, Zoology $zoozaznam): Response
    {
        if ($this->isCsrfTokenValid('delete'.$zoozaznam->getId(), $request->request->get('_token'))           && $zoozaznam->getSfGuardUserId() == $this->getUser()->getSfGuardUserId() ) {
            $iPomID = $zoozaznam->getId();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($zoozaznam);
            $entityManager->flush();

           $this->addFlash(
            'notice',
            'Record nr. '.$iPomID().' was deleted!'
          );
        }
        else
        {
           $this->addFlash(
            'notice',
            'Record nr. '.$zoozaznam->getId().' was not deleted!'
          );
        }

        return $this->redirectToRoute('zoology_index');
    }

  /**
   * @Route("/{id}/vzor", name="zoology_vzor")
   * @Security("is_granted('ROLE_USER')")
   */
  public function newfromstamp(EntityManagerInterface $em, Request $request,  Zoology $zoovzor)
  {

    $zoology = new Zoology();
    $zoology->setZoologyDate($zoovzor->getZoologyDate());
    $zoology->setZoologyLongitud($zoovzor->getZoologyLongitud());
    $zoology->setZoologyLatitud($zoovzor->getZoologyLatitud());
    $zoology->setZoologyLocality($zoovzor->getZoologyLocality());
//    $zoology->setZoologyAccessibility($zoovzor->getZoologyAccessibility());
    $zoology->setZoologyDescription($zoovzor->getZoologyDescription());


    $form = $this->createForm(ZoologyType::class, $zoology);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
	   $zoology = $form->getData();	
	   $zoology->setSfGuardUserId($this->getUser()->getSfGuardUserId());
	   $em->persist($zoology);
           $em->flush();
          
           return $this->redirectToRoute('zoology_index');
    }


    return $this->render('zoology/vzor.html.twig', [
            'form' => $form->createView(),
        ]);
  }
}

?>
