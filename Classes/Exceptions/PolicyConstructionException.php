<?php
declare(strict_types=1);

namespace Nieuwenhuizen\ContentSecurityPolicy\Exceptions;

class PolicyConstructionException extends \Exception
{

    /**
     * @var string
     */
    protected $message = 'Something went wrong while trying to create a Policy';
}
