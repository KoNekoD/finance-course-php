<?php

namespace App\Controller;

use App\Security\ApiTokenAuthenticator;
use App\Repository\CurrencyRepository;
use App\Repository\TokenRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class ApiCurrencyController extends AbstractController
{

    public function getList(int $page, Request $request, TokenRepository $tokenRepository, CurrencyRepository $currencyRepository): Response
    {

        $auth = new ApiTokenAuthenticator();
        if ($auth->supports($request)) {
            $token = $auth->getCredentials($request);
            if ($tokenRepository->findOneBy(['token' => $token])) {
                // build the query for the doctrine paginator
                $query = $currencyRepository->createQueryBuilder('u')
                    ->orderBy('u.id', 'DESC')
                    ->getQuery();

                //set page size
                $pageSize = '5';

                // load doctrine Paginator
                $paginator = new Paginator($query);

                // now get one page's items:
                $paginator
                    ->getQuery()
                    ->setFirstResult($pageSize * ($page - 1)) // set the offset
                    ->setMaxResults($pageSize); // set the limit

                return $this->json($paginator);
            } else {
                // For add new token if not exists
                //$eToken = new Token();
                //$eToken->setToken($token);
                //$tokenRepository->add($eToken, true);
                //return $this->json($eToken->getId());

                return $this->json([
                    'message' => 'Access denied'
                ], Response::HTTP_UNAUTHORIZED);
            }
        } else {
            return $this->json([
                'message' => 'You must authorize using bearer token'
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function getItem(string $id, Request $request, TokenRepository $tokenRepository, CurrencyRepository $currencyRepository): Response
    {
        $auth = new ApiTokenAuthenticator();
        if ($auth->supports($request)) {
            $token = $auth->getCredentials($request);
            if ($tokenRepository->findOneBy(['token' => $token])) {
                return $this->json($currencyRepository->find($id));
            } else {
                // For add new token if not exists
                //$eToken = new Token();
                //$eToken->setToken($token);
                //$tokenRepository->add($eToken, true);
                //return $this->json($eToken->getId());

                return $this->json([
                    'message' => 'Access denied'
                ], Response::HTTP_UNAUTHORIZED);
            }
        } else {
            return $this->json([
                'message' => 'You must authorize using bearer token'
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}
