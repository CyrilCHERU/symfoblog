<?php

namespace App\Subscriber;

use App\Event\CommentAddedEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mime\Email;

class CommentNotificationSubscriber implements EventSubscriberInterface
{
    protected $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    public static function getSubscribedEvents()
    {
        return [
            'blog.comment.add' => 'sendNotificationEmail'
        ];
    }

    public function sendNotificationEmail(CommentAddedEvent $event)
    {
        $comment = $event->getComment();


        // TODO : Send an email to admin
        $email = new Email();
        $email->from(("notification@blog-a-la-con.com"))
            ->to("admin@admin.com")
            ->subject("Nouveau commentaire sur votre blog")
            ->html("
            <h2>Nouveau commentaire posté par </h2>
            <strong>{$comment->getAuthor()->getName()}</strong> a écrit un commentaire sue l'article {$comment->getPost()->getTitle()}
            <p>{$comment->getContent()}</p>
            ");

        $this->mailer->send($email);
    }
}
