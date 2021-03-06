<?php

namespace Spatricius\SlackUnfurlBundle\Service\SlackResponseRenderer;

use Spatricius\SlackUnfurlBundle\Service\SlackRequestParser\GitlabProjectParser;
use Spatricius\SlackUnfurlBundle\Service\SlackRequestParser\SlackRequestParserInterface;

class GitlabProjectRenderer implements SlackResponseRendererInterface
{
    public function supports(SlackRequestParserInterface $slackRequestParser): bool
    {
        return $slackRequestParser instanceof GitlabProjectParser;
    }

    /** @param GitlabProjectParser $slackRequestParser */
    public function resolve(SlackRequestParserInterface $slackRequestParser, array $options = array()): string
    {
        $name = $slackRequestParser->getName();
        $details = $slackRequestParser->getLazyDetails();

        $text = <<<TEXT
Project '$name':
Description: {$details['description']}
Last activity: {$details['last_activity_at']}
TEXT;

        return $text;
    }
}