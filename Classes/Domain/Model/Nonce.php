<?php
declare(strict_types=1);

namespace Nieuwenhuizen\ContentSecurityPolicy\Domain\Model;

use Neos\Flow\Annotations as Flow;
use Nieuwenhuizen\ContentSecurityPolicy\Exceptions\NonceValueException;
use Nieuwenhuizen\ContentSecurityPolicy\Helpers\NonceGenerator;

/**
 * Class Nonce
 *
 * @Flow\Scope("singleton")
 */
class Nonce
{

    /**
     * @var string
     */
    private $value;

    /**
     * Nonce constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->value = NonceGenerator::generateNonce();
    }

    /**
     * @return string
     * @throws NonceValueException
     */
    public function getValue(): string
    {
        if (!$this->value) {
            throw new NonceValueException();
        }
        return $this->value;
    }
}
