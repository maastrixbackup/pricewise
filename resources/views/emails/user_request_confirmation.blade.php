{{-- @component('mail::message')
# Product Request Confirmation

Hello {{ $user_name }},

Thank you for your request. Here are the details:

**Product Name:** {{ $product_name }} <br/>
**Quantity:** {{ $qty }}<br/>
**Delivery Address:** {{ $delivery_address }}<br/>
**Callback Date:** {{ $callback_date }}<br/>
**Additional Information:** {{ $additional_info }}

We will get back to you soon.

Thanks,
{{ config('app.name') }}
@endcomponent --}}

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Product Request Confirmation</title>
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
            background-color: #007bff;
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
                    <h1>Product Request Confirmation</h1>
                </div>
                <div class="content">
                    <p>Dear {{ $user_name }},</p>
                    <p>Thank you for your product request. Here are the details of your request:</p>
                    <ul>
                        <li><strong>Product Name:</strong> {{ $product_name }}</li>
                        <li><strong>Quantity:</strong> {{ $qty }}</li>
                        <li><strong>Delivery Address:</strong> {{ $delivery_address }}</li>
                        <li><strong>Callback Date:</strong> {{ $callback_date }}</li>
                        <li><strong>Additional Information:</strong> {{ $additional_info }}</li>
                    </ul>
                    <p>We will process your request and get back to you soon.</p>
                    <p>Thank you for choosing us!</p>
                </div>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
