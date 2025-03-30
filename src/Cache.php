<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Infrastructure\GAE;

use Exception;
use Packback\Lti1p3\Interfaces\ICache;
use Google\Cloud\Firestore\FirestoreClient;
use GrotonSchool\Slim\LTI\Domain\ConsumerConfigurationInterface;
use GrotonSchool\Slim\LTI\Infrastructure\CacheInterface;

/**
 * @see https://github.com/packbackbooks/lti-1-3-php-library/wiki/Laravel-Implementation-Guide#cache Working from Packback's wiki example
 */
class Cache implements ICache, CacheInterface
{
    public const COLLECTION_PATH = 'cache';

    private FirestoreClient $firestore;
    private int $duration;

    public function __construct(SettingsInterface $settings)
    {
        $this->firestore = new FirestoreClient();
        $this->duration = $settings->getDuration();
    }

    public function getLaunchData(string $key): ?array
    {
        $result = $this->firestore
            ->collection(self::COLLECTION_PATH)
            ->where('key', '=', $key)
            ->documents();
        if ($result->size() > 1) {
            throw new Exception('Multiple records found');
        }
        if ($result->size() == 1) {
            return $result->rows()[0]->data()['jwtBody'];
        }
        return null;
        // TODO does launch data need to be cleared after single access?
    }

    public function cacheLaunchData(string $key, array $jwtBody): void
    {
        $this->firestore
            ->collection(self::COLLECTION_PATH)
            ->newDocument()
            ->set([
                'key' => $key,
                'jwtBody' => $jwtBody
            ]);
    }

    public function cacheNonce(string $nonce, string $state): void
    {
        $this->firestore
            ->collection(self::COLLECTION_PATH)
            ->newDocument()
            ->set([
                'nonce' =>  $nonce,
                'state' => $state,
                'expiration' => time() + $this->duration
            ]);
    }

    public function checkNonceIsValid(string $nonce, string $state): bool
    {
        $result = $this->firestore
            ->collection(self::COLLECTION_PATH)
            ->where('nonce', '=', $nonce)
            ->documents();
        if ($result->size() > 1) {
            throw new Exception('Multiple matching nonces found');
        }
        if ($result->size() == 1) {
            $nonce = $result->rows()[0]->data();
            $this->firestore->document($result->rows()[0]->name())->delete();
            return time() <= $nonce['expiration'] &&  $state === $nonce['state'];
        }
        return false;
    }

    public function cacheAccessToken(string $key, string $accessToken): void
    {
        $this->firestore
            ->collection(self::COLLECTION_PATH)
            ->newDocument()->set([
                'key' => $key,
                'accessToken' => $accessToken,
                'expiration' => time() + $this->duration
            ]);
    }

    public function getAccessToken(string $key): ?string
    {
        $result = $this->firestore
            ->collection(self::COLLECTION_PATH)
            ->where('key', '=', $key)
            ->where('expiration', '<=', time())->documents();
        if ($result->size() > 1) {
            throw new Exception('Multiple access tokens found');
        }
        if ($result->size() == 1) {
            return $result->rows()[0]->data()['accessToken'];
        }
        return null;
    }

    public function clearAccessToken(string $key): void
    {
        $result = $this->firestore
            ->collection(self::COLLECTION_PATH)
            ->where('key', '=', $key)->documents();
        for ($i = 0; $i < $result->size(); $i++) {
            $this->firestore->document($result->rows()[$i]->name())->delete();
        }
    }

    public function cacheConsumerConfiguration(ConsumerConfigurationInterface $config): string
    {
        $document = $this->firestore
            ->collection(self::COLLECTION_PATH)
            ->newDocument();
        $document->set([
            'configuration' =>  $config,
        ]);
        return $document->id();
    }

    public function getConsumerConfiguration(string $identifier): ConsumerConfigurationInterface
    {
        $$document = $this->firestore
            ->collection(self::COLLECTION_PATH)->document($identifier);
        $config = $document->data()['configuration'];
        $document->delete();
        return $config;
    }

    public function cacheRegistrationToken(string $registration_token): string
    {
        $document = $this->firestore
            ->collection(self::COLLECTION_PATH)
            ->newDocument();
        $document->set([
            'registration_token' => $registration_token
        ]);
        return $document->id();
    }

    public function getRegistrationToken(string $identifier): string
    {
        $$document = $this->firestore
            ->collection(self::COLLECTION_PATH)->document($identifier);
        $config = $document->data()['registration_token'];
        $document->delete();
        return $config;
    }
}
