<?php
namespace Stanford\FARewear;
/** @var \Stanford\FARewear\FARewear $module */

header("Access-Control-Allow-Origin: *");

if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
    // Handle JSON payload
    $postData = file_get_contents('php://input');
    $requestData = json_decode($postData, true);
} else if ($_SERVER['CONTENT_TYPE'] === 'application/x-www-form-urlencoded') {
    // Handle form data
    $requestData = $_POST;
} else {
    if(!empty($_GET['show'])){
        $api_link = $module->getUrl("/pages/save.php",true, true);
        exit("API Endpoint URL : " . $api_link . "=1");
    }

    // handle other content types or respond with error
    http_response_code(400);
    echo "Unsupported content type. Please send data as JSON or form data.";
    exit;
}

if($requestData !== null) {
    http_response_code(200);
    $response = $module->parseSave($requestData);
    exit($response);
}
?>
