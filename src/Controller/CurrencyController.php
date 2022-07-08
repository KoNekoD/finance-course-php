<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Currency;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class CurrencyController extends AbstractController
{

    public function showCurrencies(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $currencies = $entityManager->getRepository(Currency::class)->findAll();

        return $this->render(
            'currency/show.html.twig',
            ['currencyList' => $currencies]
        );
    }
}