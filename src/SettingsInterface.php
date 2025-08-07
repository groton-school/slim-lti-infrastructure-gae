<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Infrastructure\GAE;

interface SettingsInterface
{
    public function getCacheDuration(): int;
}
