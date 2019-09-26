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
use Symfony\Contracts\Translation\TranslatorInterface;

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
  public function new(EntityManagerInterface $em, Request $request, TranslatorInterface $translator)
  {
    //založ nový záznam
    $zoology = new Zoology();
    $form = $this->createForm(ZoologyType::class, $zoology);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
	   $zoology = $form->getData();	
	   $zoology->setSfGuardUserId($this->getUser()->getSfGuardUserId());
	   $zoology->setZoologyExport('N');
  //         dd($zoology);
	   $em->persist($zoology);
           $em->flush();
          
           $this->addFlash(
            'notice',
            $translator->trans('zaznam.zalozeny',[ 'zaznam' => $zoology->getId()] )
          );
            return $this->redirectToRoute('zoology_show', [ 'id' => $zoology->getId() ]);
    }


    return $this->render('zoology/new.html.twig', [
            'form' => $form->createView(),
        ]);
  }
    /**
     * @Route("/{id}/edit", name="zoology_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function edit(Request $request, Zoology $zoozaznam, TranslatorInterface $translator, EntityManagerInterface $em): Response
    {
        //upraviť sa nesmie záznam, ak bol exportovaný do AVES-u!
        if($zoozaznam->getZoologyExport() <> 'E'): 
          //zabrániť exportu do AVES počas editácie zmenou zoology_export na Z
          $oPomZoo = $this->getDoctrine()->getRepository(Zoology::class)->find($zoozaznam->getId());
          $oPomZoo->setZoologyExport('Z');
          $em->persist($oPomZoo);
          $em->flush();

          $form = $this->createForm(ZoologyType::class, $zoozaznam);
          $form->handleRequest($request);

          if ($form->isSubmitted() && $form->isValid()) {
              $this->getDoctrine()->getManager()->flush();

             //zabrániť exportu do AVES počas editácie zmenou zoology_export na Z, teraz nazad na N
          
             $oPomZoo2 = $this->getDoctrine()->getRepository(Zoology::class)->find($zoozaznam->getId());
          $oPomZoo2->setZoologyExport('N');
          $em->persist($oPomZoo2);
          $em->flush();

              $this->addFlash(
              'notice',
              $translator->trans('zaznam.upraveny',[ 'zaznam' => $zoozaznam->getId()] )
            );
              return $this->redirectToRoute('zoology_show', [ 'id' => $zoozaznam->getId() ]);

          }

          return $this->render('zoology/edit.html.twig', [
              'zoozaznam' => $zoozaznam,
              'form' => $form->createView(),
          ]);

        else:

            $this->addFlash(
            'notice',
            $translator->trans('zaznam.exportovany.do.avesu',[ 'zaznam' => $zoozaznam->getId()] )
          );
            return $this->redirectToRoute('zoology_show', [ 'id' => $zoozaznam->getId() ]);

        endif;
    }
    /**
     * @Route("/{id}", name="zoology_delete", methods={"DELETE"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function delete(Request $request, Zoology $zoozaznam, TranslatorInterface $translator): Response
    {
        if ($this->isCsrfTokenValid('delete'.$zoozaznam->getId(), $request->request->get('_token'))           && $zoozaznam->getSfGuardUserId() == $this->getUser()->getSfGuardUserId() ) {
            $iPomID = $zoozaznam->getId();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($zoozaznam);
            $entityManager->flush();

           $this->addFlash(
            'notice',
            $translator->trans('zaznam.zmazany',[ 'zaznam' => $iPomID] )
          );
        }
        else
        {
           $this->addFlash(
            'notice',
            $translator->trans('zaznam.nezmazany',[ 'zaznam' => $zoozaznam->getId()] )
          );
        }

        return $this->redirectToRoute('zoology_index');
    }

  /**
   * @Route("/{id}/vzor", name="zoology_vzor")
   * @Security("is_granted('ROLE_USER')")
   */
  public function newfromstamp(EntityManagerInterface $em, Request $request,  Zoology $zoovzor, $pridavanieDruhu = "nie", TranslatorInterface $translator)
  {

    $zoology = new Zoology();
    $zoology->setZoologyDate($zoovzor->getZoologyDate());
    $zoology->setZoologyLongitud($zoovzor->getZoologyLongitud());
    $zoology->setZoologyLatitud($zoovzor->getZoologyLatitud());
    $zoology->setZoologyLocality($zoovzor->getZoologyLocality());
    $zoology->setZoologyDescription($zoovzor->getZoologyDescription());
    $zoology->setZoologyExport('N');

//   $zoology->setZoologyAccessibility($zoovzor->getZoologyAccessibility());
//   $zoology->setCount($zoovzor->getCount());

    $lPom = false;
    if($request->query->get('pridavanieDruhu') == "ano") $lPom = true;

    //tretí argument je parameter pre formulár, aby nezobrazoval položky, ktoré túto situáciu
    //rozlišujú, ide o lokalitné položky
    $form = $this->createForm(ZoologyType::class, $zoology, [
      'disable_field' => $lPom
      ]);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
	   $zoology = $form->getData();	
	   $zoology->setSfGuardUserId($this->getUser()->getSfGuardUserId());
	   $em->persist($zoology);
           $em->flush();

           //rozlíšiť dve situácie, zaznam.zalozny.zo.vzoru a zaznam.zalozeny.pridaj.druh
           $this->addFlash(
            'notice',
            $lPom == true ? 
              $translator->trans('zaznam.zalozeny.pridaj.druh',[ 'zaznam' => $zoology->getId()] )
            :
              $translator->trans('zaznam.zalozeny.zo.vzoru',[ 'zaznam' => $zoology->getId()] )
          );
            return $this->redirectToRoute('zoology_show', [ 'id' => $zoology->getId() ]);
    }


    return $this->render('zoology/vzor.html.twig', [
            'form' => $form->createView(),
        ]);
  }
}

?>
