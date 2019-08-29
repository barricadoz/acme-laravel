<?php

/**
 * Create a slug.
 *
 * @param string $value
 *   The value from which the slug will be created.
 *
 * @return string
 *   The slug.
 */
function slug($value) {
    // Remove any characters that are not underscore, dash, letter, number or space.
    $value = preg_replace('![^' . preg_quote('_') . preg_quote('-') . '\pL\pN\s]+!u', '', mb_strtolower($value));

    // Replace any underscore with a dash.
    $value = preg_replace('![' . preg_quote('_') . '\s]+!u', '-', $value);

    // Remove whitespace.
    return trim($value);
}
