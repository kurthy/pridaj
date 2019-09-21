<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class OsymfoniiController extends AbstractController
{
    /**
     * @Route("/osymfonii", name="osymfonii")
     */
    public function index()
    {
        return $this->render('osymfonii/index.html.twig', [
            'controller_name' => 'OsymfoniiController',
        ]);
    }
}
