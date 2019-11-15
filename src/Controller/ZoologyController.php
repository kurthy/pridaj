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

     //nacitame hodnotu namapovaneho checkboxu
     $aPomNepripDoAvesu = $form['nieHnedDoAvesu']->getData(); 

	   $zoology = $form->getData();	
	   $zoology->setSfGuardUserId($this->getUser()->getSfGuardUserId());

     //novému záznamu môže užívateľ zafajknúť že "nie je pripravený hneď do Avesu"
     //vtedy nastaví novému záznamu hodnotu exportu "Z", iba ak nie je hodnota Z
     //treba nastaviť exportu N - nový, neimportovaný a pripravený do Avesu
     //a aves ho aj stiahne, kým je Z, aves nesťahuje!
     if($aPomNepripDoAvesu) $zoology->setZoologyExport('Z');
     if($zoology->getZoologyExport() <> 'Z') $zoology->setZoologyExport('N');

  //         dd($zoology);
	   $em->persist($zoology);
           $em->flush();

           $cPomZazDetFlash =  $zoology->getId().' ('.$zoology->getZoologyLocality().', '.$zoology->getZoologyDate()->format('Y-m-d').', '.$zoology->getLkpzoospeciesId().')';

           $this->addFlash(
            'notice',
            $translator->trans('zaznam.zalozeny',[ 'zaznam' => $cPomZazDetFlash ] )
          );

           $nextAction = $form->get('saveAndAdd')->isClicked()
           ? 'zoology_vzor'
           : 'zoology_show';

            if ($nextAction == 'zoology_vzor'): 
              return $this->redirectToRoute($nextAction, [ 'id' => $zoology->getId(), 'pridavanieDruhu' => 'ano' ]);
            else:
              return $this->redirectToRoute($nextAction, [ 'id' => $zoology->getId() ]);
            endif;
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
          if($zoozaznam->getZoologyExport() <> 'Z') $oPomZoo->setZoologyExport('Z');
          $em->persist($oPomZoo);
          $em->flush();

          $form = $this->createForm(ZoologyType::class, $zoozaznam);
          $form->handleRequest($request);

          if ($form->isSubmitted() && $form->isValid()) {
              $this->getDoctrine()->getManager()->flush();

             //zabrániť exportu do AVES počas editácie zmenou zoology_export na Z, teraz nazad na N
          
             $oPomZoo2 = $this->getDoctrine()->getRepository(Zoology::class)->find($zoozaznam->getId());

     //nacitame hodnotu namapovaneho checkboxu
     $aPomNepripDoAvesu = $form['nieHnedDoAvesu']->getData(); 
     if($aPomNepripDoAvesu):
       $oPomZoo2->setZoologyExport('Z');
     else: 
       $oPomZoo2->setZoologyExport('N');
     endif;

          $em->persist($oPomZoo2);
          $em->flush();

          $cPomZazDetFlash =  $zoozaznam->getId().' ('.$zoozaznam->getZoologyLocality().', '.$zoozaznam->getZoologyDate()->format('Y-m-d').', '.$zoozaznam->getLkpzoospeciesId().')';

          $this->addFlash(
            'notice',
            $translator->trans('zaznam.upraveny',[ 'zaznam' => $cPomZazDetFlash ] )
          );
            return $this->redirectToRoute('zoology_show', [ 'id' => $zoozaznam->getId() ]);

          }

          return $this->render('zoology/edit.html.twig', [
              'zoozaznam' => $zoozaznam,
              'form' => $form->createView(),
          ]);

        else:

           $cPomZazDetFlash =  $zoozaznam->getId().' ('.$zoozaznam->getZoologyLocality().', '.$zoozaznam->getZoologyDate()->format('Y-m-d').', '.$zoozaznam->getLkpzoospeciesId().')';

            $this->addFlash(
            'notice',
            $translator->trans('zaznam.exportovany.do.avesu',[ 'zaznam' =>  $cPomZazDetFlash  ] )
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
  public function newfromstamp(EntityManagerInterface $em, Request $request, Zoology $zoovzor, $pridavanieDruhu = "nie", TranslatorInterface $translator)
  {

    $zoology = new Zoology();
    $zoology->setZoologyDate($zoovzor->getZoologyDate());
    $zoology->setZoologyLongitud($zoovzor->getZoologyLongitud());
    $zoology->setZoologyLatitud($zoovzor->getZoologyLatitud());
    $zoology->setZoologyLocality($zoovzor->getZoologyLocality());
    $zoology->setZoologyDescription($zoovzor->getZoologyDescription());
    if($zoovzor->getZoologyExport() <> 'Z'):
      $zoology->setZoologyExport('N');
    else:
      $zoology->setZoologyExport('Z');
    endif;

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

     $aPomNepripDoAvesu = $form['nieHnedDoAvesu']->getData(); 

	   $zoology = $form->getData();	
	   $zoology->setSfGuardUserId($this->getUser()->getSfGuardUserId());
     if($aPomNepripDoAvesu):
       $zoology->setZoologyExport('Z');
     else:
       $zoology->setZoologyExport('N');
     endif;

	   $em->persist($zoology);
           $em->flush();


           $cPomZazDetFlash =  $zoology->getId().' ('.$zoology->getZoologyLocality().', '.$zoology->getZoologyDate()->format('Y-m-d').', '.$zoology->getLkpzoospeciesId().')';
           //rozlíšiť dve situácie, zaznam.zalozny.zo.vzoru a zaznam.zalozeny.pridaj.druh
           $this->addFlash(
            'notice',
            $lPom == true ? 
              $translator->trans('zaznam.zalozeny.pridaj.druh',[ 'zaznam' => $cPomZazDetFlash ] )
            :
              $translator->trans('zaznam.zalozeny.zo.vzoru',[ 'zaznam' => $cPomZazDetFlash ] )
          );

           $nextAction = $form->get('saveAndAdd')->isClicked()
           ? 'zoology_vzor'
           : 'zoology_show';

            if ($nextAction == 'zoology_vzor'): 
              return $this->redirectToRoute($nextAction, [ 'id' => $zoology->getId(), 'pridavanieDruhu' => 'ano' ]);
            else:
              return $this->redirectToRoute($nextAction, [ 'id' => $zoology->getId() ]);
            endif;
            return $this->redirectToRoute('zoology_show', [ 'id' => $zoology->getId() ]);
    }


    return $this->render('zoology/vzor.html.twig', [
            'form' => $form->createView(),
        ]);
  }
 /**
   * @Route("/{id}/preaves", name="zoology_preaves")
   * @Security("is_granted('ROLE_USER')")
   */
  public function preaves(EntityManagerInterface $em, Request $request,  Zoology $zoozaznam, TranslatorInterface $translator)
  {
    if($zoozaznam->getZoologyExport() == 'I'): 
           $zoozaznam->setZoologyExport('N');
           $em->persist($zoozaznam);
           $em->flush($zoozaznam);
           $cPomZazDetFlash =  $zoozaznam->getId().' ('.$zoozaznam->getZoologyLocality().', '.$zoozaznam->getZoologyDate()->format('Y-m-d').', '.$zoozaznam->getLkpzoospeciesId().')';

           $this->addFlash(
            'success',
            $translator->trans('zaznam.upraveny',[ 'zaznam' => $cPomZazDetFlash ] )
          );
    endif;

      return $this->redirectToRoute('zoology_index');
  }
}

?>
