<?php

function GoToNoAccess( $app_root )
{
    header("Location: " . $app_root . "/no_access.php");
    exit;
}

function GoHome( $app_root )
{
    header("Location: " . $app_root . "/steden.php");
    exit;
}

function GoToPage( $app_root, $page )
{
    header("Location: " . $app_root . "/$page");
    exit;
}

