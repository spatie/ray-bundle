<?php

namespace Spatie\RayBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Spatie\RayBundle\DependencyInjection\SpatieRayExtension;

class SpatieRayExtensionTest extends AbstractExtensionTestCase
{
    protected function getContainerExtensions(): array
    {
        return [
            new SpatieRayExtension(),
        ];
    }

    /** @test */
    public function after_loading_the_correct_parameter_has_been_set(): void
    {
        $this->load();

        $expectedSettings = [
            'enable' => true,
            'send_log_calls_to_ray' => true,
            'send_dumps_to_ray' => true,
            'host' => 'localhost',
            'port' => 23517,
            'remote_path' => null,
            'local_path' => null,
        ];

        $this->assertContainerBuilderHasParameter('ray.settings', $expectedSettings);
    }
}
