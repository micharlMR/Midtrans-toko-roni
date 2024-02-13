<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<body>
    <div class="container">
        <h2>Order Details</h2>
        <div class="row">
            <div class="col-md-6">
                <h3>Order Information</h3>
                <p><strong>Order ID:</strong> {{ $order->id }}</p>
                <p><strong>Name:</strong> {{ $order->name }}</p>
                <p><strong>Email:</strong> {{ $order->email }}</p>
                <p><strong>Total Amount:</strong> ${{ $order->total_amount }}</p>
                <p><strong>Settled At:</strong> {{ $order->settled_at }}</p>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <h3>Order Details</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->detail as $detail)
                        <tr>
                            <td>{{ $detail->product->name }}</td>
                            <td>{{ $detail->qty }}</td>
                            <td>${{ $detail->total_amount }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>