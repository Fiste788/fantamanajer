<?php
declare(strict_types=1);

namespace App\Authentication\Identifier;

use Authentication\Identifier\AbstractIdentifier;
use Authentication\Identifier\Resolver\ResolverAwareTrait;
use Authentication\Identifier\Resolver\ResolverInterface;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;

/**
 * @property \App\Service\WebauthnService $Webauthn
 */
class WebauthnHandleIdentifier extends AbstractIdentifier
{
    use ResolverAwareTrait;
    use ServiceAwareTrait;

    /**
     * Constructor
     *
     * @param array $config Configuration
     * @throws \Cake\Core\Exception\CakeException
     */
    public function __construct(array $config = [])
    {
        $this->setConfig($config);
        $this->loadService('Webauthn');
    }

    /**
     * Default configuration.
     * - `fields` The fields to use to identify a user by:
     *   - `username`: one or many username fields.
     * - `resolver` The resolver implementation to use.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'fields' => [
            self::CREDENTIAL_USERNAME => 'uuid',
        ],
        'resolver' => 'Authentication.Orm',
    ];

    /**
     * {@inheritDoc}
     *
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function identify(array $data)
    {
        if (!isset($data['publicKey']) || !isset($data['userHandle'])) {
            return null;
        }

        /** @var \Psr\Http\Message\ServerRequestInterface $request */
        $request = $data['request'];
        $publicKey = (string)$data['publicKey'];
        $userHandle = (string)$data['userHandle'];

        $result = $this->Webauthn->login($publicKey, $request, $userHandle);

        return $this->_findIdentity($result->getUserHandle());
    }

    /**
     * Find a user record using the username/identifier provided.
     *
     * @param string $identifier The username/identifier.
     * @return \ArrayAccess|array|null
     * @throws \RuntimeException
     */
    protected function _findIdentity(string $identifier)
    {
        /** @var string[] $fields */
        $fields = (array)$this->getConfig('fields.' . self::CREDENTIAL_USERNAME);
        $conditions = [];
        foreach ($fields as $field) {
            $conditions[$field] = $identifier;
        }

        return $this->getResolver()->find($conditions, ResolverInterface::TYPE_OR);
    }
}
