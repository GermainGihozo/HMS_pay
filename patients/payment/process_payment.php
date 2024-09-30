<?php
session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ;
    $phoneNumber = $_POST['phonenumber']; // Fix the input name to match the form

    $amount = $_POST['amount'] ;
    $username = $_SESSION['username'] ?? 'Guest'; // Safely fetch session variable

    // Check for missing fields
    if (empty($email) || empty($phoneNumber) || empty($amount)) {
        die("Payment error: One or more required parameters missing");
    }

    // Flutterwave credentials
    $public_key = 'FLWPUBK_TEST-f9724ae8e606279ab4ae3ed7a68cece2-X';
    $secret_key = 'FLWSECK_TEST-d4c539ad2c717d6f6c3a98a0ca2e7d62-X';

    // API endpoint for initiating the transaction
    $url = "https://api.flutterwave.com/v3/payments";

    // Payment data
    $data = [
        "tx_ref" => uniqid(), // Unique transaction reference
        "amount" => $amount,
        "currency" => "RWF",
        "redirect_url" => "https://yourwebsite.com/payment_callback.php",
        "payment_options" => "mobilemoneyrwanda",
        "meta" => [
            "consumer_id" => 23,
            "consumer_mac" => "92a3-912ba-1192a"
        ],
        "customer" => [
            "email" => $email,
            "phone_number" => $phoneNumber,
            "name" => $username
        ],
        "customizations" => [
            "title" => "Payment",
            "description" => "Payment for services",
            "logo" => "https://yourwebsite.com/logo.png"
        ]
    ];

    // Initialize cURL
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $secret_key,
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        // Handle cURL error
        echo 'Curl error: ' . $error;
    } else {
        $result = json_decode($response, true);
        if ($result['status'] === 'success') {
            // Redirect to the payment link
            header('Location: ' . $result['data']['link']);
        } else {
            // Log the result for debugging
            var_dump($result);
            echo 'Payment error: ' . $result['message'];
        }
    }
}
?>
