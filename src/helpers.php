<?php
/*
 * Copyright (c) Rasso Hilber
 * https://rassohilber.com
 */

use Hirasso\Attr\Attr;


if (!function_exists('attr')) {

    /**
     * Convert an array of conditional attributes into a string of HTMLElement attributes.
     */
    function attr(
        array $_attrs = [],
        bool $debug = false
    ) {
        return Attr::attr(...func_get_args());
    }
}


if (!function_exists('jsonInAttr')) {

    /**
     * Convert an object or array to JSON that's safe to be used inside a HTMLElement attribute
     */
    function jsonInAttr(
        array $_attrs = [],
        bool $debug = false
    ) {
        return Attr::jsonInAttr(...func_get_args());
    }
}
