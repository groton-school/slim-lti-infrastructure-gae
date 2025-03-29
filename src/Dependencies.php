<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Infrastructure\GAE;

use DI;
use GrotonSchool\Slim\LTI\Infrastructure\DatabaseInterface;
use Packback\Lti1p3\Interfaces\ICache;
use Packback\Lti1p3\Interfaces\IDatabase;

class Dependencies
{
    public static function addDefinitions(DI\ContainerBuilder $containerBuilder)
    {
        $containerBuilder->addDefinitions([
            IDatabase::class => DI\autowire(Database::class),
            DatabaseInterface::class => DI\autowire(Database::class),
            ICache::class => DI\autowire(Cache::class)
        ]);
    }
}
