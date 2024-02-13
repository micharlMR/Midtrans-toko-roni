<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .max-img {
            max-height: 200px;
            max-width: 300px;
            object-fit: scale-down;
            /* You can change this value */
        }

        .item-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price {
            font-weight: bold;
        }
    </style>
</head>

<body>
    @include('navbar')

    <div class="container">
        <h2>Shopping Cart</h2>
        <div class="row">
            <div class="col-md-8">
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
                                        <div class="input-group quantity-control  align-items-center">
                                            <button class="btn btn-outline-secondary mx-2 decrease-btn" data-id="{{ $item['productId'] }}" data-action="decrease">-</button>
                                            <span class="quantity" data-id="{{ $item['productId'] }}">{{ $item['quantity'] }}</span>
                                            <button class="btn btn-outline-secondary mx-2 increase-btn" data-id="{{ $item['productId'] }}" data-action="increase">+</button>
                                        </div>
                                        <strong class="text-nowrap">Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
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
                        <a href="/cart/shipment">
                            <button type="button" class="btn btn-primary btn-block">Buy</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const decreaseBtns = document.querySelectorAll('.decrease-btn');
            const increaseBtns = document.querySelectorAll('.increase-btn');

            decreaseBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    let productId = this.getAttribute('data-id');
                    updateQuantity(productId, 'decrease');
                });
            });

            increaseBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    let productId = this.getAttribute('data-id');
                    updateQuantity(productId, 'increase');
                });
            });

            function updateQuantity(productId, action) {
                var url = "{{ route('cart.update')}}";
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: productId,
                        action: action
                    },
                    success: function(response) {
                        // Update the quantity display
                        document.querySelector('.quantity[data-id="' + productId + '"]').textContent = response.quantity;
                        const totalElement = document.querySelector('#total');
                        if (totalElement) {
                            // Change the text content of the element
                            totalElement.textContent = response.total; // Replace 'New Total: $100' with your desired text
                        } else {
                            // Log an error message if the element does not exist
                            console.error('Element with id "total" not found.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating quantity:', error);
                    }
                });
            }
        });
    </script>
</body>

</html>