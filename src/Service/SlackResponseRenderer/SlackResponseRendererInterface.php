<?php

namespace Spatricius\SlackUnfurlBundle\Service\SlackResponseRenderer;

use Spatricius\SlackUnfurlBundle\Service\SlackRequestParser\SlackRequestParserInterface;

interface SlackResponseRendererInterface
{
    public function supports(SlackRequestParserInterface $slackRequestParser): bool;

    public function resolve(SlackRequestParserInterface $slackRequestParser): string;
}