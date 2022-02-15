<?php

namespace App\Controller;

use App\Entity\Step;
use App\Form\StepType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/step')]
class StepController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    #[Route('/{id}', name: 'step_show', methods: ['GET'])]
    public function show(Step $step): Response
    {
        return $this->render('step/show.html.twig', ['step' => $step]);
    }

    #[Route('/{id}/edit', name: 'step_edit', methods: ['GET, POST'])]
    public function edit(Request $request, Step $step): Response
    {
        $form = $this->createForm(StepType::class, $step);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('step_show', ['id' => $step->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('step/edit.html.twig', [
            'form' => $form,
            'step' => $step,
        ]);
    }

    #[Route('/{id}', name: 'step_delete', methods: ['POST'])]
    public function delete(Request $request, Step $step): Response
    {
        $achievement = $step->getAchievement();

        if ($this->isCsrfTokenValid('delete'.$step->getId(), $request->request->get('_token'))) {
            $this->em->remove($step);
            $this->em->flush();
        }

        return $this->redirectToRoute('achievement_steps', ['id' => $achievement->getId()], Response::HTTP_SEE_OTHER);
    }
}
