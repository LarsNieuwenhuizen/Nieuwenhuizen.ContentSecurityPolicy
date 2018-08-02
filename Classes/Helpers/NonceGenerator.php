<?php
declare(strict_types=1);

namespace Nieuwenhuizen\ContentSecurityPolicy\Helpers;

/**
 * Class NonceGenerator
 *
 * @package Nieuwenhuizen.ContentSecurityPolicy
 */
class NonceGenerator
{

    /**
     * @param int $length
     * @return string
     * @throws \Exception
     */
    public static function generateNonce($length = 16): string
    {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;

            $bytes = random_bytes($size);

            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }
}
