<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ApiCurrencyController extends AbstractController
{

    /**
     * @param GET['page']
     * @return Response
     */
    public function getList(int $page = 1,Request $request) : string
    {
        $page = $request->query->get('page');

        //$normalizer = new CustomNormalizer();
        $normalizer = new ObjectNormalizer();
        $jsonEncoder = new jsonEncoder();
        $serializer = new Serializer([$normalizer], [$jsonEncoder]);

        return $serializer->serialize($normalizer, 'json');
    }

    /**
     * @param string GET['id']
     * @return Response
     */
    public function getItem(string $id) : Response
    {

    }
}
