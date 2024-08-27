<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Product Notification Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f4f4f4;
            padding-bottom: 40px;
        }
        .main {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            padding: 20px;
            background-color: #2c3e50;
            color: white;
            text-align: center;
        }
        .content {
            padding: 30px;
            color: #555555;
            line-height: 1.5;
        }
        .content h1 {
            margin-top: 0;
            color: #333333;
        }
        .content p {
            margin: 0 0 15px;
        }
        .footer {
            padding: 20px;
            background-color: #2c3e50;
            color: white;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main">
            <div class="header">
                <h1>New Product Notification Request</h1>
            </div>
            <div class="content">
                <h1>Hello Admin,</h1>
                <p>A new product notification request has been received with the following details:</p>
                <p><strong>User Name:</strong> {{ $userName }}</p>
                <p><strong>User Email:</strong> {{ $email }}</p>
                <p><strong>Product Name:</strong> {{ $productName }}</p>
                <p>Please take the necessary action to ensure this user is notified when the product is available.</p>
                {{-- <p>If you need further details, you can <a href="{{ $adminPanelLink }}" target="_blank">view the request</a> in the admin panel.</p> --}}
            </div>
            <div class="footer">
                <p>&copy; {{ date('Y') }} Your Company Name. All rights reserved.</p>
                <p>This is an automated message, please do not reply.</p>
            </div>
        </div>
    </div>
</body>
</html>
