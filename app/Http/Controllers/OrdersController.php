<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use App\Models\Orders;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class OrdersController extends Controller
{
    public function index()
    {
        $products = Products::all(); // Fetch all products

        $cart = session()->get('cart', []);

        // Loop through each product to check if it exists in the cart
        foreach ($products as $product) {
            // Initialize the quantity to 0 by default
            $product->quantityInCart = 0;

            // Iterate through each item in the cart to find matching product IDs
            foreach ($cart as $item) {
                // If the current item's productId matches the product's ID
                if ($item['productId'] == $product->id) {
                    // Set the quantity in the cart for this product
                    $product->quantityInCart = $item['quantity'];
                    // No need to continue searching if the product is found
                    break;
                }
            }
        }

        return view('home', compact('products')); // Pass products data to the view
    }

    public function updateCart(Request $request)
    {
        $productId = $request->input('product_id');
        $action = $request->input('action');

        $cart = Session::get('cart', []);

        $existingItemIndex = -1;
        foreach ($cart as $index => $item) {
            if ($item['productId'] == $productId) {
                $existingItemIndex = $index;
                break;
            }
        }

        if ($existingItemIndex !== -1) {
            if ($action === 'decrease') {
                $cart[$existingItemIndex]['quantity'] -= 1;
                if ($cart[$existingItemIndex]['quantity'] === 0) {
                    unset($cart[$existingItemIndex]);

                    $qty = 0;
                } else {
                    $qty = $cart[$existingItemIndex]['quantity'];
                }
            } elseif ($action === 'increase') {
                $cart[$existingItemIndex]['quantity'] += 1;
                $qty = $cart[$existingItemIndex]['quantity'];
            }
        } else {
            if ($action === 'increase') {
                $cart[] = ['productId' => $productId, 'quantity' => 1];
            }
            $qty = 1;
        }

        Session::put('cart', $cart);
        Session::save();

        $items = $this->getCartItems($cart);
        $total = $this->calculateTotal($items);

        $total = 'Rp ' . number_format($total, 0, ',', '.');

        return response()->json(['quantity' => $qty, 'total' => $total]);
    }

    public function showCart()
    {
        $cart = Session::get('cart', []);
        $items = $this->getCartItems($cart);
        $total = $this->calculateTotal($items);

        $total = 'Rp ' . number_format($total, 0, ',', '.');

        return view('cart', compact('cart', 'total'));
    }

    public function shipment()
    {
        $cart = Session::get('cart', []);
        $items = $this->getCartItems($cart);
        $total = $this->calculateTotal($items);

        $total = 'Rp ' . number_format($total, 0, ',', '.');

        return view('shipment', compact('cart', 'total'));
    }


    private function getCartItems($cart)
    {
        $items = [];
        foreach ($cart as $item) {
            $product = Products::find($item['productId']);
            if ($product) {
                $product->quantity = $item['quantity'];
                $items[] = $product;
            }
        }
        return $items;
    }

    private function calculateTotal($items)
    {
        $total = 0;
        foreach ($items as $item) {
            $total += $item->price * $item->quantity; // Multiply price by quantity
        }
        return $total;
    }

    function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data.name'     => 'required|string',
            'data.email'    => 'required|string|email',
            'data.phone'    => 'required|string',
            'data.address'  => 'required|string',
            'data.cart'     => 'required',
            'data.total'    => 'required|numeric',
        ]);

        $validatedData = $validator->validated();

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $order = Orders::create(array_merge(
            [
                'name'          => $validatedData['data']['name'],
                'email'         => $validatedData['data']['email'],
                'phone_number'  => $validatedData['data']['phone'],
                'address'       => $validatedData['data']['address'],
                'total_amount'  => $validatedData['data']['total'],
            ]
        ));

        $cart = $validatedData['data']['cart'];
        $items = $this->getCartItems($cart);

        foreach ($items as $item) {
            $orderDetail = OrderDetail::create(array_merge(
                [
                    'order_id'      => $order->id,
                    'product_id'    => $item->id,
                    'qty'           => $item->quantity,
                    'total_amount'  => $item->price * $item->quantity,
                ]
            ));
        }

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $transaction_details = array(
            'order_id' => $order->id,
            'gross_amount' => $order->total_amount,
        );

        $shipping_address = array(
            'first_name' => $order->name,
            'email' => $order->email,
            'phone' => $order->phone_number,
            'address' => $order->address,
        );

        $customer_details = array(
            'first_name' => $order->name,
            'email' => $order->email,
            'phone' => $order->phone_number,
            'shipping_address' => $shipping_address,
        );

        $item_details = [];

        foreach ($items as $item) {
            $temp = array(
                'id' => $item->id,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'name' => $item->name,
            );
            $item_details[] = $temp;
        }

        $params = array(
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details,
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return response()->json(['snapToken' => $snapToken]);
    }

    function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        if ($hashed == $request->signature_key) {
            $order = Orders::find($request->order_id);
            // dd($)
            $current_time = date('Y-m-d H:i:s');
            $order->update(['settled_at' => $current_time]);
        }
    }
}
