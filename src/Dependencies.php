<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Infrastructure\GAE;

use DI;
use DI\ContainerBuilder;
use GrotonSchool\Slim\LTI\Infrastructure\CacheInterface;
use GrotonSchool\Slim\LTI\Infrastructure\DatabaseInterface;
use GrotonSchool\Slim\Norms\DependenciesInterface;

class Dependencies implements DependenciesInterface
{
    public function inject(ContainerBuilder $containerBuilder): void
    {
        $containerBuilder->addDefinitions([
            // autowire groton-school/slim-lti-shim implementations
            CacheInterface::class => DI\autowire(Cache::class),
            DatabaseInterface::class => DI\autowire(Database::class)
        ]);
    }
}
