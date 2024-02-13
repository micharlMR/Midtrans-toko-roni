<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($this->checkCredentials($credentials)) {
            // Authentication passed...
            // You can add session or cookie logic here
            return redirect()->intended('admin/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid email or password.']);
    }

    private function checkCredentials($credentials)
    {
        // Hardcoded admin credentials
        $adminEmail = 'admin@example.com';
        $adminPassword = 'password';

        return $credentials['email'] === $adminEmail && $credentials['password'] === $adminPassword;
    }

    public function ordersReport()
    {
        $orders = Order::with('detail')->whereNotNull('settled_at')->orderBy('created_at', 'desc')->get();

        return view('admin.dashboard', compact('orders'));
    }

    public function orderDetails($orderId)
    {
        $order = Order::with('detail.product')->find($orderId);

        return view('admin.orderDetail', compact('order'));
    }
}
