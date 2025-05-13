<?php

/**
 * Get CLC Settings
 *
 * @param $key
 * @param $default_value
 * @return mixed|string
 */
function get_clc_settings($key, $default_value = '' ) {
    global $clc_settings;

    if( empty($clc_settings) ) {
        return $default_value;
    }

    if ( empty( $clc_settings[ $key ] ) ) {
        return $default_value;
    }

    return $clc_settings[ $key ];
}
