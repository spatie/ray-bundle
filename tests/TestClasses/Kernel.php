<?php

namespace Spatie\RayBundle\Tests\TestClasses;

use Spatie\RayBundle\SpatieRayBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\HttpKernel;

class Kernel extends HttpKernel
{
    public function registerBundles(): array
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new SpatieRayBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/../src/Resources/config/services.yaml');
    }
}
