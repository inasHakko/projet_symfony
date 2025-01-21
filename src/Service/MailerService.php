<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class MailerService
{
    private $replayTo;
        public function __construct(private MailerInterface $mailer, $replayTo)
        {
            $this->replayTo = $replayTo;
        }
    public function sendEmail(
        $to = 'inas.hakkou11@gmail.com',
        $content = 'See Twig integration for better HTML integration!',
        $subject = 'Time for Symfony Mailer!'
    )
    {
        // dd($this->replayTo);
        $email = (new Email())
            ->from('noreplaysymfony@gmail.com')
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            ->replyTo($this->replayTo)
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            ->text('Sending emails is fun again!')
            ->html($content);

        $this->mailer->send($email);
        return new Response('Email sent successfully');
        // ...
    }

    // public function sendEmail(
    //     string $to,
    //     string $content,
    //     string $subject = 'No subject'
    // ): Response {
    //     $email = (new Email())
    //     ->from('noreplay_symfony@gmail.com')
    //     ->to('inas.hakkou11@gmail.com')
    //     ->subject('Test Email')
    //     ->text('Ceci est un test pour vérifier l’envoi des emails.')
    //     ->html('<p>Ceci est un test pour vérifier l’envoi des emails.</p>');

    // try {
    //     $this->mailer->send($email);
    //     return new Response('Email envoyé avec succès.');
    // } catch (\Exception $e) {
    //     return new Response('Erreur : ' . $e->getMessage());
    // }
    // }


}