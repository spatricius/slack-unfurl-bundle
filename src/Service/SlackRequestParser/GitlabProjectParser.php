<?php

namespace Spatricius\SlackUnfurlBundle\Service\SlackRequestParser;

use Gitlab\Client;

class GitlabProjectParser implements SlackRequestParserInterface
{
    protected ?string $name;
    protected array $details = array();

    public static function getDefaultPriority(): int
    {
        return -5;
    }

    public function __construct(
        protected Client $gitlabClient,
        protected ?string $gitlabDomain
    ) {}

    public function supports(string $url): bool
    {
        $host = parse_url($url, PHP_URL_HOST);

        if ($host !== $this->gitlabDomain) {
            return false;
        }

        $path = parse_url($url, PHP_URL_PATH);
        $parts = explode('/', $path);

        return count($parts) >= 3;
    }

    public function parse(string $url): void
    {
        $path = parse_url($url, PHP_URL_PATH);
        $parts = explode('/', $path);
        $this->name = implode('/', [$parts[1], $parts[2]]);
    }

    public function getLazyDetails(): array
    {
        if (empty($this->details)) {
            $projects = $this->gitlabClient->projects()->all(
                $this->getOptions()
            );
            foreach ($projects as $project) {
                if (str_contains($project['web_url'], $this->name)) {
                    $this->details = $project;
                    break;
                }
            }
        }

        return $this->details;
    }

    public function checkRemotelyValid(): bool
    {
        $details = $this->getLazyDetails();

        return !empty($details);
    }

    protected function getOptions(): array
    {
        return array(
            'simple' => true,
            'search_namespaces' => true,
            'search' => $this->name,
            'order_by' => 'last_activity_at',
        );
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getId(): int
    {
        $details = $this->getLazyDetails();
        return (int)$details['id'] ?? 0;
    }
}