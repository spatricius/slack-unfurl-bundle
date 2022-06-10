<?php

namespace Spatricius\SlackUnfurlBundle\Service\SlackRequestParser;

class SlackRequestParserResolver
{
    public function __construct(protected \Traversable $parsers)
    {
    }

    public function resolve(string $url): ?SlackRequestParserInterface
    {
        /** @var SlackRequestParserInterface $parser */
        foreach ($this->parsers as $parser) {
            if ($parser->supports($url)) {
                return $parser;
            }
        }

        return null;
    }
}