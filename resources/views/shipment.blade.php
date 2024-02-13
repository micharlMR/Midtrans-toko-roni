<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>

<body>
    @include('navbar')

    <div class="container">
        <h2>Shipment</h2>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter your name">
                            </div>
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone number</label>
                                <input type="text" name="phone" class="form-control" id="phone" placeholder="Enter your phone number">
                            </div>
                            <div class="form-group">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" name="address" id="address" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="cart-items">
                    @if (empty($cart))
                    <p>Your cart is empty</p>
                    @else
                    @foreach ($cart as $item)
                    @php
                    $product = \App\Models\Products::find($item['productId']);
                    @endphp
                    <div class="card my-2">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <img src="{{ $product->product_photo }}" class="card-img img-square max-img" alt="Placeholder Image" style="max-height: 150px; max-width: 100px;">
                                </div>
                                <div class="col-md-10 d-flex flex-column">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text">{{ $product->description }}</p>
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <div></div>
                                        <strong class="text-nowrap">
                                            <span class="quantity" data-id="{{ $item['productId'] }}">
                                                {{ $item['quantity'] }}
                                            </span>
                                            x Rp. {{ number_format($product->price, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
            <!-- Repeat the above card for each product in the cart -->

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Cart Summary</h5>
                        <ul class="list-group mb-3">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Total (IDR)</span>
                                <strong id="total">{{ $total }}</strong>
                            </li>
                        </ul>
                        <!-- <a href="/cart/checkout">
                            <button type="submit" class="btn btn-primary btn-block">Checkout</button>
                        </a> -->

                        <button type="button" onclick="sendDataToController()" class="btn btn-primary btn-block">Checkout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="snap-container"></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function sendDataToController() {
            var name = $('#name').val();
            var email = $('#email').val();
            var phone = $('#phone').val();
            var address = $('#address').val();

            var cart = <?php

                        use Illuminate\Support\Facades\Session;

                        echo json_encode(Session::get('cart', [])); ?>;

            console.log(cart);

            var elementTotal = document.getElementById("total"); // Get the element by its ID
            var totalStr = elementTotal.textContent;

            // Remove non-numeric characters
            var totalInt = totalStr.replace(/\D/g, '');

            // Convert string to integer
            var total = parseInt(totalInt);

            var inputData = {
                name: name,
                email: email,
                phone: phone,
                address: address,
                cart: cart,
                total: total,
            }; // Get the input data from a form field, assuming its ID is 'inputData'

            console.log(inputData);

            $.ajax({
                url: "{{ route('cart.checkout') }}", // URL to your Laravel route
                method: 'POST', // HTTP method
                data: {
                    data: inputData, // Data to be sent to the controller
                    _token: '{{ csrf_token() }}' // CSRF token for Laravel
                },
                success: function(response) {
                    // Handle the response from the controller
                    console.log(response['snapToken']);
                    window.snap.pay(response['snapToken']);
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error(xhr.responseText);
                }
            });
        }
    </script>
</body>

</html>