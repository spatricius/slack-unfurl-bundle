<?php

namespace Spatricius\SlackUnfurlBundle\Service\SlackResponseRenderer;

use Spatricius\SlackUnfurlBundle\Service\SlackRequestParser\SlackRequestParserInterface;

class SlackResponseResolver
{
    public function __construct(protected array $renderers)
    {
    }

    public function resolve(SlackRequestParserInterface $slackRequestParser): ?string
    {
        /** @var SlackResponseRendererInterface $renderer */
        foreach ($this->renderers as $renderer) {
            if ($renderer->supports($slackRequestParser)) {
                return $renderer->resolve($slackRequestParser);
            }
        }

        return null;
    }
}