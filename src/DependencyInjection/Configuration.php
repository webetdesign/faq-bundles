<?php

namespace WebEtDesign\FaqBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('wd_faq');
        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('default_locale')->isRequired()->end()
                ->arrayNode('locales')
                    ->scalarPrototype()->isRequired()->end()
                ->end()
                ->arrayNode('configuration')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('use_category')->defaultFalse()->end()
                        ->scalarNode('admin_with_export')->defaultFalse()->end()
                        ->scalarNode('ckeditor_context')->defaultValue('default')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
