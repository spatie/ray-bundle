<?php

namespace Spatie\RayBundle\DependencyInjection;

use Spatie\RayBundle\Ray;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class SpatieRayExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        //$loader = new XmlFileLoader($container, new FileLocator(dirname(__DIR__).'/Resources/config'));
        //$loader->load('services.xml');

        $loader = new YamlFileLoader($container, new FileLocator(dirname(__DIR__).'/Resources/config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('spatie_ray.settings', $config);

        $container->getDefinition(Ray::class);
    }

    public function getNamespace(): string
    {
        return 'https://spatie.be/schema/dic/ray';
    }

    public function getXsdValidationBasePath(): string
    {
        return __DIR__ . '/../Resources/config/schema';
    }
}
