<?php

interface LoggerInterface
{
    public function __construct( $logfile );

    public function Log( $msg );
}