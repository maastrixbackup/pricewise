<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Notification</title>
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

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="main">
            <div class="header">
                <h1>Product Notification</h1>
            </div>
            <div class="content">
                <div class="card">
                    <div class="card-body">
                        <h1>Hello, {{ $user_name }}</h1>
                        <p>Thank you for your interest in our products.</p>
                        <p>We wanted to let you know that we have received your request to be notified when the product
                            is available.</p>
                        <p>Product: <strong>{{ $product_name }}</strong></p>
                        <p>We will notify you at <strong>{{ $email }}</strong> as soon as the product becomes
                            available.</p>
                        <p>If you have any questions, feel free to contact us.</p>
                        <p>Thank you for choosing our store!</p>
                    </div>
                </div>
            </div>
            <div class="footer">
                <p>&copy; {{ date('Y') }} Your Company Name. All rights reserved.</p>
                <p>This is an automated message, please do not reply.</p>
            </div>
        </div>
    </div>
</body>

</html>
