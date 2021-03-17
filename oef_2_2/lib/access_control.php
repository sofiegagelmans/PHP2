<?php

if ( ! isset($public_access) ) $public_access = false;

CheckAccess( $app_root, $public_access );

function CheckAccess( $app_root, $public_access )
{
    if ( ! $public_access AND ! isset($_SESSION['user']) )
    {
        GoToNoAccess( $app_root );
    }
}



