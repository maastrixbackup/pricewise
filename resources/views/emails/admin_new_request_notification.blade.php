<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>New Product Request Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .header {
            background-color: #dc3545;
            color: #fff;
            padding: 10px;
            border-radius: 5px 5px 0 0;
            text-align: center;
        }

        .content {
            padding: 20px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.8em;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="">
                    <h1>New Product Request</h1>
                </div>
                <div class="content">
                    <p>Dear Admin,</p>
                    <p>A new product request has been received. Here are the details:</p>
                    <ul>
                        <li><strong>User Name:</strong> {{ $user_name }}</li>
                        <li><strong>User Email:</strong> {{ $user_email }}</li>
                        <li><strong>Phone Number:</strong> {{ $user_number }}</li>
                        <li><strong>Product Name:</strong> {{ $product_name }}</li>
                        <li><strong>Quantity:</strong> {{ $qty }}</li>
                        <li><strong>Delivery Address:</strong> {{ $delivery_address }}</li>
                        <li><strong>Callback Date:</strong> {{ $callback_date }}</li>
                        <li><strong>Additional Information:</strong> {{ $additional_info }}</li>
                    </ul>
                    <p>Please take the necessary actions.</p>
                </div>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Pricewise. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
