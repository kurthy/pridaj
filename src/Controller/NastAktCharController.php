<?php
// src/Controller/NastAktCharController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\Response;


class NastAktCharController extends AbstractController
{
    /**
     * @Route("/nastaktchar")
     */
    public function nastaktchar(KernelInterface $kernel)
    {
        $application = new Application($kernel);
	$application->setAutoExit(false);

	$input = new ArrayInput([
            'command' => 'cas:nastavAvesCharakteristiku',
        ]);

        $output = new NullOutput(); 
        $application->run($input, $output);

	return new Response("");
    }

}

?>
