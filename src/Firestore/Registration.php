<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Infrastructure\GAE\Firestore;

/**
 * @method static self new(?array $registration)
 * @method self setAuthTokenUrl(?string $authTokenUrl)
 * @method self setAuthLoginUrl(?string $loginTokenUrl)
 * @method self setClientId(?string $clientId)
 * @method self setKeySetUrl(?string $keySetUrl)
 * @method self setKid(?string $kid)
 * @method self setIssuer(?string $issuer)
 * @method self setToolPrivateKey(?string $toolPrivateKey)
 */
class Registration extends \GrotonSchool\Slim\LTI\Domain\Registration implements Base
{
    // TODO make Registration::COLLECTION_PATH configurable
    public const COLLECTION_PATH = 'lti_registrations';

    public function name(): string
    {
        return self::COLLECTION_PATH . '/' . $this->getIssuer();
    }
}
