# Query Builder Bundle

Integrates `makinacorpus/query-builder` into Symfony.

This bundle will create a service per each `doctrine/dbal` connection:

 - Each service name is `query_builder.doctrine.CONNECTION_NAME`,
 - If a `default` connection is present, then an alias is wired for it using the
   `MakinaCorpus\QueryBuilder\Bridge\Doctrine\DoctrineQueryBuilder` class name.

And that's pretty much it, for now.

# Setup

First install:

```sh
composer require makinacorpus/query-builder-bundle
```

Then add the bundle to `config/bundles.php` if `symfony/flex` did not:

```php
return [
    // ... your other bundles.
    MakinaCorpus\QueryBuilderBundle\QueryBuilderBundle::class => ['all' => true],
];
```

And you're done.

# Usage

Simply inject the service wherever you need it, a controller action for example:

```php
<?php

declare (strict_types=1);

namespace App\Controller;

use MakinaCorpus\QueryBuilder\Bridge\Doctrine\DoctrineQueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestingController extends AbstractController
{
    #[Route('/testing/query-builder', name: 'testing_query_builder')]
    public function testQueryBuilder(
        DoctrineQueryBuilder $queryBuilder,
    ): Response {
        $result = $queryBuilder
            ->select('some_table')
            ->executeQuery()
        ;

        $data = [];
        foreach ($result->iterateAssociative() as $row) {
            $data[] = $row;
        }

        return $this->json($data);
    }
}
```

Basic, simple.
