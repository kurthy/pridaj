<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PrehladController extends AbstractController
{
    /**
     * @Route("/prehlad", name="homepage")
     */
    public function index()
    {
        return $this->render('prehlad/index.html.twig', [
            'controller_name' => 'PrehladController',
        ]);
    }
}
