<?php
declare(strict_types=1);

namespace Nieuwenhuizen\ContentSecurityPolicy\ViewHelpers;

use Neos\FluidAdaptor\Core\ViewHelper\AbstractViewHelper;
use Nieuwenhuizen\ContentSecurityPolicy\Domain\Model\Nonce;
use Neos\Flow\Annotations as Flow;

/**
 * Class NonceViewHelper
 *
 * @package Nieuwenhuizen\ContentSecurityPolicy\ViewHelpers
 * @Flow\Scope("prototype")
 */
class NonceViewHelper extends AbstractViewHelper
{

    /**
     * @Flow\Inject
     * @var Nonce
     */
    protected $nonce;

    /**
     * @throws \Exception
     */
    public function render()
    {
        return $this->nonce->getValue();
    }
}
