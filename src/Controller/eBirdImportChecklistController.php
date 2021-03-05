<?php
// src/Controller/eBirdImportChecklistController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Zoology;
use App\Repository\ZoologyRepository;

class eBirdImportChecklistController extends AbstractController
{
  /**
   * @Route("/importchecklist/{checklist}")
   */
  public function importchecklist($checklist = '', KernelInterface $kernel, ZoologyRepository $zoologyRepository)
  {
    $application = new Application($kernel);
    $application->setAutoExit(false);

    $input = new ArrayInput([
            'command' => 'eBird:importchecklist',
            // (optional) define the value of command arguments
            'checklist' => $checklist,
            // (optional) pass options to the command
            //'--message-limit' => '',
        ]);

        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();

        $checklistUzImportovany = $zoologyRepository->najdiChecklist($checklist);
        if(count($checklistUzImportovany) > 0):
          $content="Tento Checklist už bol importovaný! Koniec";
        else:
          $application->run($input, $output);
          // return the output, don't use if you used NullOutput()
          $content = $output->fetch();

        endif;


        // return new Response(""), if you used NullOutput()
        return new Response(nl2br($content));
  }
}
?>
