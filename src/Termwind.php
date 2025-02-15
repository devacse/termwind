<?php

declare(strict_types=1);

namespace Termwind;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Termwind\Components\Element;
use Termwind\Exceptions\InvalidChild;

/**
 * @internal
 */
final class Termwind
{
    /**
     * The implementation of the output.
     */
    private static OutputInterface|null $renderer;

    /**
     * Sets the renderer implementation.
     */
    public static function renderUsing(OutputInterface|null $renderer): void
    {
        self::$renderer = $renderer ?? new ConsoleOutput();
    }

    /**
     * Creates a div element instance.
     *
     * @param  array<int, Element|string>|string  $content
     * @param  array<string, mixed>  $properties
     */
    public static function div(array|string $content = '', string $styles = '', array $properties = []): Components\Div
    {
        $content = implode('', array_map(
            fn ($element) => (string) $element, is_array($content) ? $content : [$content]
        ));

        return Components\Div::fromStyles(
            self::getRenderer(), $content, $styles, $properties
        );
    }

    /**
     * Creates a span element instance with the given style.
     *
     * @param  array<int, Element|string>|string  $content
     * @param  array<string, mixed>  $properties
     */
    public static function span(array|string $content = '', string $styles = '', array $properties = []): Components\Span
    {
        $content = implode('', array_map(
            fn ($element) => (string) $element, is_array($content) ? $content : [$content]
        ));

        return Components\Span::fromStyles(
            self::getRenderer(), $content, $styles, $properties
        );
    }

    /**
     * Creates an anchor element instance with the given style.
     *
     * @param  array<int, Element|string>|string  $content
     * @param  array<string, mixed>  $properties
     */
    public static function anchor(array|string $content = '', string $styles = '', array $properties = []): Components\Anchor
    {
        $content = implode('', array_map(
            fn ($element) => (string) $element, is_array($content) ? $content : [$content]
        ));

        return Components\Anchor::fromStyles(
            self::getRenderer(), $content, $styles, $properties
        );
    }

    /**
     * Creates an unordered list instance.
     *
     * @param  array<int, string|Element>  $content
     * @param  array<string, mixed>  $properties
     */
    public static function ul(array $content = [], string $styles = '', array $properties = []): Components\Ul
    {
        $text = implode('', array_map(function ($element): string {
            if (! $element instanceof Components\Li) {
                throw new InvalidChild('Unordered lists only accept `li` as child');
            }

            return (string) $element->prepend('• ');
        }, $content));

        return Components\Ul::fromStyles(
            self::getRenderer(), $text, $styles, $properties
        );
    }

    /**
     * Creates an ordered list instance.
     *
     * @param  array<int, string|Element>  $content
     * @param  array<string, mixed>  $properties
     */
    public static function ol(array $content = [], string $styles = '', array $properties = []): Components\Ol
    {
        $index = 0;
        $text = implode('', array_map(function ($element) use (&$index): string {
            if (! $element instanceof Components\Li) {
                throw new InvalidChild('Ordered lists only accept `li` as child');
            }

            return (string) $element->prepend(sprintf('%s. ', ++$index));
        }, $content));

        return Components\Ol::fromStyles(
            self::getRenderer(), $text, $styles, $properties
        );
    }

    /**
     * Creates a list item instance.
     *
     * @param  array<int, Element|string>|string  $content
     * @param  array<string, mixed>  $properties
     */
    public static function li(array|string $content = '', string $styles = '', array $properties = []): Components\Li
    {
        $content = implode('', array_map(
            fn ($element) => (string) $element, is_array($content) ? $content : [$content]
        ));

        return Components\Li::fromStyles(
            self::getRenderer(), $content, $styles, $properties
        );
    }

    /**
     * Creates a description list instance.
     *
     * @param  array<int, string|Element>  $content
     * @param  array<string, mixed>  $properties
     */
    public static function dl(array $content = [], string $styles = '', array $properties = []): Components\Dl
    {
        $text = implode('', array_map(function ($element): string {
            if (! $element instanceof Components\Dt && ! $element instanceof Components\Dd) {
                throw new InvalidChild('Description lists only accept `dt` and `dd` as children');
            }

            return (string) $element;
        }, $content));

        return Components\Dl::fromStyles(
            self::getRenderer(), $text, $styles, $properties
        );
    }

    /**
     * Creates a description term instance.
     *
     * @param  array<int, Element|string>|string  $content
     * @param  array<string, mixed>  $properties
     */
    public static function dt(array|string $content = '', string $styles = '', array $properties = []): Components\Dt
    {
        $content = implode('', array_map(
            fn ($element) => (string) $element, is_array($content) ? $content : [$content]
        ));

        return Components\Dt::fromStyles(
            self::getRenderer(), $content, $styles, $properties
        );
    }

    /**
     * Creates a description details instance.
     *
     * @param  array<int, Element|string>|string  $content
     * @param  array<string, mixed>  $properties
     */
    public static function dd(array|string $content = '', string $styles = '', array $properties = []): Components\Dd
    {
        $content = implode('', array_map(
            fn ($element) => (string) $element, is_array($content) ? $content : [$content]
        ));

        return Components\Dd::fromStyles(
            self::getRenderer(), $content, $styles, $properties
        );
    }

    /**
     * Creates a horizontal rule instance.
     *
     * @param  array<string, mixed>  $properties
     */
    public static function hr(string $styles = '', array $properties = []): Components\Hr
    {
        $width = terminal()->width();

        return Components\Hr::fromStyles(
            self::getRenderer(), str_repeat(html_entity_decode('&mdash;'), $width), $styles, $properties
        );
    }

    /**
     * Creates an break line element instance.
     */
    public static function breakLine(): Components\BreakLine
    {
        return Components\BreakLine::fromStyles(
            self::getRenderer(), '', '',
        );
    }

    /**
     * Gets the current renderer instance.
     */
    private static function getRenderer(): OutputInterface
    {
        return self::$renderer ??= new ConsoleOutput();
    }
}
