<?php

namespace HighFive\MainBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This compiler pass registers the handlers in the NotificationManager.
 */
class AddNotificationHandlersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('high_five.notification.manager')) {
            return;
        }

        $definition = $container->getDefinition('high_five.notification.manager');

        foreach ($container->findTaggedServiceIds('high_five.notification_handler') as $id => $tags) {
            $definition->addMethodCall('addHandler', array(new Reference($id)));
        }
    }
}
