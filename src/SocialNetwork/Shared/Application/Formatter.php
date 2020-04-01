<?php

namespace Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Shared\Application;

use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Shared\Application\Contracts\Formatter as FormatterContract;

/**
 * Class Formatter
 * @package Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Shared\Application
 */
final class Formatter implements FormatterContract
{
    /**
     * Format a string with given context.
     * @param string $format
     * @param array $context
     * @return string
     */
    public function format(string $format, array $context = []): string
    {
        $replaces = [];
        foreach ($context as $placeholder => $replace) {
            $replaces['{' . $placeholder . '}'] = $replace;
        }

        return strtr($format, $replaces);
    }
}
