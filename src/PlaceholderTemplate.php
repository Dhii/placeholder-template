<?php

namespace Dhii\Output;

use Dhii\Data\Container\CreateContainerExceptionCapableTrait;
use Dhii\Data\Container\CreateNotFoundExceptionCapableTrait;
use Dhii\Exception\CreateInvalidArgumentExceptionCapableTrait;
use Dhii\Exception\CreateRuntimeExceptionCapableTrait;
use Dhii\I18n\StringTranslatorAwareTrait;
use Dhii\I18n\StringTranslatorConsumingTrait;
use Dhii\Regex\GetAllMatchesRegexCapablePcreTrait;
use Dhii\Regex\QuoteRegexCapablePcreTrait;
use Dhii\Util\String\StringableReplaceCapableTrait;
use Dhii\Util\String\StringableInterface as Stringable;
use InvalidArgumentException;

/**
 * A template implementation that replaces placeholders in text.
 *
 * @since [*next-version*]
 */
class PlaceholderTemplate extends AbstractBasePlaceholderTemplate
{
    /* @since [*next-version*] */
    use GetAllMatchesRegexCapablePcreTrait;

    /* @since [*next-version*] */
    use QuoteRegexCapablePcreTrait;

    /* @since [*next-version*] */
    use StringableReplaceCapableTrait;

    /* @since [*next-version*] */
    use StringTranslatorConsumingTrait;

    /* @since [*next-version*] */
    use StringTranslatorAwareTrait;

    /* @since [*next-version*] */
    use CreateContainerExceptionCapableTrait;

    /* @since [*next-version*] */
    use CreateNotFoundExceptionCapableTrait;

    /* @since [*next-version*] */
    use CreateRuntimeExceptionCapableTrait;

    /* @since [*next-version*] */
    use CreateInvalidArgumentExceptionCapableTrait;

    /**
     * @since [*next-version*]
     *
     * @param Stringable|string|int|float|bool $template     The template which is represented by this instance.
     * @param Stringable|string|int|float|bool $tokenStart   The delimiter which represents the start of a token.
     * @param Stringable|string|int|float|bool $tokenEnd     The delimiter which represents the end of a token.
     * @param Stringable|string|int|float|bool $tokenDefault The default value to use when a token's value cannot be resolved.
     *
     * @throws InvalidArgumentException If one of the parameters is of an invalid type.
     */
    public function __construct($template, $tokenStart, $tokenEnd, $tokenDefault)
    {
        $this->_setPlaceholderTemplate($template);
        $this->_setTokenStart($tokenStart);
        $this->_setTokenEnd($tokenEnd);
        $this->_setDefaultPlaceholderValue($tokenDefault);
    }
}
