<?php
// src/Controller/eBirdImportController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class eBirdImportController extends AbstractController
{
  /**
   * @Route("/importden/{den}")
   */
  public function importden($den = '', KernelInterface $kernel)
  {
    $application = new Application($kernel);
    $application->setAutoExit(false);

    $input = new ArrayInput([
            'command' => 'eBird:importden',
            // (optional) define the value of command arguments
            'den' => $den,
            // (optional) pass options to the command
            //'--message-limit' => '',
        ]);

        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();
        $application->run($input, $output);


        // return the output, don't use if you used NullOutput()
        $content = $output->fetch();

        // return new Response(""), if you used NullOutput()
        return new Response(nl2br($content));
  }
}
?>
