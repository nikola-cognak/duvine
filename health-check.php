<?php

if ( file_exists( 'wp-config.php' ) ) {
    require_once 'wp-config.php';

    return http_response_code(200);
}

return http_response_code(500);