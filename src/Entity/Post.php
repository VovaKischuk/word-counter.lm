<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'posts')]
class Post
{
    use Timestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', length: 11)]
    private ?int $id;

    #[ORM\Column(name: 'title', type: 'string', length: 255, nullable: true)]
    private ?string $title;

    #[ORM\Column(name: 'text', type: 'text', nullable: true)]
    private ?string $text;

    public static function create(string $title, string $text): self
    {
        $instance = new self();

        $instance->title = $title;
        $instance->text = $text;

        return $instance;
    }

    public function update(string $title, string $text): void
    {
        $this->title = $title;
        $this->text = $text;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getText(): ?string
    {
        return $this->text;
    }
}