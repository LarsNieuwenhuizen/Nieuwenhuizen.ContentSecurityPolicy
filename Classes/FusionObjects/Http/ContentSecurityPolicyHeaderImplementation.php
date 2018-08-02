<?php
declare(strict_types=1);

namespace Nieuwenhuizen\ContentSecurityPolicy\FusionObjects\Http;

use Neos\Fusion\FusionObjects\Http\ResponseHeadImplementation;
use Nieuwenhuizen\ContentSecurityPolicy\ContentSecurity\ContentSecurityPolicyImplementation;
use Neos\Flow\Annotations as Flow;

class ContentSecurityPolicyHeaderImplementation extends ResponseHeadImplementation
{

    /**
     * @var bool
     * @Flow\InjectConfiguration(path="enabled")
     */
    protected $enabled;

    /**
     * @return array
     * @throws \Exception
     */
    public function getHeaders(): array
    {
        $headers = parent::getHeaders();

        if (!array_key_exists('Content-Security-Policy', $headers) && $this->enabled === true) {
            $headers['Content-Security-Policy'] = (new ContentSecurityPolicyImplementation())->generate();
        }

        return $headers;
    }
}
