<?php

namespace App\Controller;

use App\Entity\Impsubory;
use App\Form\ImpsuboryType;
use App\Entity\Zoology;
use App\Entity\Lkppristupnost;
use App\Entity\LkpzoospeciesAves;
use App\Entity\Lkpzoochar;
use App\Repository\LkppristupnostRepository;
use App\Repository\LkpzoospeciesAvesRepository;
use App\Repository\ImpsuboryRepository;
use App\Repository\LkpzoocharRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;
use App\Utilities\PrvyExcelFilter;
use App\Utilities\ExcelDatum2Unix;
use App\Utilities\AvesExcelTools;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Route("/impsubory")
 */
class ImpsuboryController extends AbstractController
{



    /**
     * @Route("/", name="impsubory_index", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function index(ImpsuboryRepository $impsuboryRepository): Response
    {
        return $this->render('impsubory/index.html.twig', [
            'impsubories' => $impsuboryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="impsubory_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function new(Request $request, ExcelDatum2unix $ExcelDatum2Unix, EntityManagerInterface $em, LkppristupnostRepository $lkppristupnostRepository, LkpzoospeciesAvesRepository $lkpzoospeciesAvesRepository, LkpzoocharRepository $lkpzoocharRepository, ValidatorInterface $validator, AvesExcelTools $avesexceltools): Response
    {
        $impsubory = new Impsubory();
        $iPomNacitajVarkuRiadkov = 50;
        $form = $this->createForm(ImpsuboryType::class, $impsubory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $excelSubor    = $form['subor']->getData();

            if ($excelSubor) {
                $originalFilename = pathinfo($excelSubor->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$excelSubor->guessExtension();

                //validácia podľa prvých dvoch riadkov
                //pri chyba sa vráti do formulára a chybu popíše flash hláškou
                $cPomOverenie2r = $avesexceltools->overdvariadky($excelSubor);
                if($cPomOverenie2r !== 'OK'):

                   $this->addFlash(
                    'danger',
                    $cPomOverenie2r
                  );
                  return $this->redirectToRoute('impsubory_index');
                else:
                  $aPomPlatnyZdroj = true;
                endif;

                $inputFileType = IOFactory::identify($excelSubor);
                $readerkompl   = IOFactory::createReader($inputFileType); 

                $readerkompl->setReadDataOnly(true); 
                $spreadsheetKompl = $readerkompl->load($excelSubor);

                $worksheetKompl  = $spreadsheetKompl->getActiveSheet();
                $highestRow = $worksheetKompl->getHighestRow(); // e.g. 10

                 if($highestRow < $iPomNacitajVarkuRiadkov && $aPomPlatnyZdroj):
                    $sheetDataKompl   = $spreadsheetKompl->getActiveSheet()->toArray(null, true, true, true);

                    $cPomImport       = $avesexceltools->importdata($excelSubor); //vráti multidimens array
                    $cPomImportString = json_encode($cPomImport,JSON_UNESCAPED_UNICODE);
                    $this->addFlash(
                      'info',
                     $cPomImportString
                    );

                 endif; 
                
                // Move the file to the directory where brochures are stored
 /*               try {
                    $excelSubor->move(
                        $this->getParameter('excelfiles_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
*/
                // updates the 'excelFilename' property to store the xls file name
                // instead of its contents
                $impsubory->setImpsubor($newFilename);
                $impsubory->setSfGuardUserId($this->getUser()->getSfGuardUserId());
                $impsubory->setCreated(new \DateTime());
                if($highestRow < $iPomNacitajVarkuRiadkov && $aPomPlatnyZdroj):
                  $impsubory->setNotice('Načítané dáta, načítal riadkov: '.$highestRow);
                else:
                  $impsubory->setNotice('Zatiaľ načítaných iba '.$iPomNacitajVarkuRiadkov.' riadkov, ostatné pomocou akcie. Excel má riadkov: '.$highestRow);
                endif;
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($impsubory);
            $entityManager->flush();

            return $this->redirectToRoute('impsubory_index');
        }

        return $this->render('impsubory/new.html.twig', [
            'impsubory' => $impsubory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/download/file", name="download_file")
     **/
     public function downloadFile()
    {
      $xlsPath = 'downloads/pridajAves.xls';
      return $this->file($xlsPath);
    }

    /**
     * @Route("/{id}", name="impsubory_show", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function show(Impsubory $impsubory): Response
    {
      /*
        return $this->render('impsubory/show.html.twig', [
            'impsubory' => $impsubory,
        ]);
      */
       $this->addFlash(
            'info',
            'prepáčte, funkcia zobraziť je v príprave'
        );
       return $this->redirectToRoute('impsubory_index');
    }

    /**
     * @Route("/{id}/edit", name="impsubory_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function edit(Request $request, Impsubory $impsubory, TranslatorInterface $translator): Response
    {
      /*
        $form = $this->createForm(ImpsuboryType::class, $impsubory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('impsubory_index');
        }

        return $this->render('impsubory/edit.html.twig', [
            'impsubory' => $impsubory,
            'form' => $form->createView(),
        ]);
       */
       $this->addFlash(
            'info',
            'prepáčte, funkcia "'.$translator->trans('load.data').'" je v príprave'
        );
       return $this->redirectToRoute('impsubory_index');

    }

    /**
     * @Route("/{id}", name="impsubory_delete", methods={"DELETE"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function delete(Request $request, Impsubory $impsubory): Response
    {
      /*
        if ($this->isCsrfTokenValid('delete'.$impsubory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($impsubory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('impsubory_index');
    */
       $this->addFlash(
            'info',
            'prepáčte, funkcia "odstrániť súbor" je v príprave'
        );
       return $this->redirectToRoute('impsubory_index');

    }


} 
