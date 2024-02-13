<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Toko Kartu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    @include('navbar')

    @if (session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
    @endif

    <div class="container">
        <div class="row">
            @foreach($products as $product)
            <div class="col-3">
                <div class="card" style="min-height:650px;">
                    <img src="{{ $product->product_photo }}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">Rp. {{ number_format($product->price, 0, ',', '.') }}</p>
                        <p class="card-text" id="description_{{ $product->id }}">{{ $product->description }}</p>
                        <div class="quantity-control">
                            <button class="btn btn-outline-secondary mx-2 decrease-btn" data-id="{{ $product->id }}" data-action="decrease">-</button>
                            <span class="quantity" data-id="{{ $product->id }}">
                                {{ $product->quantityInCart }}
                            </span>
                            <button class="btn btn-outline-secondary mx-2 increase-btn" data-id="{{ $product->id }}" data-action="increase">+</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    </div>
    <ul>

    </ul>
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