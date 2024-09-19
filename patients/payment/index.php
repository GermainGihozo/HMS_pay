<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
<form id="paymentForm" method="POST" action="process_payment.php">
  <input type="email" name="email" id="email" placeholder="Email" required />
  <input type="text" name="phoneNumber" id="phoneNumber" placeholder="Phone Number" required />
  <input type="text" name="amount" id="amount" placeholder="Amount" required />
  <button type="submit">Pay with Mobile money</button>
</form>

</body>
</html>