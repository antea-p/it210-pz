<?php
function is_too_short(mixed $input, int $min): bool
{
    return strlen($input) < $min;
}

function is_too_long(mixed $input, int $max): bool
{
    return strlen($input) > $max;
}
