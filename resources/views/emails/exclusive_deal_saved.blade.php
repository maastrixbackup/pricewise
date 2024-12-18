<!DOCTYPE html>
<html>

<head>
    <title>Exclusive Deal Saved</title>
</head>

<body>
    <h1>Thank you for saving your deal!</h1>
    <p>Your deal details are as follows:</p>
    <ul>
        <li>Email: {{ $emailId }}</li>
        <li>Postal Code: {{ $postalCode }}</li>
        <li>House Number: {{ $houseNo }}</li>
        <li>Address: {{ $address }}</li>
        <li>Valid Till: {{ $validTill }}</li>
        <li>Deals Url: {{ $dealsUrl }}</li>
    </ul>
</body>

</html>
