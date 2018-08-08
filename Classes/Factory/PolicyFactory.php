<?php
declare(strict_types=1);

namespace Nieuwenhuizen\ContentSecurityPolicy\Factory;

use Nieuwenhuizen\ContentSecurityPolicy\Domain\Model\Nonce;
use Nieuwenhuizen\ContentSecurityPolicy\Domain\Model\Policy;
use Nieuwenhuizen\ContentSecurityPolicy\Exceptions\PolicyConstructionException;

class PolicyFactory
{

    /**
     * @return Policy
     * @throws PolicyConstructionException
     */
    public static function create(): Policy
    {
        try {
            $directiveCollections = func_get_args();
            $directives = array_shift($directiveCollections);

            array_walk($directives, function (array &$item, string $key) use ($directiveCollections) {
                foreach ($directiveCollections as $collection) {
                    if (array_key_exists($key, $collection)) {
                        $item = array_unique(array_merge($item, $collection[$key]));
                    }
                }
            });

            $policy = new Policy();
            $nonce = new Nonce();
            $policy->setNonce($nonce);

            foreach ($directives as $directive => $values) {
                $policy->addDirective($directive, $values);
            }

            return $policy;
        } catch (\Exception $exception) {
            throw new PolicyConstructionException();
        }
    }
}
