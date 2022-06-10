<?php

namespace Spatricius\SlackUnfurlBundle\Message;

class LinkSharedMessage
{
    public function __construct(protected object $eventObject)
    {
    }

    public function getEventObject()
    {
        return $this->eventObject;
    }
}