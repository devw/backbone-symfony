<?php

namespace HighFive\MainBundle\DependencyInjection\Serializer;

use JMS\SerializerBundle\DependencyInjection\HandlerFactoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AvatarFactory implements HandlerFactoryInterface
{
    public function getConfigKey()
    {
        return 'avatar';
    }

    public function getType(array $config)
    {
        return self::TYPE_SERIALIZATION;
    }

    public function addConfiguration(ArrayNodeDefinition $builder)
    {
        $builder
            ->addDefaultsIfNotSet()
        ;
    }

    public function getHandlerId(ContainerBuilder $container, array $config)
    {
        return 'high_five.serializer.avatar';
    }
}
