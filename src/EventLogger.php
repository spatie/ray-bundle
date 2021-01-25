<?php

namespace Spatie\RayBundle;

use Spatie\RayBundle\Payloads\EventPayload;

class EventLogger
{
    protected bool $enabled = false;

    private Ray $ray;

    public function __construct(Ray $ray)
    {
        $this->ray = $ray;
    }

    public function enable(): self
    {
        $this->enabled = true;

        return $this;
    }

    public function disable(): self
    {
        $this->enabled = false;

        return $this;
    }

    public function handleEvent(string $eventName, array $arguments): void
    {
        if (! $this->shouldHandleEvent($eventName)) {
            return;
        }

        $payload = new EventPayload($eventName, $arguments);

        $this->ray->sendRequest($payload);
    }

    public function isLoggingEvents(): bool
    {
        return $this->enabled;
    }

    protected function shouldHandleEvent($event): bool
    {
        return $this->enabled;
    }
}
