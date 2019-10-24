<?php

namespace App\Event;

use App\Entity\Comment;
use Symfony\Component\EventDispatcher\Event;

class CommentAddedEvent extends Event
{
    protected $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function getComment(): Comment
    {
        return $this->comment;
    }
}
