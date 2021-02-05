<?php

namespace Spatie\RayBundle\Tests;

use Nyholm\BundleTest\BaseBundleTestCase;
use Spatie\RayBundle\SpatieRayBundle;

class SpatieRayBundleTest extends BaseBundleTestCase
{
    protected function getBundleClass(): string
    {
        return SpatieRayBundle::class;
    }

    /** @test */
    public function it_can_init_the_bundle(): void
    {
        $this->bootKernel();

        $container = $this->getContainer();

        $settings = $container->getParameter('spatie_ray.settings');

        self::assertTrue($settings['enable']);
        self::assertTrue($settings['send_log_calls_to_ray']);
        self::assertTrue($settings['send_dumps_to_ray']);
        self::assertSame('localhost', $settings['host']);
        self::assertSame(23517, $settings['port']);
        self::assertNull($settings['remote_path']);
        self::assertNull($settings['local_path']);
    }

    /** @test */
    public function it_works_with_different_configuration(): void
    {
        $kernel = $this->createKernel();

        $kernel->addConfigFile(__DIR__.'/TestClasses/config.yml');

        $this->bootKernel();

        $container = $this->getContainer();

        $settings = $container->getParameter('spatie_ray.settings');

        self::assertFalse($settings['enable']);
        self::assertFalse($settings['send_log_calls_to_ray']);
        self::assertFalse($settings['send_dumps_to_ray']);
        self::assertSame('different', $settings['host']);
        self::assertSame(1, $settings['port']);
        self::assertNull($settings['remote_path']);
        self::assertNull($settings['local_path']);
    }
}
