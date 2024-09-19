<?php
if (isset($_GET['status']) && $_GET['status'] === 'successful') {
    // Get the transaction ID
    $transactionID = $_GET['transaction_id'];

    // Verify the transaction
    $secret_key = 'FLWSECK_TEST-d4c539ad2c717d6f6c3a98a0ca2e7d62-X';
    $url = "https://api.flutterwave.com/v3/transactions/$transactionID/verify";

    // Initialize cURL
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
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
            // Payment was successful
            $transactionData = $result['data'];
            // Process the transaction details (e.g., save to database, display confirmation)
            echo 'Payment successful! Transaction ID: ' . $transactionData['id'];
        } else {
            // Payment verification failed
            echo 'Payment verification error: ' . $result['message'];
        }
    }
} else {
    // Payment failed or was cancelled
    echo 'Payment not successful.';
}
?>
