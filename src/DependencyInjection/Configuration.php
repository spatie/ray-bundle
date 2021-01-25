<?php

namespace Spatie\RayBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('spatie.ray');

        $treeBuilder->getRootNode()
            ->children()
                ->booleanNode('enable')
                    ->defaultTrue()
                    ->info("This settings controls whether data should be sent to Ray. By default, `ray()` will only transmit data in non-production environments.")
                ->end()
                ->booleanNode('send_log_calls_to_ray')
                    ->defaultTrue()
                    ->info("When enabled, all things logged to the application log will be sent to Ray as well.")
                ->end()
                ->booleanNode('send_dumps_to_ray')
                    ->defaultTrue()
                    ->info("When enabled, all things passed to `dump` or `dd` will be sent to Ray as well.")
                ->end()
                ->booleanNode('enable')
                    ->defaultTrue()
                    ->info("This settings controls whether data should be sent to Ray. By default, `ray()` will only transmit data in non-production environments.")
                ->end()
                ->scalarNode('host')
                    ->defaultValue('localhost')
                    ->info("The host used to communicate with the Ray app. \nFor usage in Docker on Mac or Windows, you can replace host with 'host.docker.internal' \nFor usage in Homestead on Mac or Windows, you can replace host with '10.0.2.2")
                ->end()
                ->integerNode('port')
                    ->defaultValue(23517)
                    ->info("The port number used to communicate with the Ray app.")
                ->end()
                ->scalarNode('remote_path')
                    ->defaultValue(null)
                    ->info("Absolute base path for your sites or projects in Homestead, Vagrant, Docker, or another remote development server.")
                ->end()
                ->scalarNode('local_path')
                    ->defaultValue(null)
                    ->info("Absolute base path for your sites or projects on your local computer where your IDE or code editor is running on.")
                ->end()
            ->end();

        return $treeBuilder;
    }
}
