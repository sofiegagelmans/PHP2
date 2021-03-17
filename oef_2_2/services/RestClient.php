<?php
class RESTClient
{
    private $authentication;
    private $curl;

    function __construct( array $authentication = null )
    {
        $this->authentication = $authentication;
    }

    function CurlInit( $url )
    {
        $this->curl = curl_init( $url );
        curl_setopt( $this->curl, CURLOPT_RETURNTRANSFER, true );

        if ( $this->authentication ) {
            curl_setopt($this->curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($this->curl, CURLOPT_USERPWD,
                $this->authentication['username'] . ":" .
                $this->authentication['password']
            );
        }
    }

    function CurlExec()
    {
        $curl_response = curl_exec( $this->curl );

        if ( $curl_response === false )
        {
            $info = curl_getinfo( $this->curl );
            die('error occured during curl exec. Additional info: ' . var_export( $info ) );
        }
        curl_close( $this->curl );

        return $curl_response;
    }

}
?>