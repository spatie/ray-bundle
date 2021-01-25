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

        $definition = $container->getDefinition('spatie.ray');
        $definition->replaceArgument(0, $config['enable']);
        $definition->replaceArgument(1, $config['send_log_calls_to_ray']);
        $definition->replaceArgument(2, $config['send_dumps_to_ray']);
        $definition->replaceArgument(3, $config['host']);
        $definition->replaceArgument(4, $config['port']);
        $definition->replaceArgument(5, $config['remote_path']);
        $definition->replaceArgument(6, $config['local_path']);
    }

    public function getNamespace(): string
    {
        return 'https://spatie.be/schema/dic/ray';
    }

    public function getXsdValidationBasePath(): string
    {
        return __DIR__.'/../Resources/config/schema';
    }
}
