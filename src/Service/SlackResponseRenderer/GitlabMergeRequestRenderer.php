<?php

namespace Spatricius\SlackUnfurlBundle\Service\SlackResponseRenderer;

use Spatricius\SlackUnfurlBundle\Service\SlackRequestParser\GitlabMergeRequestParser;
use Spatricius\SlackUnfurlBundle\Service\SlackRequestParser\SlackRequestParserInterface;

class GitlabMergeRequestRenderer implements SlackResponseRendererInterface
{
    public function supports(SlackRequestParserInterface $slackRequestParser): bool
    {
        return $slackRequestParser instanceof GitlabMergeRequestParser;
    }

    /** @param GitlabMergeRequestParser $slackRequestParser */
    public function resolve(SlackRequestParserInterface $slackRequestParser): string
    {
        $details = $slackRequestParser->getLazyDetails();
        $changes = $slackRequestParser->getLazyChanges();

        $text = <<<TEXT
MR {$details['title']} ({$details['state']})
`{$changes['source_branch']}` -> `{$changes['target_branch']}`
Updated at: {$details['updated_at']}
Total changes: {$changes['changes_count']}
TEXT;

        return $text;
    }
}