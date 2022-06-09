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
        $commits = $slackRequestParser->getLazyCommits();

        $commitsText = '';
        foreach ($commits as $commit) {
            $commitsText .= "<{$commit['web_url']}|{$commit['short_id']}>";
            $commitsText .= ", ";
        }

        $text = <<<TEXT
MR {$details['title']} ({$details['state']})
Updated at: {$details['updated_at']}
Commits: $commitsText
TEXT;

        return $text;
    }
}