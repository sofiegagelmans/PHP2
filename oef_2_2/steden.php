<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

require_once "lib/autoload.php";

PrintHead();
PrintJumbo( $title = "Leuke plekken in Europa" , $subtitle = "Tips voor citytrips voor vrolijke vakantiegangers!" );
PrintNavbar();
?>

<div class="container">
    <div class="row">

<?php
    //toon messages als er zijn
    $container->getMessageService()->ShowErrors();
    $container->getMessageService()->ShowInfos();

    //export button
    $output ="";
    $output .= "<a style='margin-left: 10px' class='btn btn-info' role='button' href='export/export_images.php'>Export CSV</a>";
    $output .= "<div><br></div>";

    //get data
    $data = $container->getDBManager()->GetData( "select * from images" );

    //get template
    $template = file_get_contents("templates/column.html");

    //get weather data
    $restClient = new RESTClient($authentication = null);

    foreach ($data as $key => $row) {
        $city = $row['img_weather_location'];
        $url = 'http://api.openweathermap.org/data/2.5/weather?q=' . $city . $apiKey;

        $restClient->CurlInit($url);
        $response = json_decode($restClient->CurlExec());

        $row['description'] = $response->weather[0]->description;
        $row['temp'] = round($response->main->temp);
        $row['humidity'] = $response->main->humidity;
        $row['weather_icon'] = '<img src="http://openweathermap.org/img/w/' .$response->weather[0]->icon . '.png" >';

        $data[$key] = $row;
    }

    //merge
    $output .= MergeViewWithData( $template, $data );
    print $output;
?>

    </div>
</div>

</body>
</html>