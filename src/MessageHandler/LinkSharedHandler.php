<?php

namespace Spatricius\SlackUnfurlBundle\MessageHandler;

use Spatricius\SlackUnfurlBundle\Message\LinkSharedMessage;
use Spatricius\SlackUnfurlBundle\Service\SlackRequestParser\SlackRequestParserResolver;
use Spatricius\SlackUnfurlBundle\Service\SlackResponseRenderer\SlackResponseResolver;
use Spatricius\SlackUnfurlBundle\Service\SlackUnfurlBuilder;
use JoliCode\Slack\ClientFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class LinkSharedHandler implements MessageHandlerInterface
{
    public function __construct(
        protected string $slackAppToken,
        protected SlackRequestParserResolver $slackRequestParserResolver,
        protected SlackResponseResolver $slackResponseResolver,
        protected SlackUnfurlBuilder $slackUnfurlBuilder,
        protected LoggerInterface $logger
    ) {}

    public function __invoke(LinkSharedMessage $message)
    {
        $eventObject = $message->getEventObject();
        $this->logger->debug(sprintf('LinkSharedHandler invoked for links %s', $eventObject->links));

        $unfurls = [];
        foreach ($eventObject->links as $linkObject) {
            $slackRequestParser = $this->slackRequestParserResolver->resolve($linkObject->url);
            if (!$slackRequestParser) {
                $this->logger->warning(sprintf('No slack request parser found for url %s', $linkObject->url));
            }

            $text = $this->slackResponseResolver->resolve($slackRequestParser);
            if (!$text) {
                $this->logger->warning(sprintf('No text response found for url %s', $linkObject->url));
            }

            $unfurls[$linkObject->url] = $this->slackUnfurlBuilder->build($text);
        }

        $request = [
            'channel' => $eventObject->channel,
            'ts' => $eventObject->message_ts,
            'unfurls' => json_encode($unfurls),
        ];

        $this->logger->debug('Sending callback');
        try {
            $client = ClientFactory::create($this->slackAppToken);
            $response = $client->chatUnfurl($request);
            $this->logger->info(sprintf('Callback sent with response: %s', $response));
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $this->logger->error(sprintf('Exception when sending callback: %s, with links: %s', $message, var_export($eventObject->links, true)));
        }
    }
}