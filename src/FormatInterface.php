<?php

namespace Selfphp\DataConverter;

interface FormatInterface
{
    /**
     * Converts input data to a different format.
     *
     * @param mixed $input
     * @return mixed
     */
    public function convert(mixed $input): mixed;
}
