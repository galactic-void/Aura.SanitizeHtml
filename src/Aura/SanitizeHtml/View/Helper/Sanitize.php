<?php
/**
 * 
 * This file is part of the Giraffe project for PHP.
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\SanitizeHtml\View\Helper;

use Aura\View\Helper\AbstractHelper;
use Aura\SanitizeHtml\Sanitize as SanitizeHtml;

/**
 * 
 * 
 * 
 * @package Aura.SanitizeHtml
 * 
 */
class Sanitize extends AbstractHelper
{
    /**
     * 
     * @var SanitizeHtml
     *
     */
    protected $sanitize;

    /**
     * 
     * @param SanitizeHtml $sanitize
     *
     */
    public function __construct(SanitizeHtml $sanitize)
    {
        $this->sanitize = $sanitize;
    }

    /**
     * 
     * Sanitize HTML input.
     * 
     * @param  string $input
     * 
     * @return string
     *
     */
    public function __invoke($input)
    {
        return $this->sanitize->sanitize($input);
    }
}
 