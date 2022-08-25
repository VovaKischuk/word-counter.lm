<?php

namespace App\Service;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function findList(string $search = '', string $sort = 'ASC'): array
    {
        $qb = $this->em->createQueryBuilder();
        $posts = $qb
            ->select('p')
            ->from(Post::class, 'p')
            ->where($qb->expr()->like('p.title', $qb->expr()->literal("%{$search}%")))
            ->orderBy('p.id', $sort)
            ->getQuery()
            ->getResult();

        if ($posts === null) {
            throw new NotFoundHttpException();
        }

        return $posts;
    }

    public function findById(int $id): Post
    {
        $post = $this->em->getRepository(Post::class)->findOneBy(['id' => $id]);

        if ($post === null) {
            throw new NotFoundHttpException();
        }

        return $post;
    }

    public function executeUpdate(int $id, array $data): Post
    {
        $post = $this->em->getRepository(Post::class)->findOneBy(['id' => $id]);

        $post->update($data['title'], $data['text']);

        $this->em->persist($post);
        $this->em->flush();

        return $post;
    }

    public function executeCreate(array $data): Post
    {
        $post = Post::create($data['title'], $data['text']);

        $this->em->persist($post);
        $this->em->flush();

        return $post;
    }

    public function executeDelete(int $id): void
    {
        $post = $this->em->getRepository(Post::class)->findOneBy(['id' => $id]);

        $this->em->remove($post);
        $this->em->flush();
    }
}