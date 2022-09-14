<?php

namespace App\Controller;

use App\Form\WordCounterFormType;
use App\Service\WordStatisticService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WordCounterController extends AbstractController
{
    #[Route('/', name: 'word_counter', methods: [Request::METHOD_GET])]
    public function index(WordStatisticService $service): Response
    {
        $form = $this->createForm(WordCounterFormType::class);

        $result = $service->madeStatistics('');

        return $this->render('base.html.twig', [
            'form' => $form->createView(),
            'result' => $result
        ]);
    }

    #[Route('/', name: 'create_word_counter', methods: [Request::METHOD_POST])]
    public function create(Request $request, WordStatisticService $service): Response
    {
        $form = $this->createForm(WordCounterFormType::class);
        $form->handleRequest($request);
        $result = [];

        if ($form->isSubmitted()) {
            $data = $form->getData();
            $result = $service->madeStatistics($data['text']);
        }

        return $this->render('base.html.twig', [
            'form' => $form->createView(),
            'result' => $result
        ]);
    }
}