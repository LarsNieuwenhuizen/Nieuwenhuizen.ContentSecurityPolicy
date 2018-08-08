<?php
declare(strict_types=1);

namespace Nieuwenhuizen\ContentSecurityPolicy\Domain\Model;

use Neos\Flow\Annotations as Flow;
use Nieuwenhuizen\ContentSecurityPolicy\ContentSecurity\Directive;
use Nieuwenhuizen\ContentSecurityPolicy\Exceptions\InvalidDirectiveException;
use Nieuwenhuizen\ContentSecurityPolicy\Exceptions\NonceValueException;

/**
 * Class Policy
 *
 * @package Nieuwenhuizen.ContentSecurityPolicy
 */
class Policy
{

    /** @const string */
    const SECURITY_HEADER_KEY_REPORT_ONLY = 'Content-Security-Policy-Report-Only';

    /** @const string */
    const SECURITY_HEADER_KEY = 'Content-Security-Policy';

    /**
     * @var array
     */
    protected $directives = [];

    /**
     * @var Nonce
     */
    protected $nonce;

    /**
     * @var bool
     * @Flow\InjectConfiguration(path="report-only")
     */
    protected $reportOnly;

    /**
     * @param Nonce $nonce
     * @return Policy
     */
    public function setNonce(Nonce $nonce): Policy
    {
        $this->nonce = $nonce;

        return $this;
    }

    /**
     * @return bool
     */
    public function isReportOnly(): bool
    {
        return $this->reportOnly;
    }

    /**
     * @param bool $reportOnly
     * @return Policy
     */
    public function setReportOnly(bool $reportOnly): Policy
    {
        $this->reportOnly = $reportOnly;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecurityHeaderKey(): string
    {
        if ($this->isReportOnly()) {
            return self::SECURITY_HEADER_KEY_REPORT_ONLY;
        }

        return self::SECURITY_HEADER_KEY;
    }

    /**
     * @return array
     */
    public function getDirectives(): array
    {
        return $this->directives;
    }

    /**
     * @param string $directive
     * @param $values
     * @return Policy
     * @throws InvalidDirectiveException
     * @throws \ReflectionException
     */
    public function addDirective(string $directive, $values): self
    {
        if (!Directive::isValidDirective($directive)) {
            throw new InvalidDirectiveException();
        }

        $this->directives[$directive] = array_map(function ($value) use ($directive) {
            return $this->sanitizeValue($value);
        }, $values);

        return $this;
    }

    /**
     * @return Nonce
     */
    public function getNonce(): Nonce
    {
        return $this->nonce;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $directives = $this->getDirectives();
        $keys = array_keys($directives);

        $items = array_map(function ($values, $directive) {
            $value = implode(' ', $values);
            return "$directive $value";
        }, $directives, $keys);

        return implode(';', $items) . ';';
    }

    /**
     * @param string $value
     * @return string
     * @throws NonceValueException
     */
    protected function sanitizeValue(string $value): string
    {
        if ($this->isSpecialValue($value)) {
            return "'{$value}'";
        }

        if ($value === '{nonce}') {
            return "'nonce-" . $this->getNonce()->getValue() . "'";
        }

        return $value;
    }

    /**
     * @param string $directive
     * @return bool
     */
    protected function isSpecialValue(string $directive): bool
    {
        $specialDirectives = [
            'none',
            'report-sample',
            'self',
            'strict-dynamic',
            'unsafe-eval',
            'unsafe-inline',
        ];

        return in_array($directive, $specialDirectives);
    }
}
