<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class LuckyController extends AbstractController
{
    /**
     * @throws \Exception If random number not found
     */
    public function number(): Response
    {
        $number = random_int(1, 100);

        return $this->render('lucky/number.html.twig',
            ['number' => $number]);
    }
}