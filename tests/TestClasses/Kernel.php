<?php

namespace Spatie\RayBundle\Tests\TestClasses;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Spatie\RayBundle\SpatieRayBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    public function registerBundles(): array
    {
        return [
            new FrameworkBundle(),
            new SpatieRayBundle(),
            new DoctrineBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        //$loader->load(__DIR__ . '/../src/Resources/config/services.yaml');
    }
}
