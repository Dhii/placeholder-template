<?php

namespace Dhii\Output;

use Dhii\Factory\Exception\CreateCouldNotMakeExceptionCapableTrait;
use Dhii\I18n\StringTranslatorAwareTrait;
use Dhii\I18n\StringTranslatorConsumingTrait;
use Dhii\Util\Normalization\NormalizeStringableCapableTrait;
use Dhii\Util\String\StringableInterface as Stringable;
use Exception as RootException;
use InvalidArgumentException;

/**
 * A factory of placeholder templates.
 *
 * @since [*next-version*]
 */
class PlaceholderTemplateFactory extends AbstractBasePlaceholderTemplateFactory
{
    /**
     * The classname of the the product that this factory will create.
     * 
     * @since [*next-version*]
     * 
     * @var string|Stringable|null
     */
    protected $productClassname;

    /* @since [*next-version*] */
    use NormalizeStringableCapableTrait;

    /* @since [*next-version*] */
    use CreateCouldNotMakeExceptionCapableTrait;

    /* @since [*next-version*] */
    use StringTranslatorConsumingTrait;

    /* @since [*next-version*] */
    use StringTranslatorAwareTrait;

    /**
     * PlaceholderTemplateFactory constructor.
     *
     * @since [*next-version*]
     *
     * @param Stringable|string|int|float|bool $productClassname Name of the product class.
     * @param Stringable|string|int|float|bool $tokenStart       The delimiter that represent the start of a token.
     * @param Stringable|string|int|float|bool $tokenEnd         The delimiter that represents the end of a token.
     * @param Stringable|string|int|float|bool $defaultValue     The value to use if a token value cannot be resolved.
     *
     * @throws InvalidArgumentException If one of the arguments has the wrong type.
     */
    public function __construct($productClassname, $tokenStart, $tokenEnd, $defaultValue)
    {
        $this->_setTokenStart($tokenStart);
        $this->_setTokenEnd($tokenEnd);
        $this->_setDefaultPlaceholderValue($defaultValue);
        $this->productClassname = $this->_normalizeStringable($productClassname);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function make($config = null)
    {
        try {
            return $this->_make($config);
        } catch (RootException $e) {
            throw $this->_createCouldNotMakeException($this->__('Could not create placeholder template'), null, $e, $this, $config);
        }
    }

    /**
     * Retrieves the class name of the product that this instance creates.
     *
     * @since [*next-version*]
     *
     * @return Stringable|string|int|float|bool class name.
     */
    protected function _getProductClassname()
    {
        return $this->productClassname;
    }
}
