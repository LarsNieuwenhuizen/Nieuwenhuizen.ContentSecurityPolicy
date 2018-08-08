<?php
declare(strict_types=1);

namespace Nieuwenhuizen\ContentSecurityPolicy\FusionObjects\Http;

use Neos\Fusion\FusionObjects\Http\ResponseHeadImplementation;
use Neos\Flow\Annotations as Flow;
use Nieuwenhuizen\ContentSecurityPolicy\ContentSecurity\ContentSecurityPolicyImplementation;
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
            $policy = PolicyFactory::create($this->configuration['default'], $this->configuration['custom']);
            $headers[$policy->getSecurityHeaderKey()] = (string)$policy;
        }

        return $headers;
    }
}
