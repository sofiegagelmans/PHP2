<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

$public_access=true;
require_once "autoload.php";

SaveFormData( $container->getMessageService(),
                            $container->getDBManager(),
                            $container->getValidator(),
                            $app_root
                        );

function SaveFormData( MessageService $ms, DBManager $dbm, Validator $val, $app_root )
{
    if ( $_SERVER['REQUEST_METHOD'] == "POST" )
    {
        if ( ! isset( $_POST['btnOpslaan'] ) )
        {
            if ( isset($_POST['aftercancel'] )) { GoToPage( $app_root, $_POST['aftercancel'] ); exit; }
            else { GoHome( $app_root ); exit; }
        }

        //controle CSRF token
        if ( ! key_exists("csrf", $_POST)) die("Missing CSRF");
        if ( ! hash_equals( $_POST['csrf'], $_SESSION['lastest_csrf'] ) ) die("Problem with CSRF");

        $_SESSION['lastest_csrf'] = "";

        //sanitization
        $_POST = StripSpaces($_POST);
        $_POST = ConvertSpecialChars($_POST);

        $table = $pkey = $update = $insert = $where = $str_keys_values = "";

        //get important metadata
        if ( ! key_exists("table", $_POST)) die("Missing table");
        if ( ! key_exists("pkey", $_POST)) die("Missing pkey");

        $formname = $_POST['formname'];
        $table = $_POST['table'];
        $pkey = $_POST['pkey'];

        //validation
        $sending_form_uri = $_SERVER['HTTP_REFERER'];
        $val->CompareWithDatabase( $table, $pkey );

        //Validaties voor het registratieformulier
        if ( $formname == "register" )
        {
            $val->ValidateUsrPassword( $_POST['usr_password'] );
            $val->CheckUniqueUsrEmail( $_POST['usr_email'] );
        }

        if ( $formname == "profiel" OR $formname == "register" )
        {
            $val->ValidateUsrEmail( $_POST['usr_email'] );
        }

        //terugkeren naar afzender als er een fout is
        if ( $ms->CountNewErrors() > 0 OR $ms->CountNewInputErrors() )
        {
            $_SESSION['OLD_POST'] = $_POST;
            header( "Location: " . $sending_form_uri ); exit();
        }

        //insert or update?
        if ( $_POST["$pkey"] > 0 ) $update = true;
        else $insert = true;

        if ( $update ) $sql = "UPDATE $table SET ";
        if ( $insert ) $sql = "INSERT INTO $table SET ";

        //make key-value string part of SQL statement
        $keys_values = [];

        foreach ( $_POST as $field => $value )
        {
            //skip non-data fields
            if ( in_array( $field, [ 'btnOpslaan', 'formname', 'table', 'pkey',
                                                'afterinsert', 'afterupdate', 'aftercancel', 'csrf' ] ) ) continue;

            //handle primary key field
            if ( $field == $pkey )
            {
                if ( $update ) $where = " WHERE $pkey = $value ";
                continue;
            }

            if ( $field == "usr_password" ) //encrypt usr_password
            {
                $value = password_hash( $value, PASSWORD_BCRYPT );
                $keys_values[] = " $field = '$value' " ;

                $ms->AddMessage("info", "Bedankt voor uw registratie");
            }
            else //all other data-fields
            {
                $keys_values[] = " $field = '$value' " ;
            }

        }

        $str_keys_values = implode(" , ", $keys_values );

        //extend SQL with key-values
        $sql .= $str_keys_values;

        //extend SQL with WHERE
        $sql .= $where;

        //run SQL
        $result = $dbm->ExecuteSQL( $sql );

        //output if not redirected
        print $sql ;
        print "<br>";
        print $result->rowCount() . " records affected";

        //redirect after insert or update
        if ( $insert AND $_POST["afterinsert"] > "" ) header("Location: ../" . $_POST["afterinsert"] );
        if ( $update AND $_POST["afterupdate"] > "" ) header("Location: ../" . $_POST["afterupdate"] );
    }
}
