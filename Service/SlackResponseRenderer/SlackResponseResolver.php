<?php

namespace Spatricius\SlackUnfurlBundle\Service\SlackResponseRenderer;

use Spatricius\SlackUnfurlBundle\Service\GitlabTextResolver\SlackResponseRendererInterface;
use Spatricius\SlackUnfurlBundle\Service\SlackRequestParser\SlackRequestParserInterface;

class SlackResponseResolver
{
    protected array $renderers;

    public function __construct(array $parsers)
    {
        $this->renderers = $parsers;
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