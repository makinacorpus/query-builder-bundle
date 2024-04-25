<?php

declare(strict_types=1);

namespace MakinaCorpus\QueryBuilderBundle\DependencyInjection\Compiler;

use MakinaCorpus\QueryBuilder\Bridge\Doctrine\DoctrineBridge;
use MakinaCorpus\QueryBuilder\DatabaseSession;
use MakinaCorpus\QueryBuilder\QueryBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class RegisterDoctrineBridgePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('doctrine.connections')) {
            return;
        }

        $queryBuilders = [];

        foreach ($container->getParameter('doctrine.connections') as $name => $serviceId) {
            $definition = new Definition();
            $definition->setClass(DoctrineBridge::class);
            $definition->setArguments([new Reference($serviceId)]);

            if ($container->hasDefinition('query_builder.converter.plugin_registry')) {
                $definition->addMethodCall('setConverterPluginRegistry', [new Reference('query_builder.converter.plugin_registry')]);
            }

            $queryBuilderServiceId = 'query_builder.session.' . $serviceId;
            $queryBuilders[$name] = $queryBuilderServiceId;

            $container->setDefinition($queryBuilderServiceId, $definition);

            // Alias 'query_builder.doctrine.CONNECTION' is deprecated.
            $container->setAlias('query_builder.doctrine.' . $serviceId, $queryBuilderServiceId);

            // Register aliases based upon arguement name.
            $container->registerAliasForArgument($queryBuilderServiceId, QueryBuilder::class, $name);
            $container->registerAliasForArgument($queryBuilderServiceId, DatabaseSession::class, $name);
        }

        if ($queryBuilders) {
            // Register global aliases that will target the default connection.
            if ($container->hasParameter('doctrine.default_connection')) {
                $defaultConnectionId = $container->getParameter('doctrine.default_connection');

                if ($queryBuilderId = ($queryBuilders[$defaultConnectionId] ?? null)) {
                    $container->setAlias(QueryBuilder::class, $queryBuilderId);
                    $container->setAlias(DatabaseSession::class, $queryBuilderId);
                }
            }

            $container->setParameter('query_builder.doctrine.connections', $queryBuilders);
        }
    }
}
