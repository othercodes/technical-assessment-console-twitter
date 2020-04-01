<?php

namespace Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Shared\Application\Contracts;

/**
 * Interface Formatter
 * @package Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Shared\Application\Contracts
 */
interface Formatter
{
    /**
     * Format a string with given context.
     * @param string $format
     * @param array $context
     * @return string
     */
    public function format(string $format, array $context = []): string;
}
