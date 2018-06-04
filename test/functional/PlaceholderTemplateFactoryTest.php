<?php

namespace Dhii\Output\UnitTest;

use Dhii\Output\PlaceholderTemplateFactory as TestSubject;
use Dhii\Output\Exception\CouldNotRenderExceptionInterface;
use Dhii\Output\TemplateFactoryInterface;
use Xpmock\TestCase;
use Exception as RootException;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_MockObject_MockBuilder as MockBuilder;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class PlaceholderTemplateFactoryTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Output\PlaceholderTemplateFactory';

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
     * @param array $methods        The methods to mock.
     * @param array $constructorArs Arguments to pass to the subject's constructor.
     *
     * @return MockObject|TestSubject The new instance.
     */
    public function createInstance($methods = [], $constructorArs = [])
    {
        is_array($methods) && $methods = $this->mergeValues($methods, [
        ]);

        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
            ->setMethods($methods)
            ->setConstructorArgs($constructorArs)
            ->getMock();

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
     * Tests whether `make()` works as expected.
     *
     * @since [*next-version*]
     */
    public function testProtectedRenderContext()
    {
        $template = uniqid('template');
        $className = 'Dhii\Output\PlaceholderTemplate';
        $tStart = '${';
        $tEnd = '}';
        $default = '***';
        $config = [
            TemplateFactoryInterface::K_TEMPLATE => $template,
        ];
        $subject = $this->createInstance(null, [$className, $tStart, $tEnd, $default]);
        $_subject = $this->reflect($subject);

        $result = $subject->make($config);
        $this->assertInstanceOf('Dhii\Output\PlaceholderTemplate', $result, 'Wrong product created');
    }
}
