<?php
declare(strict_types=1);

namespace Nieuwenhuizen\ContentSecurityPolicy\ContentSecurity;

use ReflectionClass;

abstract class Directive
{

    /** @const string */
    const BASE = 'base-uri';

    /** @const string */
    const BLOCK_ALL_MIXED_CONTENT = 'block-all-mixed-content';

    /** @const string */
    const CHILD = 'child-src';

    /** @const string */
    const CONNECT = 'connect-src';

    /** @const string */
    const DEFAULT = 'default-src';

    /** @const string */
    const FONT = 'font-src';

    /** @const string */
    const FORM_ACTION = 'form-action';

    /** @const string */
    const FRAME = 'frame-src';

    /** @const string */
    const FRAME_ANCESTORS = 'frame-ancestors';

    /** @const string */
    const IMG = 'img-src';

    /** @const string */
    const MANIFEST = 'manifest-src';

    /** @const string */
    const MEDIA = 'media-src';

    /** @const string */
    const OBJECT = 'object-src';

    /** @const string */
    const PLUGIN = 'plugin-types';

    /** @const string */
    const REPORT = 'report-uri';

    /** @const string */
    const SANDBOX = 'sandbox';

    /** @const string */
    const SCRIPT = 'script-src';

    /** @const string */
    const STYLE = 'style-src';

    /** @const string */
    const UPGRADE_INSECURE_REQUESTS = 'upgrade-insecure-requests';

    /** @const string */
    const WORKER = 'worker-src';

    /**
     * @param string $directive
     * @return bool
     * @throws \ReflectionException
     */
    public function isValidDirective(string $directive): bool
    {
        $validDirectives = (new ReflectionClass(static::class))->getConstants();

        return in_array($directive, $validDirectives);
    }
}
