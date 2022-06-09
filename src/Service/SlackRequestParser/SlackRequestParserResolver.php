<?php

namespace Spatricius\SlackUnfurlBundle\Service\SlackRequestParser;

use SlackRequestParserInterface;

class SlackRequestParserResolver
{
    protected array $parsers;

    public function __construct(array $parsers)
    {
        $this->parsers = $parsers;
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