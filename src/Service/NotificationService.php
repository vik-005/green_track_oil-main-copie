<?php

namespace App\Service;

use Twig\Environment;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\DemandesProspection;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;  // Ajout d'un logger pour suivre les erreurs

class NotificationService
{
    private $mailer;
    private $twig;
    private $logger;  // Ajout du logger

    public function __construct(MailerInterface $mailer, Environment $twig, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->logger = $logger;  // Initialisation du logger
    }

    /**
     * Envoie une demande d'approbation pour une prospection.
     */
    public function sendApprovalRequest(DemandesProspection $prospection): void
    {
        try {
            $agent = $prospection->getAgent();
            $this->validateAgentEmail($agent);  // Vérifie l'agent et son email

            $email = (new Email())
                ->from(new Address('providencekoukoui@gmail.com', 'Notification App'))
                ->to(new Address($agent->getEmail()))
                ->subject('Demande de prospection en attente d\'approbation')
                ->html($this->twig->render('emails/approval_request.html.twig', [
                    'prospection' => $prospection,
                    'agent' => $agent,
                ]));

            $this->mailer->send($email);
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de l\'envoi de la demande d\'approbation: ' . $e->getMessage());
        }
    }

    /**
     * Envoie une notification de mise à jour de la prospection.
     */
    public function sendProspectionUpdateNotification(DemandesProspection $prospection): void
    {
        try {
            $agent = $prospection->getAgent();
            $this->validateAgentEmail($agent);

            $email = (new Email())
                ->from(new Address('no-reply@example.com', 'Notification App'))
                ->to(new Address($agent->getEmail()))
                ->subject('Demande de prospection mise à jour')
                ->html($this->twig->render('emails/prospection_update.html.twig', [
                    'prospection' => $prospection,
                    'agent' => $agent,
                ]));

            $this->mailer->send($email);
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de l\'envoi de la notification de mise à jour: ' . $e->getMessage());
        }
    }

    /**
     * Envoie une notification de suppression de la prospection.
     */
    public function sendProspectionDeletionNotification(DemandesProspection $prospection): void
    {
        try {
            $agent = $prospection->getAgent();
            $this->validateAgentEmail($agent);

            $email = (new Email())
                ->from(new Address('no-reply@example.com', 'Notification App'))
                ->to(new Address($agent->getEmail()))
                ->subject('Demande de prospection supprimée')
                ->html($this->twig->render('emails/prospection_deletion.html.twig', [
                    'prospection' => $prospection,
                    'agent' => $agent,
                ]));

            $this->mailer->send($email);
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de l\'envoi de la notification de suppression: ' . $e->getMessage());
        }
    }

    /**
     * Valide si l'agent existe et a une adresse e-mail valide.
     *
     * @throws \Exception
     */
    private function validateAgentEmail($agent): void
    {
        if (!$agent || !$agent->getEmail()) {
            throw new \Exception('L\'agent n\'existe pas ou ne possède pas d\'adresse e-mail valide.');
        }
    }
}
