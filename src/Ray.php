<?php

namespace Spatie\RayBundle;

use Composer\InstalledVersions;
use Spatie\Ray\Client;
use Spatie\Ray\Ray as BaseRay;
use Spatie\Ray\Settings\Settings;
use Symfony\Component\HttpKernel\Kernel;

class Ray extends BaseRay
{
    private EventLogger $eventLogger;

    private QueryLogger $queryLogger;

    public function __construct(Settings $settings, Client $client = null, string $uuid = null)
    {
        // persist the enabled setting across multiple instantiations
        $enabled = static::$enabled;

        parent::__construct($settings, $client, $uuid);

        static::$enabled = $enabled;

        $this->eventLogger = new EventLogger($this);
        $this->queryLogger = new QueryLogger();
    }

    public function showEvents($callable = null): self
    {
        $wasLoggingEvents = $this->eventLogger()->isLoggingEvents();

        $this->eventLogger()->enable();

        if ($callable) {
            $callable();

            if (! $wasLoggingEvents) {
                $this->eventLogger()->disable();
            }
        }

        return $this;
    }

    public function events($callable = null): self
    {
        return $this->showEvents($callable);
    }

    public function stopShowingEvents(): self
    {
        $this->eventLogger->disable();

        return $this;
    }

    public function showQueries($callable = null): self
    {
        $wasLoggingQueries = $this->queryLogger()->isLoggingQueries();

        $this->queryLogger()->startLoggingQueries();

        if (! is_null($callable)) {
            $callable();

            if (! $wasLoggingQueries) {
                $this->stopShowingQueries();
            }
        }

        return $this;
    }

    public function queries($callable = null): self
    {
        return $this->showQueries($callable);
    }

    public function stopShowingQueries(): self
    {
        $this->queryLogger()->stopLoggingQueries();

        return $this;
    }

    protected function eventLogger(): EventLogger
    {
        return $this->eventLogger;
    }

    protected function queryLogger(): QueryLogger
    {
        return $this->queryLogger;
    }

    public function sendRequest($payloads, array $meta = []): BaseRay
    {
        if (! static::$enabled) {
            return $this;
        }

        $meta['symfony_version'] = Kernel::VERSION;

        if (class_exists(InstalledVersions::class)) {
            $meta['ray_bundle_package_version'] = InstalledVersions::getVersion('spatie/ray-bundle');
        }

        return BaseRay::sendRequest($payloads, $meta);
    }
}
