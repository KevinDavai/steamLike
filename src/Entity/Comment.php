<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $comment_content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $comment_date_post;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentContent(): ?string
    {
        return $this->comment_content;
    }

    public function setCommentContent(string $comment_content): self
    {
        $this->comment_content = $comment_content;

        return $this;
    }

    public function getCommentDatePost(): ?string
    {
        return $this->comment_date_post;
    }

    public function setCommentDatePost(string $comment_date_post): self
    {
        $this->comment_date_post = $comment_date_post;

        return $this;
    }
}
