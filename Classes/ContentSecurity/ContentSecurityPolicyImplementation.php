<?php
declare(strict_types=1);

namespace Nieuwenhuizen\ContentSecurityPolicy\ContentSecurity;

use Nieuwenhuizen\ContentSecurityPolicy\Domain\Model\Nonce;
use Nieuwenhuizen\ContentSecurityPolicy\Exceptions\InvalidDirectiveException;
use Neos\Flow\Annotations as Flow;

/**
 * Class ContentSecurityPolicyImplementation
 *
 * @package Nieuwenhuizen.ContentSecurityPolicy
 * @Flow\Scope("prototype")
 */
class ContentSecurityPolicyImplementation extends Directive
{

    /**
     * @Flow\Inject
     * @var Nonce
     */
    protected $nonce;

    /**
     * @Flow\InjectConfiguration(path="content-security-policy")
     * @var array
     */
    protected $configuration;

    /**
     * ContentSecurityPolicyImplementation constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        if (!$this->nonce instanceof Nonce) {
            $this->setNonce(new Nonce());
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function generate(): string
    {
        $csp = '';

        array_walk($this->configuration['default'], function (array $item, string $key) use (&$csp) {
            if (array_key_exists($key, $this->configuration['custom'])) {
                $item = array_merge($item, $this->configuration['custom'][$key]);
            }

            if ($this->isValidDirective($key)) {
                $csp .= "$key ";
                foreach ($item as $policy) {
                    if ($policy === '{nonce}') {
                        $csp .= "'nonce-" . $this->getNonce()->getValue() . "' ";
                    } else {
                        $csp .= $policy . ' ';
                    }
                }
                $csp .= ';';

                return trim($csp);
            }

            throw new InvalidDirectiveException('The given directive ' . $key . ' appears to be invalid');
        });

        return $csp;
    }

    /**
     * @return Nonce
     */
    public function getNonce(): Nonce
    {
        return $this->nonce;
    }

    /**
     * @param Nonce $nonce
     */
    public function setNonce(Nonce $nonce): void
    {
        $this->nonce = $nonce;
    }
}
