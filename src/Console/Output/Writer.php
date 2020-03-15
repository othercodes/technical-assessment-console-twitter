<?php

namespace Lookiero\Hiring\ConsoleTwitter\Console\Output;

use Lookiero\Hiring\ConsoleTwitter\Application\Contracts\Output as OutputContract;

/**
 * Class Output
 * @package Lookiero\Hiring\ConsoleTwitter\Console\Output
 */
class Writer implements OutputContract
{
    /**
     * Write data into the output stream. Instead of implementing a complex Output handle use
     * the default echo/print statement to send the output to the output stream, KISS you know...
     * @param string $data
     * @return bool
     */
    public function write(string $data): bool
    {
        print($data);
        
        return true;
    }
}