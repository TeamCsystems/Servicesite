<?php

$terminalNumber = '154333';
// $terminalNumber = '100';
$userName = '734sxZiAXhnMhNFPnDFm';
$sumToBill = '1.00';  // The amount to be billed
$successRedirectUrl = 'https://kaltime.com/success';
$errorRedirectUrl = 'https://kaltime.com/error';
$indicatorUrl = 'https://kaltime.com/indicator';

// Prepare the request URL
$requestUrl = "https://secure.cardcom.solutions/api/v11/LowProfile/Create";
$requestParams = [
    "TerminalNumber" => 154333,
    "ApiName" => "734sxZiAXhnMhNFPnDFm",
    "ReturnValue" => "Z12332X",
    "Amount" => 10.5,
    "SuccessRedirectUrl" => "https://www.google.com",
    "FailedRedirectUrl" => "https://www.yahoo.com",
    "WebHookUrl" => "https://www.mysite.com/CardComLPWebHook",
    "Document" => [
        "To" => "test client",
        "Email" => "test@testDomain.com",
        "Products" => [
            [
                "Description" => "my item to sell",
                "UnitCost" => 10.5
            ]
        ]
    ]
];


// Initialize cURL session
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $requestUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($requestParams));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute cURL session and close it
$response = curl_exec($ch);
curl_close($ch);

// Parse the response
                                                                                              

$data = json_decode($response, TRUE);
// parse_str($response, $responseArray);
// print_r($data);
// echo $data['LowProfileId'];
$lowProfileCode = $data['LowProfileId'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Page</title>
</head>
<body>
    <iframe src="https://secure.cardcom.solutions/External/lowProfileClearing/<?php echo $terminalNumber; ?>.aspx?LowProfileCode=<?php echo $lowProfileCode; ?>" width="100%" height="600px" frameborder="0">
        Your browser does not support iframes.
    </iframe>
</body>
</html>
