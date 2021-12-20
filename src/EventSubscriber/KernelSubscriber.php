<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class KernelSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $httpUA = $request->server->get('HTTP_USER_AGENT');

        // Si on est sur un mobile
        if (strpos($httpUA, 'Mobile')) {
            // On redirige vers un site dédié. Ex: http://m.charlesen.fr
            dump('Je suis un mobile');

            $urlRedirect = new RedirectResponse('http://mobile.oflix.fr');
            // on écrase la réponse par défaut, pour rediriger vers 
            // la page m.charlesen.fr
            $event->setResponse($urlRedirect);
        }

        // on peut proposer des produits en fonction de la page précédente
        // Si on vient de chez macdo.fr ==> On va proposer de meilleurs sandwich
        $httpReferer = $request->server->get('HTTP_REFERER');

        // Sinon on ne fait rien
        // dd($request->server, $httpReferer, $httpUA);
    }

    /**
     * Cette méthode reprend la liste des évènements que l'on souhaite écouter
     *
     * @return void
     */
    public static function getSubscribedEvents()
    {
        return [
            // clé : l'évènement à écouter ==> valeur : Méthode à appeler en cas de déclenchement de l'évènement
            'kernel.request' => 'onKernelRequest',
        ];
    }
}
