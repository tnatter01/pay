<?php

require __DIR__ . '/vendor/autoload.php';
require 'config.php';


$manier = $_POST["Manier"];

if($manier == "eenmalig"){
    $result = \Paynl\Transaction::start(array(
        // required
        'amount' => $_POST["prijs"],
        'returnUrl' => 'http://tnatter.nl/pay/resultaat.php',

        // optional
        'currency' => 'EUR',
        'description' => 'Eenmalige donatie Zinloos Geweld tegen Dieren',
        'testmode' => 0,
        'language' => 'NL',
        'enduser' => array(
            'initials' => $_POST["Voornaam"],
            'lastName' => $_POST["Achternaam"],
            'emailAddress' => $_POST["Email"],
        )
    ));
} elseif($manier == "jaarlijks" || $manier=="kwartaal" || $manier=="maandelijks"){
    $result = \Paynl\Transaction::start(array(
        // required
        'amount' => 0.01,
        'returnUrl' => 'http://tnatter.nl/pay/resultaat.php',

        // optional
        'currency' => 'EUR',
        'description' => 'Betaling voor incasso',
        'testmode' => 0,
        'language' => 'NL',
        'extra1' => $_POST["prijs"] * 100,
        'extra2' => $_POST["Manier"],
        'enduser' => array(
            'initials' => $_POST["Voornaam"],
            'lastName' => $_POST["Achternaam"],
            'emailAddress' => $_POST["Email"],
        ),
    ));
}




//// Save this transactionid and link it to your order
$transactionId = $result->getTransactionId();
//
//echo $transactionId . "<br>";
//
//// Redirect the customer to this url to complete the payment
$redirect = $result->getRedirectUrl();
//
//echo $redirect;

$response = array("paymentURL"=>$redirect,"transactionId"=>$transactionId,"manier"=>"$manier");
echo json_encode($response);

?>
