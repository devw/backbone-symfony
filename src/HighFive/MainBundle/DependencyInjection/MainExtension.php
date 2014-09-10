<?php

namespace HighFive\MainBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MainExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('api.xml');
        $loader->load('avatar.xml');
        $loader->load('console.xml');
        $loader->load('locale.xml');
        $loader->load('information.xml');
        $loader->load('invitation.xml');
        $loader->load('mailer.xml');
        $loader->load('markdown.xml');
        $loader->load('messages.xml');
        $loader->load('notification.xml');
        $loader->load('tracking.xml');
        $loader->load('recognition.xml');
        $loader->load('registration.xml');
        $loader->load('serializer.xml');
        $loader->load('twig.xml');
        $loader->load('util.xml');

        // Adding this extra class avoids to have a class map of exactly 4096 bytes which is the case otherwise
        $this->addClassesToCompile(array('Symfony\Bridge\Twig\Extension\RoutingExtension'));
    }
}
