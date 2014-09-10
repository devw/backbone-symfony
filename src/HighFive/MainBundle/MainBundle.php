<?php

namespace HighFive\MainBundle;

use HighFive\MainBundle\DependencyInjection\Compiler\AddNotificationHandlersPass;
use HighFive\MainBundle\DependencyInjection\Serializer\AvatarFactory;
use JMS\SerializerBundle\DependencyInjection\JMSSerializerExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MainBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AddNotificationHandlersPass());
    }

    public function configureSerializerExtension(JMSSerializerExtension $ext)
    {
        $ext->addHandlerFactory(new AvatarFactory());
    }
}
