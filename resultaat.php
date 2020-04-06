<?php
require __DIR__ . '/vendor/autoload.php';
require 'config.php';


$transaction = \Paynl\Transaction::getForReturn();
$transactionId = $_GET["orderId"];

$date = date('Y-m-d');
$date = new DateTime($date);
$interval = new DateInterval('P1M');
$date->add($interval);




//manual transfer transactions are always pending when the user is returned
if( $transaction->isPaid() || $transaction->isPending()){
    $transaction = \Paynl\Transaction::get($transactionId);

    $data = $transaction->getData();

//    var_dump($data);
    echo 'Betaald<br>';
if($data["statsDetails"]["extra2"] == "jaarlijks" || $data["statsDetails"]["extra2"] == "maandelijks" || $data["statsDetails"]["extra2"] == "kwartaal") {


    switch($data["statsDetails"]["extra2"]){
        case "jaarlijks":
            $intervalPeriod = 5;
            break;
        case "maandelijks":
            $intervalPeriod = 2;
            break;
        case "kwartaal":
            $intervalPeriod = 3;
            break;
    }

    try {
        $result = \Paynl\DirectDebit\Recurring::add(array(
            'amount' => $data["statsDetails"]["extra1"],
            'bankaccountHolder' => $data["enduser"]["initials"] . " " . $data["enduser"]["lastName"],
            'bankaccountNumber' => $data["stornoDetails"]["iban"],
            'intervalValue' => 1,
            'intervalPeriod' => $intervalPeriod,
            'bankaccountBic' => $data["stornoDetails"]["bic"],
            'processDate' => $date->format('Y-m-d'),
            'description' => 'Incasso Zinloos Geweld tegen Dieren ' . $data["paymentDetails"]["customerKey"]

//         optional
//        'exchangeUrl' => 'http://path_to_your_exchange/file.php',
//            'description' => 'De omschrijving',
//            'ipAddress' => '192.168.20.123',
//            'email' => 'naam@email.com',
//            'promotorId' => '123456789',
//            'tool' => 'sdk',
//            'info' => 'info',
//            'object' => 'object',
//            'extra1' => 'extra1',
//            'extra2' => 'extra2',
//            'extra3' => 'extra3',
//            'currency' => 'EUR',
        ));
        header('Location: http://test5.joyridha.nl/bedankt/');
    } catch (\Paynl\Error\Error $e) {
        echo "Error: ".$e->getMessage();
    }
} else{
    header('Location: http://test5.joyridha.nl/bedankt/');
}


} elseif($transaction->isCanceled()) {
        echo "Canceled";
}

//
# Setup API Url




?>
