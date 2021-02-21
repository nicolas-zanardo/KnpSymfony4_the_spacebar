<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AccountController
 * @IsGranted("ROLE_USER", message="Access denied by @IsGranted")
 * @package App\Controller
 */
class AccountController extends BaseController
{
    /**
     * @Route("/account", name="app_account")
     * @param LoggerInterface $logger
     * @return Response
     */
    public function index(LoggerInterface $logger): Response
    {
        $logger->debug('Checking account page for '. $this->getUser()->getEmail());
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    /**
     * @Route("/api/account", name="api_account")
     */
    public function accountAPI(): JsonResponse
    {
        $user = $this->getUser();
        return $this->json($user, 200, [], [
            'groups' => ['main']
        ]);
    }
}
