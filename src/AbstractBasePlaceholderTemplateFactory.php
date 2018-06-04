<?php

namespace Dhii\Output;

use ArrayAccess;
use Dhii\Data\Container\ContainerGetCapableTrait;
use Dhii\Data\Container\CreateContainerExceptionCapableTrait;
use Dhii\Data\Container\CreateNotFoundExceptionCapableTrait;
use Dhii\Exception\OutOfRangeException;
use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface as BaseContainerInterface;
use Dhii\Exception\CreateInvalidArgumentExceptionCapableTrait;
use Dhii\Exception\CreateOutOfRangeExceptionCapableTrait;
use Dhii\Util\Normalization\NormalizeStringCapableTrait;
use Dhii\Util\String\StringableInterface as Stringable;
use stdClass;
use Exception as RootException;

/**
 * Common functionality for placeholder template factories.
 *
 * @since [*next-version*]
 */
abstract class AbstractBasePlaceholderTemplateFactory implements TemplateFactoryInterface
{
    /* @since [*next-version*] */
    use ContainerGetCapableTrait;

    /* @since [*next-version*] */
    use TokenStartAwareTrait;

    /* @since [*next-version*] */
    use TokenEndAwareTrait;

    /* @since [*next-version*] */
    use DefaultPlaceholderValueAwareTrait;

    /* @since [*next-version*] */
    use NormalizeStringCapableTrait;

    /* @since [*next-version*] */
    use CreateNotFoundExceptionCapableTrait;

    /* @since [*next-version*] */
    use CreateContainerExceptionCapableTrait;

    /* @since [*next-version*] */
    use CreateInvalidArgumentExceptionCapableTrait;

    /* @since [*next-version*] */
    use CreateOutOfRangeExceptionCapableTrait;

    /**
     * Creates a new instance of a placeholder template.
     *
     * @since [*next-version*]
     *
     * @param array|ArrayAccess|BaseContainerInterface|stdClass|null $config The make config.
     *
     * @throws InvalidArgumentException    If product classname is not a valid type.
     * @throws OutOfRangeException         If class that the factory is trying to make does not exist.
     * @throws ContainerExceptionInterface If one of the required config keys cannot be accessed.
     * @throws RootException               For other problems.
     *
     * @return TemplateInterface The new template instance.
     */
    protected function _make($config = null)
    {
        $productClassname = $this->_normalizeString($this->_getProductClassname());
        if (!$this->_classExists($productClassname)) {
            throw $this->_createOutOfRangeException($this->__('Class "%1$s" does not exist', [$productClassname]), null, null, $productClassname);
        }

        $template = $this->_containerGet($config, TemplateFactoryInterface::K_TEMPLATE);

        return new $productClassname($template, $this->_getTokenStart(), $this->_getTokenEnd(), $this->_getDefaultPlaceholderValue());
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _normalizeTokenDelimiter($delimiter)
    {
        return $delimiter;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _normalizeKey($key)
    {
        return $key;
    }

    /**
     * Checks whether a class with the specified name exists.
     *
     * @since [*next-version*]
     *
     * @param Stringable|string|int|float|bool $classname The name of the class to check for.
     *
     * @return bool True if the class exists; false otherwise.
     */
    protected function _classExists($classname)
    {
        return class_exists($classname);
    }

    /**
     * Retrieves the class name of the product that this instance creates.
     *
     * @since [*next-version*]
     *
     * @return Stringable|string|int|float|bool class name.
     */
    abstract protected function _getProductClassname();
}
