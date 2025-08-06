<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Infrastructure\GAE\Firestore;

use Exception;
use Google\Cloud\Firestore\FirestoreClient;

class FirestoreUserRepository implements UserRepositoryInterface
{
    private FirestoreClient $firestore;

    public function __construct()
    {
        $this->firestore = new FirestoreClient();
    }

    public function getLocator(string $consumerHostname, string $id): string
    {
        return  "instances/$consumerHostname/users/$id";
    }

    public function findUser(string $consumerHostname, string $id): User|null
    {
        $locator = $this->getLocator($consumerHostname, $id);
        try {
            $document = $this->firestore->document($locator);
            $snapshot = $document->snapshot();
            if ($snapshot->exists()) {
                return new User($locator, $snapshot->data());
            }
        } catch (Exception $_) {
            // do nothing
        }

        return null;
    }

    public function createUser(string $consumerHostname, string $id, array $data = []): User
    {
        $user = new User($this->getLocator($consumerHostname, $id), ['id' => $id, ...$data]);
        $this->saveUser($user, true);
        return $user;
    }

    public function saveUser(User $user, bool $force = false): bool
    {
        if ($user->isDirty() || $force) {
            $document = $this->firestore->document($user->getLocator());
            $document->set($user->getData(), ['merge' => true]);
            $user->setDirty(false);
            return true;
        }
        return false;
    }
}
