<?php

declare(strict_types=1);

namespace MakinaCorpus\QueryBuilderBundle\DependencyInjection\Compiler;

use MakinaCorpus\QueryBuilder\Bridge\Doctrine\DoctrineQueryBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class RegisterDoctrineQueryBuilderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('doctrine.connections')) {
            return;
        }

        $queryBuilders = [];

        foreach ($container->getParameter('doctrine.connections') as $name => $serviceId) {
            $definition = new Definition();
            $definition->setClass(DoctrineQueryBuilder::class);
            $definition->setArguments([new Reference($serviceId)]);

            if ($container->hasDefinition('query_builder.converter.plugin_registry')) {
                $definition->addMethodCall('setConverterPluginRegistry', [new Reference('query_builder.converter.plugin_registry')]);
            }

            $queryBuilderServiceId = \sprintf('query_builder.doctrine.%s', $serviceId);
            $queryBuilders[$name] = $queryBuilderServiceId;

            $container->setDefinition($queryBuilderServiceId, $definition);
        }

        if ($queryBuilders) {
            if ($container->hasParameter('doctrine.default_connection')) {
                $defaultConnectionId = $container->getParameter('doctrine.default_connection');

                if ($queryBuilderId = ($queryBuilders[$defaultConnectionId] ?? null)) {
                    $container->setAlias(DoctrineQueryBuilder::class, $queryBuilderId);
                }
            }

            $container->setParameter('query_builder.doctrine.connections', $queryBuilders);
        }
    }
}
