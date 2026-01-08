<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'location', 'details.product'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Search functionality - search across multiple fields
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('invoice_number', 'like', "%{$search}%")
                  ->orWhere('shipping_details', 'like', "%{$search}%")
                  ->orWhere('created_at', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('location', function($q2) use ($search) {
                      $q2->where('location_name', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->paginate(10)->withQueryString();

        // Get order statistics
        $stats = [
            'total' => Transaction::count(),
            'pending' => Transaction::where('status', 'pending')->count(),
            'processing' => Transaction::where('status', 'processing')->count(),
            'ready' => Transaction::where('status', 'ready')->count(),
            'completed' => Transaction::where('status', 'completed')->count(),
        ];

        return view('admin_pesanan', compact('orders', 'stats'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,ready,completed'
        ]);

        $order = Transaction::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return back()->with('success', "Status pesanan #{$order->invoice_number} berhasil diubah menjadi " . ucfirst($request->status));
    }

    public function show($id)
    {
        $order = Transaction::with(['user', 'location', 'details.product'])->findOrFail($id);
        return response()->json($order);
    }
}
