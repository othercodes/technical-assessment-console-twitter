<?php

namespace Lookiero\Hiring\ConsoleTwitter\Applications\Console;

use Lookiero\Hiring\ConsoleTwitter\Shared\Application\Contracts\Input as InputContract;

/**
 * Class InputArguments
 * Parse the input arguments following the conventions:
 *
 *  Commands always start with the user’s name.
 *  posting:    usernameA -> message
 *  reading:    usernameA
 *  following:  usernameA follows usernameB
 *  wall:       usernameA wall
 *
 * The commands are always composed between 1 and 3 tokens
 *  1. username (required).
 *  2. command (optional) if not provided assume is "read".
 *  3. args (optional) depends on the command.
 *
 * @package Lookiero\Hiring\ConsoleTwitter\Applications\Console
 */
class InputArguments implements InputContract
{
    /**
     * The arguments.
     * @var array
     */
    private $arguments = [];

    /**
     * Arguments constructor. Get the raw input data and parse it into
     * a usable array of arguments:
     *
     *  ctrl => the command to execute.
     *  args => the arguments for the command.
     */
    public function __construct()
    {
        $input = array_values(array_filter(explode(' ', $this->read()), function ($item) {
            return !empty(trim($item));
        }));

        $this->arguments['ctrl'] = isset($input[1]) ? $input[1] : 'read';
        $this->arguments['args'] = [
            isset($input[0]) ? $input[0] : null,
            isset($input[2]) ? implode(' ', array_slice($input, 2)) : []
        ];
    }

    /**
     * Read the input stream. I'm directly using readline() as input but it can be
     * easily replaced for a former low level Input handler.
     * @return string
     */
    public function read(): string
    {
        return readline("> ");
    }

    /**
     * Return the arguments
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
}