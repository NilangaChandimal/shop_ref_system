<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            width: 2.5in;
            margin: 0;
            padding: 0;
        }
        .receipt-header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .receipt-content {
            margin-top: 10px;
        }
        .receipt-content p {
            margin: 0;
        }
        .receipt-footer {
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
        }
        .item {
            border-bottom: 1px solid #000;
            padding: 3px 0;
        }
        .item-description {
            display: inline-block;
            width: 100%;
        }
        .item-amount {
            display: inline-block;
            text-align: right;
            width: 30%;
        }
    </style>
</head>
<body>
    <div class="receipt-header">
        <p>Sample Shop</p>
        <p>1234 Shop Street, City</p>
        <p>Contact: (123) 456-7890</p>
        <hr>
    </div>

    <div class="receipt-content">
        @foreach ($sale->products as $product)
            <div class="item">
                <div class="item-description">
                    <p>{{ $product->name }} ({{ $product->pivot->quantity }} @ {{ number_format($product->selling_price, 2) }})</p>
                </div>
                <div class="item-amount">
                    <p>${{ number_format($product->pivot->quantity * $product->selling_price, 2) }}</p>
                </div>
            </div>
        @endforeach

        <hr>
        <div>
            <p><strong>Total Value: </strong>${{ number_format($totalValue, 2) }}</p>
            <p><strong>Paid: </strong>${{ number_format($paidValue, 2) }}</p>
            <p><strong>Balance: </strong>${{ number_format($balance, 2) }}</p>
        </div>
    </div>

    <div class="receipt-footer">
        <p>Thank you for shopping with us!</p>
    </div>
</body>
</html>
