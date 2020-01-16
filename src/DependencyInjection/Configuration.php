<?php

namespace WebEtDesign\FaqBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('wd_faq');
        $rootNode
            ->children()
                ->arrayNode('configuration')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('use_category')->defaultFalse()->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
