<?php

namespace Spatie\RayBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class SpatieRayExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(dirname(__DIR__).'/Resources/config'));
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        /**
        $container->setParameter('ray.enable', $config['enable']);
        $container->setParameter('ray.send_log_calls_to_ray', $config['send_log_calls_to_ray']);
        $container->setParameter('ray.send_dumps_to_ray', $config['send_dumps_to_ray']);
        $container->setParameter('ray.host', $config['host']);
        $container->setParameter('ray.port', $config['port']);
        $container->setParameter('ray.remote_path', $config['remote_path']);
        $container->setParameter('ray.local_path', $config['local_path']);
         */

        $container->setParameter('ray.settings', $config);

        $container->getDefinition('spatie_ray');
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
