<?php

declare(strict_types=1);

namespace MakinaCorpus\QueryBuilderBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;

class RegisterConverterPluginPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('query_builder.converter.plugin_registry')) {
            return;
        }

        $registryDefinition = $container->getDefinition('query_builder.converter.plugin_registry');

        foreach ($this->findAndSortTaggedServices('query_builder.converter_plugin', $container) as $reference) {
            $registryDefinition->addMethodCall('register', [$reference]);
        }
    }
}
