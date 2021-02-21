<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentAdminController extends AbstractController
{

    /**
     * @Route("/admin/comment", name="comment_admin")
     * @param CommentRepository $repository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(CommentRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {

        $q = $request->query->get('q');

        $queryBuilder = $repository->findAllWithSearch($q);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );


        return $this->render('comment_admin/index.html.twig', [
            'pagination' => $pagination
        ]);
    }
}
