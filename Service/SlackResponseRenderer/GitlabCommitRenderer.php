<?php

namespace Spatricius\SlackUnfurlBundle\Service\SlackResponseRenderer;

use Spatricius\SlackUnfurlBundle\Service\GitlabTextResolver\SlackResponseRendererInterface;
use Spatricius\SlackUnfurlBundle\Service\SlackRequestParser\GitlabCommitParser;
use Spatricius\SlackUnfurlBundle\Service\SlackRequestParser\SlackRequestParserInterface;

class GitlabCommitRenderer implements SlackResponseRendererInterface
{
    public function supports(SlackRequestParserInterface $slackRequestParser): bool
    {
        return $slackRequestParser instanceof GitlabCommitParser;
    }

    /** @param GitlabCommitParser $slackRequestParser */
    public function resolve(SlackRequestParserInterface $slackRequestParser): string
    {
        $commit      = $slackRequestParser->getLazyDetails();
        $parentLinks = $this->getParentLinks($slackRequestParser->getLazyParentsDetails());

        $text = <<<TEXT
Commit '{$commit['title']}' ({$commit['short_id']}) by {$commit['committer_name']}
Committed at {$commit['committed_date']}
Total changes: {$commit['stats']['total']}
$parentLinks
TEXT;

        return $text;
    }

    protected function getParentLinks(array $parentsDetails): string
    {
        $parentLinks = '';
        foreach ($parentsDetails as $parentsDetail) {
            $parentLinks .= "<{$parentsDetail['web_url']}|{$parentsDetail['short_id']}>, ";
        }
        if ($parentLinks) {
            $parentLinks = "Parents: $parentLinks";
        }

        return $parentLinks;
    }
}