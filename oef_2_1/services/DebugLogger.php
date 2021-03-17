<?php

class DebugLogger implements LoggerInterface
{
    private $fp;
    private $logfile;

    public function __construct( $logfile )
    {
        $this->logfile = $logfile;
        $this->fp = fopen( $this->logfile, "w+"); //w+: bij elke request het logbestand leegmaken en weer openen
    }

    public function Log( $msg )
    {
        fwrite( $this->fp, date("Y-m-d H:i:s") . ": " . $msg . "\r\n" );
    }

}