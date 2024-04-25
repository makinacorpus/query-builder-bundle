# Query Builder Bundle

Integrates `makinacorpus/query-builder` into Symfony.

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

# Services

Each Doctrine connection will have both `MakinaCorpus\QueryBuilder\QueryBuilder`
and `MakinaCorpus\QueryBuilder\DatabaseSession` associated service in container.

They are identifier by the `query_builder.session.CONNECTION_NAME` service
identifier. You can manually inject by using the service name, or use autowiring.

You can target a Doctrine connection by injecting a `QueryBuilder` or
`DatabaseSession` typed service by setting the parameter name to the Doctrine
connection name, for example:

```php
<?php

declare (strict_types=1);

namespace App\Controller;

use MakinaCorpus\QueryBuilder\DatabaseSession;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestingController extends AbstractController
{
    #[Route('/testing/query-builder', name: 'testing_query_builder')]
    public function testQueryBuilder(
        DatabaseSession $someConnectionName,
    ): Response {
    }
}
```

Will have the database session bridged over the `some_connection_name`
configued Doctrine connection.

# Usage

Simply inject the service wherever you need it, a controller action for example:

```php
<?php

declare (strict_types=1);

namespace App\Controller;

use MakinaCorpus\QueryBuilder\DatabaseSession;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestingController extends AbstractController
{
    #[Route('/testing/query-builder', name: 'testing_query_builder')]
    public function testQueryBuilder(
        DatabaseSession $session,
    ): Response {
        $result = $session
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
