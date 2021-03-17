<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

$public_access = false;
require_once "lib/autoload.php";

PrintHead();
PrintJumbo( $title = "Profiel", $subtitle = "" );
PrintNavbar();
?>

<div class="container">
    <div class="row">

        <?php
            //get data
            //$data = GetData( "select * from user where usr_id=" . $_SESSION['user']['usr_id'] );
            $data = GetData( "select * from user where usr_id=" . $_SESSION['user']->getId() );

            /*
            $user = $_SESSION['user'];
            $data = [];
            $data[0] = [
                    "usr_id" => $user->getId(),
                    "usr_voornaam" => $user->getVoornaam(),
                    "usr_naam" => $user->getNaam(),
                    "usr_email" => $user->getEmail(),
                    "usr_telefoon" => $user->getTelefoon(),
                ];
            */

            //get template
            $output = file_get_contents("templates/profiel.html");

            //add extra elements
            $extra_elements['csrf_token'] = GenerateCSRF( "profiel.php"  );

            //merge
            $output = MergeViewWithData( $output, $data );
            $output = MergeViewWithExtraElements( $output, $extra_elements );
            $output = MergeViewWithErrors( $output, $errors );
            $output = RemoveEmptyErrorTags( $output, $data );

            print $output;
        ?>

    </div>
</div>

</body>
</html>