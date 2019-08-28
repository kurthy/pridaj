<?php

namespace App\Controller;

use App\Entity\LkpzoospeciesAves;
use App\Form\LkpzoospeciesAvesType;
use App\Repository\LkpzoospeciesAvesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/lkpzoospecies/aves")
 */
class LkpzoospeciesAvesController extends AbstractController
{
    /**
     * @Route("/", name="lkpzoospecies_aves_index", methods={"GET"})
     */
    public function index(LkpzoospeciesAvesRepository $lkpzoospeciesAvesRepository): Response
    {
        return $this->render('lkpzoospecies_aves/index.html.twig', [
            'lkpzoospecies_aves' => $lkpzoospeciesAvesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="lkpzoospecies_aves_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $lkpzoospeciesAfe = new LkpzoospeciesAves();
        $form = $this->createForm(LkpzoospeciesAvesType::class, $lkpzoospeciesAfe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lkpzoospeciesAfe);
            $entityManager->flush();

            return $this->redirectToRoute('lkpzoospecies_aves_index');
        }

        return $this->render('lkpzoospecies_aves/new.html.twig', [
            'lkpzoospecies_afe' => $lkpzoospeciesAfe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="lkpzoospecies_aves_show", methods={"GET"})
     */
    public function show(LkpzoospeciesAves $lkpzoospeciesAfe): Response
    {
        return $this->render('lkpzoospecies_aves/show.html.twig', [
            'lkpzoospecies_afe' => $lkpzoospeciesAfe,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="lkpzoospecies_aves_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, LkpzoospeciesAves $lkpzoospeciesAfe): Response
    {
        $form = $this->createForm(LkpzoospeciesAvesType::class, $lkpzoospeciesAfe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('lkpzoospecies_aves_index');
        }

        return $this->render('lkpzoospecies_aves/edit.html.twig', [
            'lkpzoospecies_afe' => $lkpzoospeciesAfe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="lkpzoospecies_aves_delete", methods={"DELETE"})
     */
    public function delete(Request $request, LkpzoospeciesAves $lkpzoospeciesAfe): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lkpzoospeciesAfe->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($lkpzoospeciesAfe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('lkpzoospecies_aves_index');
    }
}
