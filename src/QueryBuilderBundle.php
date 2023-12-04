<?php

declare (strict_types=1);

namespace MakinaCorpus\QueryBuilderBundle;

use MakinaCorpus\QueryBuilderBundle\DependencyInjection\Compiler\RegisterConverterPluginPass;
use MakinaCorpus\QueryBuilderBundle\DependencyInjection\Compiler\RegisterDoctrineQueryBuilderPass;
use MakinaCorpus\QueryBuilder\Converter\InputConverter;
use MakinaCorpus\QueryBuilder\Converter\InputTypeGuesser;
use MakinaCorpus\QueryBuilder\Converter\OutputConverter;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class QueryBuilderBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterConverterPluginPass());
        $container->addCompilerPass(new RegisterDoctrineQueryBuilderPass());

        $container->registerForAutoconfiguration(InputConverter::class)->addTag('query_builder.converter_plugin');
        $container->registerForAutoconfiguration(InputTypeGuesser::class)->addTag('query_builder.converter_plugin');
        $container->registerForAutoconfiguration(OutputConverter::class)->addTag('query_builder.converter_plugin');
    }
}
