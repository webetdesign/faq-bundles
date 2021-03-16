<?php

namespace WebEtDesign\FaqBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class FaqBundleExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $processor     = new Processor();
        $config        = $processor->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
        $loader->load('admin.yaml');

        $container->setParameter('wd_faq.locales', $config['locales']);
        $container->setParameter('wd_faq.default_locale', $config['default_locale']);
        $container->setParameter('wd_faq.config', $config['configuration']);

    }

    public function getAlias()
    {
        return 'wd_faq';
    }

}
