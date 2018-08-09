<?php
declare(strict_types=1);

namespace Nieuwenhuizen\ContentSecurityPolicy\FusionObjects\Http;

use Neos\Fusion\FusionObjects\Http\ResponseHeadImplementation;
use Neos\Flow\Annotations as Flow;
use Nieuwenhuizen\ContentSecurityPolicy\Domain\Model\Policy;
use Nieuwenhuizen\ContentSecurityPolicy\Exceptions\PolicyConstructionException;
use Nieuwenhuizen\ContentSecurityPolicy\Factory\PolicyFactory;

class ContentSecurityPolicyHeaderImplementation extends ResponseHeadImplementation
{

    /**
     * @var bool
     * @Flow\InjectConfiguration(path="enabled")
     */
    protected $enabled;

    /**
     * @Flow\InjectConfiguration(path="content-security-policy")
     * @var array
     */
    protected $configuration;

    /**
     * @return array
     * @throws \Exception
     */
    public function getHeaders(): array
    {
        $headers = parent::getHeaders();

        if ($this->enabled) {
            $policy = $this->backendOrFrontendPolicy();
            $headers[$policy->getSecurityHeaderKey()] = (string)$policy;
        }

        return $headers;
    }

    /**
     * @return Policy
     * @throws PolicyConstructionException
     */
    protected function backendOrFrontendPolicy(): Policy
    {
        if ($this->getContext()['inBackend'] === true) {
            $policy = PolicyFactory::create(
                $this->configuration['backend'],
                $this->configuration['custom-backend']
            );
        } else {
            $policy = PolicyFactory::create(
                $this->configuration['default'],
                $this->configuration['custom']
            );
        }

        return $policy;
    }

    /**
     * @return array
     */
    protected function getContext(): array
    {
        return $this->fusionValue('context');
    }
}
