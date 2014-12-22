<?php

namespace Incenteev\DynamicParametersBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('incenteev_dynamic_parameters')
            ->fixXmlConfig('parameter')
            ->children()
                ->booleanNode('import_parameter_handler_map')->defaultFalse()->end()
                ->scalarNode('composer_file')
                    ->defaultValue('%kernel.root_dir%/../composer.json')
                    ->cannotBeEmpty()
                    ->info('The path to the composer.json file to load the ParameterHandler env-map')
                ->end()
                ->arrayNode('parameters')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->beforeNormalization()
                            ->ifString()
                            ->then(function ($v) {
                                return array('variable' => $v);
                            })
                        ->end()
                        ->children()
                            ->scalarNode('variable')->isRequired()->cannotBeEmpty()->end()
                            ->booleanNode('yaml')->defaultFalse()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
