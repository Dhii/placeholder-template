<?php

namespace Dhii\Output\UnitTest;

use Dhii\Output\AbstractBasePlaceholderTemplate as TestSubject;
use Dhii\Output\Exception\CouldNotRenderExceptionInterface;
use stdClass;
use Xpmock\TestCase;
use Exception as RootException;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_MockObject_MockBuilder as MockBuilder;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class AbstractBasePlaceholderTemplateTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Output\AbstractBasePlaceholderTemplate';

    /**
     * Class name of Could Not Render exception.
     *
     * @since [*next-version*]
     */
    const COULD_NOT_RENDER_EXCEPTION_CLASSNAME = 'Dhii\Output\Exception\CouldNotRenderExceptionInterface';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param array $methods The methods to mock.
     *
     * @return MockObject|TestSubject The new instance.
     */
    public function createInstance($methods = [])
    {
        is_array($methods) && $methods = $this->mergeValues($methods, [
            '__',
        ]);

        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
            ->setMethods($methods)
            ->getMockForAbstractClass();

        $mock->method('__')
            ->will($this->returnArgument(0));

        return $mock;
    }

    /**
     * Merges the values of two arrays.
     *
     * The resulting product will be a numeric array where the values of both inputs are present, without duplicates.
     *
     * @since [*next-version*]
     *
     * @param array $destination The base array.
     * @param array $source      The array with more keys.
     *
     * @return array The array which contains unique values
     */
    public function mergeValues($destination, $source)
    {
        return array_keys(array_merge(array_flip($destination), array_flip($source)));
    }

    /**
     * Creates a mock that both extends a class and implements interfaces.
     *
     * This is particularly useful for cases where the mock is based on an
     * internal class, such as in the case with exceptions. Helps to avoid
     * writing hard-coded stubs.
     *
     * @since [*next-version*]
     *
     * @param string   $className      Name of the class for the mock to extend.
     * @param string[] $interfaceNames Names of the interfaces for the mock to implement.
     *
     * @return MockBuilder The builder for a mock of an object that extends and implements
     *                     the specified class and interfaces.
     */
    public function mockClassAndInterfaces($className, $interfaceNames = [])
    {
        $paddingClassName = uniqid($className);
        $definition = vsprintf('abstract class %1$s extends %2$s implements %3$s {}', [
            $paddingClassName,
            $className,
            implode(', ', $interfaceNames),
        ]);
        eval($definition);

        return $this->getMockBuilder($paddingClassName);
    }

    /**
     * Creates a mock that uses traits.
     *
     * This is particularly useful for testing integration between multiple traits.
     *
     * @since [*next-version*]
     *
     * @param string[] $traitNames Names of the traits for the mock to use.
     *
     * @return MockBuilder The builder for a mock of an object that uses the traits.
     */
    public function mockTraits($traitNames = [])
    {
        $paddingClassName = uniqid('Traits');
        $definition = vsprintf('abstract class %1$s {%2$s}', [
            $paddingClassName,
            implode(
                ' ',
                array_map(
                    function ($v) {
                        return vsprintf('use %1$s;', [$v]);
                    },
                    $traitNames)),
        ]);
        var_dump($definition);
        eval($definition);

        return $this->getMockBuilder($paddingClassName);
    }

    /**
     * Creates a new exception.
     *
     * @since [*next-version*]
     *
     * @param string $message The exception message.
     *
     * @return RootException|MockObject The new exception.
     */
    public function createException($message = '')
    {
        $mock = $this->getMockBuilder('Exception')
            ->setConstructorArgs([$message])
            ->getMock();

        return $mock;
    }

    /**
     * Creates a new Could Not Render exception.
     *
     * @since [*next-version*]
     *
     * @param string $message The error message, if any.
     *
     * @return MockObject|CouldNotRenderExceptionInterface The new exception.
     */
    public function createCouldNotRenderException($message = '')
    {
        $mock = $this->mockClassAndInterfaces('Exception', ['Dhii\Output\Exception\CouldNotRenderExceptionInterface'])
            ->setConstructorArgs([$message])
            ->getMock();

        return $mock;
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInstanceOf(self::TEST_SUBJECT_CLASSNAME, $subject, 'A valid instance of the test subject could not be created.');
    }

    /**
     * Tests whether `_render()` works as expected when given a context.
     *
     * @since [*next-version*]
     */
    public function testProtectedRenderContext()
    {
        $template = uniqid('template');
        $tStart = uniqid('token-start');
        $tEnd = uniqid('token-end');
        $defaultValue = uniqid('default-value');
        $context = new \stdClass();
        $rendered = uniqid('rendered');
        $subject = $this->createInstance(['_getPlaceholderTemplate', '_getTokenStart', '_getTokenEnd', '_getDefaultPlaceholderValue', '_replaceTokens']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_getPlaceholderTemplate')
            ->will($this->returnValue($template));
        $subject->expects($this->exactly(1))
            ->method('_getTokenStart')
            ->will($this->returnValue($tStart));
        $subject->expects($this->exactly(1))
            ->method('_getTokenEnd')
            ->will($this->returnValue($tEnd));
        $subject->expects($this->exactly(1))
            ->method('_getDefaultPlaceholderValue')
            ->will($this->returnValue($defaultValue));
        $subject->expects($this->exactly(1))
            ->method('_replaceTokens')
            ->with($template, $context, $tStart, $tEnd, $defaultValue)
            ->will($this->returnValue($rendered));

        $_subject->_render($context);
    }

    /**
     * Tests whether `_render()` works as expected when given no context.
     *
     * @since [*next-version*]
     */
    public function testProtectedRenderNoContext()
    {
        $template = uniqid('template');
        $tStart = uniqid('token-start');
        $tEnd = uniqid('token-end');
        $defaultValue = uniqid('default-value');
        $context = null;
        $rendered = uniqid('rendered');
        $subject = $this->createInstance(['_getPlaceholderTemplate', '_getTokenStart', '_getTokenEnd', '_getDefaultPlaceholderValue', '_replaceTokens']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_getPlaceholderTemplate')
            ->will($this->returnValue($template));
        $subject->expects($this->exactly(1))
            ->method('_getTokenStart')
            ->will($this->returnValue($tStart));
        $subject->expects($this->exactly(1))
            ->method('_getTokenEnd')
            ->will($this->returnValue($tEnd));
        $subject->expects($this->exactly(1))
            ->method('_getDefaultPlaceholderValue')
            ->will($this->returnValue($defaultValue));
        $subject->expects($this->exactly(1))
            ->method('_replaceTokens')
            ->with($template, [], $tStart, $tEnd, $defaultValue)
            ->will($this->returnValue($rendered));

        $_subject->_render($context);
    }

    /**
     * Tests whether `render()` works as expected.
     *
     * @since [*next-version*]
     */
    public function testRenderSuccess()
    {
        $context = new stdClass();
        $rendered = uniqid('rendered');
        $subject = $this->createInstance(['_render']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_render')
            ->with($context)
            ->will($this->returnValue($rendered));

        $result = $subject->render($context);
        $this->assertEquals($rendered, $result, 'Wrong rendering result');
    }

    /**
     * Tests whether `render()` fails as expected.
     *
     * @since [*next-version*]
     */
    public function testRenderFailure()
    {
        $context = uniqid('context');
        $rootException = $this->createException('Something went wrong while rendering');
        $exception = $this->createCouldNotRenderException('Could not render');
        $subject = $this->createInstance(['_render', '_createCouldNotRenderException']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_render')
            ->with($context)
            ->will($this->throwException($rootException));
        $subject->expects($this->exactly(1))
            ->method('_createCouldNotRenderException')
            ->with()
            ->will($this->returnValue($exception));

        $this->setExpectedException(static::COULD_NOT_RENDER_EXCEPTION_CLASSNAME);
        $result = $subject->render($context);
    }
}
