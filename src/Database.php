<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\LTI\Infrastructure\GAE;

use Google\Cloud\Firestore\FirestoreClient;
use GrotonSchool\Slim\LTI\Domain\Registration;
use GrotonSchool\Slim\LTI\Infrastructure\GAE\Firestore;
use GrotonSchool\Slim\LTI\Infrastructure\DatabaseInterface;
use Packback\Lti1p3\Interfaces\ILtiRegistration;
use Packback\Lti1p3\Interfaces\ILtiDeployment;
use Packback\Lti1p3\LtiDeployment;
use Packback\Lti1p3\OidcException;

/**
 * @see https://github.com/packbackbooks/lti-1-3-php-library/wiki/Laravel-Implementation-Guide#database Working from Packback's wiki example
 */
class Database implements DatabaseInterface
{
    private FirestoreClient $firestore;

    public function __construct()
    {
        $this->firestore = new FirestoreClient();
    }

    /**
     * @throws OidcException If multiple `issuer` matches without `clientId`
     */
    public function findRegistration(
        string $issuer,
        ?string $clientId = null
    ): ?Firestore\Registration {
        $query = $this->firestore
            ->collection(Firestore\Registration::COLLECTION_PATH)
            ->where('issuer', '=', $issuer);
        if ($clientId) {
            $query = $query->where('client_id', '=', $clientId);
        }
        $result = $query->documents();
        if ($result->size() > 1) {
            throw new OidcException(
                'Found multiple registrations for the given issuer, ensure a client_id is specified on login (contact your LMS administrator)',
                1
            );
        }
        if ($result->size() == 1) {
            return new Firestore\Registration($result->rows()[0]->data());
        }
        return null;
    }

    public function findRegistrationByIssuer(
        string $iss,
        ?string $clientId = null
    ): ?ILtiRegistration {
        return $this->findRegistration($iss, $clientId);
    }

    public function findDeployment(
        string $issuer,
        string $deploymentId,
        ?string $clientId = null
    ): ?ILtiDeployment {
        $registration = $this->findRegistration($issuer, $clientId);
        if (!$registration) {
            return null;
        }
        /*
         * FIXME: how do I know what deployment IDs are out there?
         *   Per https://github.com/packbackbooks/lti-1-3-php-library/issues/69#issuecomment-1713981975
         *   this is worth paying attention to, but it's not clear where in
         *   the workflow the deployment ID was originally provided. Right now
         *   I manually added it to the database.
         */
        if ($registration->hasDeployment($deploymentId)) {
            return LtiDeployment::new($deploymentId);
        }
        return null;
    }

    public function saveRegistration(Registration $registration): void
    {
        $document = $this->firestore
            ->collection(Firestore\Registration::COLLECTION_PATH)
            ->newDocument();
        $document->set($registration->jsonSerialize());
    }
}
