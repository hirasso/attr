<?php
/*
 * Copyright (c) Rasso Hilber
 * https://rassohilber.com
 */

use Hirasso\Attr\Attr;

/**
 * Make the access to the attr function globally available
 */
if (!function_exists('attr')) {

    function attr(
        array $_attrs = [],
        bool $debug = false
    ) {
        return Attr::attr(...func_get_args());
    }
}
