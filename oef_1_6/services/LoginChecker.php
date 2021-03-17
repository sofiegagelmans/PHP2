<?php

class LoginChecker
{
    private $ms;
    private $dbm;

    public function __construct(MessageService $ms, DBManager $dbm)
    {
        $this->ms = $ms;
        $this->dbm = $dbm;
    }

    function LoginCheck()
    {
        if ( $_SERVER['REQUEST_METHOD'] == "POST" )
        {
            //controle CSRF token
            if ( ! key_exists("csrf", $_POST)) die("Missing CSRF");
            if ( ! hash_equals( $_POST['csrf'], $_SESSION['lastest_csrf'] ) ) die("Problem with CSRF");

            $_SESSION['lastest_csrf'] = "";

            //sanitization
            $_POST = StripSpaces($_POST);
            $_POST = ConvertSpecialChars($_POST);

            //validation
            $sending_form_uri = $_SERVER['HTTP_REFERER'];

            //Validaties voor het loginformulier
            if ( true )
            {
                if ( ! key_exists("usr_email", $_POST ) OR strlen($_POST['usr_email']) < 5 )
                {
                    $this->ms->AddMessage("input_errors", "Het wachtwoord is niet correct ingevuld", "usr_password");
                }
                if ( ! key_exists("usr_password", $_POST ) OR strlen($_POST['usr_password']) < 8 )
                {
                    $this->ms->AddMessage("input_errors", "Het wachtwoord is niet correct ingevuld", "usr_password");
                }
            }

            //terugkeren naar afzender als er een fout is
            if ( $this->ms->CountInputErrors() > 0 OR $this->ms->CountErrors() > 0 )
            {
                $_SESSION['OLD_POST'] = $_POST;
                header( "Location: " . $sending_form_uri ); exit();
            }

            //search user in database
            $email = $_POST['usr_email'];
            $ww = $_POST['usr_password'];

            $sql = "SELECT * FROM user WHERE usr_email='$email' ";
            $data = $this->dbm->GetData($sql);

            if ( count($data) > 0 )
            {
                foreach ( $data as $row )
                {
                    if ( password_verify( $ww, $row['usr_password'] ) )
                    {
                        $user = new User();
                        $user->setId( $row['usr_id'] );
                        $user->setVoornaam( $row['usr_voornaam'] );
                        $user->setNaam( $row['usr_naam'] );
                        $user->setEmail( $row['usr_email'] );
                        $user->setTelefoon( $row['usr_telefoon'] );

                        return $user;
                    }
                }
            }

            return null;
        }
    }
}