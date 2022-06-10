<?php

namespace Spatricius\SlackUnfurlBundle\Service\SlackRequestParser;

use Gitlab\Client;

class GitlabCompareParser implements SlackRequestParserInterface
{
    protected string $branch1;
    protected string $branch2;

    public function __construct(
        protected Client $gitlabClient,
        protected GitlabProjectParser $gitlabProjectParser
    ) {}

    protected function getMatches($url): array
    {
        // eg. /-/compare/symfony4...1504_scripts_optimization?from_project_id=158
        preg_match('#/-/compare/(?<branch1>\w+)\.\.\.(?<branch2>\w+)/*#', $url, $matches);

        return $matches;
    }

    public function supports(string $url): bool
    {
        $matches = $this->getMatches($url);
        if (empty($matches['branch1']) || empty($matches['branch2'])) {
            return false;
        }

        return true;
    }

    public function parse(string $url): void
    {
        $this->gitlabProjectParser->parse($url);
        $matches = $this->getMatches($url);
        $this->branch1 = $matches['branch1'];
        $this->branch2 = $matches['branch2'];
    }

    public function getBranch1(): string
    {
        return $this->branch1;
    }

    public function getBranch2(): string
    {
        return $this->branch2;
    }

    public function getDetails(): array
    {
        $compare = $this->gitlabClient->repositories()->compare(
            $this->gitlabProjectParser->getId(),
            $this->getBranch1(),
            $this->getBranch2()
        );

        return $compare;
    }
}