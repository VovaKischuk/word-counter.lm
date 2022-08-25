<?php

namespace App\Controller;

use App\Service\PostService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/posts')]
class PostController extends AbstractController
{
    #[Route(path: '', name: 'posts_list', methods: [Request::METHOD_GET])]
    public function listAction(Request $request, PostService $service): Response
    {
        $search = $request->query->get('search', '');
        $sort = $request->query->get('sort', '');

        $posts = $service->findList($search, $sort);

        return $this->render('posts.html.twig', [
            'result' => $posts
        ]);
    }

    #[Route(path: '/{id}', name: 'post_get', methods: [Request::METHOD_GET])]
    public function readAction(int $id, PostService $service): Response
    {
        $post = $service->findById($id);

        return $this->render('post.html.twig', [
            'result' => $post
        ]);
    }

    #[Route(path: '/{id}', name: 'post_update', methods: [Request::METHOD_PATCH])]
    public function updateAction(int $id, Request $request, PostService $service): Response
    {
        $data = $this->getRequestPayload($request);
        $post = $service->executeUpdate($id, $data);

        return $this->render('post.html.twig', [
            'result' => $post
        ]);
    }

    #[Route(path: '', name: 'post_create', methods: [Request::METHOD_POST])]
    public function createAction(Request $request, PostService $service): Response
    {
        $data = $this->getRequestPayload($request);
        $post = $service->executeCreate($data);

        return $this->render('posts.html.twig', [
            'result' => $post
        ]);
    }

    #[Route(
        path: '/{id}',
        name: 'delete_post',
        methods: [Request::METHOD_DELETE]
    )]
    public function deleteAction(int $id, PostService $service): Response
    {
        $service->executeDelete($id);
        $posts = $service->findList();

        return $this->render('posts.html.twig', [
            'result' => $posts
        ]);
    }
}