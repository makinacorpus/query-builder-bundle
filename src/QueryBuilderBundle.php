<?php

declare (strict_types=1);

namespace MakinaCorpus\QueryBuilderBundle;

use MakinaCorpus\QueryBuilderBundle\DependencyInjection\Compiler\RegisterConverterPluginPass;
use MakinaCorpus\QueryBuilderBundle\DependencyInjection\Compiler\RegisterDoctrineQueryBuilderPass;
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
    }
}
