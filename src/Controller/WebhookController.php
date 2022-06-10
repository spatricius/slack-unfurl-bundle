<?php

namespace Spatricius\SlackUnfurlBundle\Controller;

use Psr\Log\LoggerInterface;
use Spatricius\SlackUnfurlBundle\Message\LinkSharedMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class WebhookController extends AbstractController
{
    #[Route('/unfurl/', name: 'unfurl')]
    public function respondAction(Request $request, MessageBusInterface $bus, LoggerInterface $logger, $slackAppId, $slackRequestToken): Response
    {
        $requestBody = $request->getContent();
        try {
            $requestJson = json_decode($requestBody, false);
        } catch
        (\Throwable $e) {
            $logger->warning('Bad json in request body');
            return new Response('<h1>nope.</h1>', 404);
        }

        // check token
        if (empty($requestJson?->token) || $requestJson?->token !== $slackRequestToken) {
            $logger->warning(sprintf('Bad slack request token in the request: %s', $requestJson?->token));
            return new Response('<h1>nope.</h1>', 404);
        }

        // check api challenge (one-time)
        if (!empty($requestJson?->challenge)) {
            $logger->debug(sprintf('Challenge sent: %s', $requestJson?->challenge));
            return $this->json(array(
                'challenge' => $requestJson->challenge,
            ));
        }

        if (empty($requestJson?->api_app_id) || $requestJson?->api_app_id !== $slackAppId){
            $logger->warning(sprintf('Bad app id in the request: %s', $requestJson?->api_app_id));
            return new Response('<h1>bad app id.</h1>', 404);
        }

        // unfurl
        if ($requestJson?->event?->type === 'link_shared') {
            $bus->dispatch(new LinkSharedMessage($requestJson->event));
            $logger->debug(sprintf('Event received: %s', $requestJson?->event?->type));

            // tell slack the event will be processed
            return new Response('ok');
        }

        $logger->debug(sprintf('Event received: %s', $requestJson?->event?->type));
        return new Response('<h1>bad event type.</h1>', 404);
    }
}