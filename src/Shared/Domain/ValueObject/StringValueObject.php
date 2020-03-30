<?php

namespace Lookiero\Hiring\ConsoleTwitter\Shared\Domain\ValueObject;

/**
 * Class StringValueObject
 * @package Lookiero\Hiring\ConsoleTwitter\Shared\Domain\ValueObject
 */
abstract class StringValueObject
{
    /**
     * The string value
     * @var string
     */
    protected $value;

    /**
     * StringValueObject constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Get the string value.
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * @param StringValueObject $valueObject
     * @return bool
     */
    public function equals(StringValueObject $valueObject): bool
    {
        return $this->value() === $valueObject->value();
    }

    /**
     * To string method.. string is a string...
     * @return string
     */
    public function __toString(): string
    {
        return $this->value();
    }
}
