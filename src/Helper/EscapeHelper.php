<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Helper;

use Exception;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;

class EscapeHelper
{
    /**
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public static function escapeCharacter(string $character, string $input): string
    {
        if (1 !== strlen($character)) {
            throw new InvalidArgumentException(sprintf("Escape character must be of length 1, got '%s'", $character));
        }

        $escapedString = preg_replace_callback(
            sprintf(
                "/\\\*%s/",
                $character
            ),
            function ($match) {
                $match = $match[0];
                if (0 == strlen($match) % 2) {
                    // odd number of escaping slashes + one single character
                    // => even length & character is already escaped
                    return $match;
                }

                return sprintf(
                    "\\%s",
                    $match
                );
            },
            $input
        );
        if (null === $escapedString) {
            // @codeCoverageIgnoreStart
            throw new Exception(preg_last_error_msg());
            // @codeCoverageIgnoreEnd
        }

        return $escapedString;
    }
}
