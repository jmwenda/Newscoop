<?php
// TODO: no access rights are checked during development; will be added.

require_once($GLOBALS['g_campsiteDir']."/classes/Input.php");
require_once($GLOBALS['g_campsiteDir'].'/classes/GeoNames.php');

/*
if (!SecurityToken::isValid()) {
    //camp_html_display_error(getGS('Invalid security token!'));
    $sec_err_array = array("status" => "403", "description" => getGS('Invalid security token!'));

    echo json_encode($sec_err_array);
    exit;
}
*/

/*
if (!$g_user->hasPermission('POI manage')) {
    camp_html_display_error(getGS("You do not have the right to manage POIs."));
    exit();
}
*/

$found_cities = '{"status":"200","cities":[';
$security_problem = '["status":"403","description":"Invalid security token!"]';
$unknown_request = '["status":"404","description":"Unknown request!"]';


// take input parameters, ask the search class to find the cities, returnes json
if (Input::Get('search')) {

    $found_list = array();

    $longitude = Input::Get('f_longitude', 'string', "", true);
    $latitude = Input::Get('f_latitude', 'string', "", true);
    if ((0 != strlen($longitude)) && (0 != strlen($latitude))) {

        $found_list = Geo_Names::FindCitiesByPosition($longitude, $latitude);
        //echo $found_list;
        //return;
    }
    else
    {
        $city_name = Input::Get('f_city_name', 'string', "", true);
        if (0 == strlen($city_name)) {
            echo $found_cities . ']}';
            exit();
        }

        $city_name = str_replace("%2B", "+", $city_name);
        $city_name = str_replace("%2F", "/", $city_name);
        $city_name = base64_decode($city_name);
        $country_code = Input::Get('f_country_code', 'string', "", true);

        $found_list = Geo_Names::FindCitiesByName($city_name, $country_code);
    }


    $first_item = true;
    foreach ($found_list as $one_city) {
        if (!$first_item) {
            $found_cities .= ',';
        }
        else {
            $first_item = false;
        }

        $city_name = implode('\\"', explode('"', $one_city['name']));

        $one_element = '{';
        $one_element .= '"name":"' . $city_name . '",';
        $one_element .= '"country":"' . $one_city['country'] . '",';
        $one_element .= '"population":"' . $one_city['population'] . '",';
        $one_element .= '"latitude":"' . $one_city['latitude'] . '",';
        $one_element .= '"longitude":"' . $one_city['longitude'] . '"';
        $one_element .= '}';

        $found_cities .= $one_element;
    }

    $found_cities .= ']}';

    echo $found_cities;
    exit();
}

echo $unknown_request;
exit();

?>
