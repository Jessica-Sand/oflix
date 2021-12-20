<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    public function onKernelResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();
        $content = $response->getContent();
        // Le message de maintenance sera configuré
        // depuis le fichier .env.
        $maintenanceMsg = $_ENV['MAINTENANCE_MSG'];

        if (!empty($maintenanceMsg)) {
            // On remplace le contenu HTML que Symfony s'appretait à retourner
            // au client
            $content = str_replace('<body>', '<body><div class="text-center mb-0 alert alert-danger">' . $maintenanceMsg . '</div>', $content);

            // On injecte le contenu modifié dans la réponse
            // Le client recevra le contenu demandé au départ + Alerte
            $response->setContent($content);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.response' => 'onKernelResponse',
        ];
    }
}
