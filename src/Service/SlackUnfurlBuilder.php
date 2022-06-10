<?php

namespace Spatricius\SlackUnfurlBundle\Service;

class SlackUnfurlBuilder
{
    public function __construct(
        protected string $accessoryImageUrl = '',
        protected string $accessoryAltText = ''
    ) {}

    public function build(string $text)
    {
        return array(
            'blocks' => $this->getBlocks($text),
        );
    }

    protected function getBlocks(string $text): array
    {
        return [
            array_merge(
                [
                    'type' => $this->getType(),
                    'text' =>
                        [
                            'type' => 'mrkdwn',
                            'text' => $text,
                        ],
                ],
                $this->getAccessoryArray()
            ),
        ];
    }

    protected function getType(): string
    {
        return 'section';
    }

    protected function getAccessoryArray(): ?array
    {
        if ($this->accessoryImageUrl) {
            return [
                'accessory' =>
                    [
                        'type' => 'image',
                        'image_url' => $this->accessoryImageUrl,
                        'alt_text' => $this->accessoryAltText,
                    ],
            ];
        }

        return [];
    }
}

