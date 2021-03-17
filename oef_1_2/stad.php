<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

require_once "lib/autoload.php";

PrintHead();
PrintJumbo( $title = "Stad OO style");
?>

<div class="container">
    <div class="row">

        <?php
        if ( ! isset( $_GET['img_id']) ) die("Geen img_id opgegeven");
        if ( ! is_numeric( $_GET['img_id']) ) die("Ongeldig argument " . $_GET['img_id'] . " opgegeven");

        $rows = GetData( "select * from images where img_id=" . $_GET['img_id'] );

        if ( $rows )
        {
            $row = $rows[0];

            //data to object
            $city = new city();
            $city->setId( $row['img_id'] );
            $city->setFilename( $row['img_filename'] );
            $city->setTitle( $row['img_title'] );
            $city->setWidth( $row['img_width'] );
            $city->setHeight( $row['img_height'] );
            $city->setPublished( $row['img_published'] );
            $city->setLanId( $row['img_lan_id'] );
            $city->setDate( $row['img_date'] );

            //get template
            $template = file_get_contents("templates/column_full.html");

            //merge
            $output = $template;

            /*
            $output = str_replace( "@img_id@", $city->getId(), $output );
            $output = str_replace( "@img_filename@", $city->getFilename(), $output );
            $output = str_replace( "@img_title@", $city->getTitle(), $output );
            $output = str_replace( "@img_width@", $city->getWidth(), $output );
            $output = str_replace( "@img_height@", $city->getHeight(), $output );
            $output = str_replace( "@img_published@", $city->getPublished(), $output );
            $output = str_replace( "@img_lan_id@", $city->getLanId(), $output );
            $output = str_replace( "@img_date@", $city->getDate(), $output );
            */

            //object to array
            $properties = $city->toArray2();
            $properties['title'] = $city->getTitle();

            foreach( $properties as $key => $value )
            {
                $output = str_replace( "@img_$key@", $value, $output );
            }

            //output
            print $output;
        }

        ?>

    </div>
</div>

</body>
</html>