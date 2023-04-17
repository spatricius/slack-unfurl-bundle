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
        $filesChanged = [];
        foreach ($details['diffs'] as $diff) {
            $filesChanged[$diff['new_path']] = 1;
            $filesChanged[$diff['old_path']] = 1;
        }
        $filesChangedText = '- '.implode(",\n- ", array_keys($filesChanged));

        $text = <<<TEXT
MR {$details['title']} ({$details['state']})
Updated at: {$details['updated_at']}
Files changed:
$filesChangedText
TEXT;

        return $text;
    }
}
