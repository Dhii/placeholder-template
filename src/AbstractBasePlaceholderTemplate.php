<?php

namespace Dhii\Output;

use ArrayAccess;
use Dhii\Util\Normalization\NormalizeStringableCapableTrait;
use Exception as RootException;
use Dhii\Data\Container\ContainerInterface as BaseContainerInterface;
use Dhii\Data\Container\ContainerGetCapableTrait;
use Dhii\Util\Normalization\NormalizeStringCapableTrait;
use stdClass;

/**
 * Common functionality for placeholder template implementations.
 *
 * @since [*next-version*]
 */
abstract class AbstractBasePlaceholderTemplate implements TemplateInterface
{
    /* @since [*next-version*] */
    use ReplaceTokensCapableTrait;

    /* @since [*next-version*] */
    use ContainerGetCapableTrait;

    /* @since [*next-version*] */
    use TokenStartAwareTrait;

    /* @since [*next-version*] */
    use TokenEndAwareTrait;

    /* @since [*next-version*] */
    use PlaceholderTemplateAwareCapableTrait;

    /* @since [*next-version*] */
    use DefaultPlaceholderValueAwareTrait;

    /* @since [*next-version*] */
    use NormalizeStringCapableTrait;

    /* @since [*next-version*] */
    use NormalizeStringableCapableTrait;

    /* @since [*next-version*] */
    use CreateCouldNotRenderExceptionCapableTrait;

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function render($context = null)
    {
        try {
            return $this->_render($context);
        } catch (RootException $e) {
            throw $this->_createCouldNotRenderException($this->__('Could not render placeholder template'), null, $e, $this);
        }
    }

    /**
     * Renders this template with context.
     *
     * @since [*next-version*]
     *
     * @param BaseContainerInterface|array|ArrayAccess|stdClass|null $context The context, which contains values for this tempalte to render with.
     *
     * @return string The result of rendering.
     */
    protected function _render($context = null)
    {
        if (is_null($context)) {
            $context = [];
        }

        return $this->_replaceTokens($this->_getPlaceholderTemplate(), $context, $this->_getTokenStart(), $this->_getTokenEnd(), $this->_getDefaultPlaceholderValue());
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
    protected function _normalizeTokenKey($key)
    {
        return $this->_normalizeKey($key);
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
}
