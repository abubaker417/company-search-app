<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanySg;
use App\Models\CompanyMx;
use App\Models\ReportSg;
use App\Models\ReportMx;
use App\Models\ReportState;

class CartController extends Controller
{
    /**
     * Display the cart
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $cartItems = [];
        $total = 0;

        foreach ($cart as $item) {
            $company = null;
            $report = null;
            $price = 0;

            if ($item['country'] === 'SG') {
                $company = CompanySg::find($item['company_id']);
                $report = ReportSg::find($item['report_id']);
                $price = $report ? $report->amount : 0;
            } elseif ($item['country'] === 'MX') {
                $company = CompanyMx::with('state')->find($item['company_id']);
                $reportState = ReportState::with('report')
                    ->where('state_id', $company->state_id)
                    ->where('report_id', $item['report_id'])
                    ->first();
                
                if ($reportState) {
                    $report = $reportState->report;
                    $price = $reportState->amount;
                }
            }

            if ($company && $report) {
                $cartItems[] = [
                    'company' => $company,
                    'report' => $report,
                    'price' => $price,
                    'country' => $item['country'],
                    'quantity' => $item['quantity']
                ];
                $total += $price * $item['quantity'];
            }
        }

        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'country' => 'required|in:SG,MX',
            'company_id' => 'required|integer',
            'report_id' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);
        $key = $request->country . '_' . $request->company_id . '_' . $request->report_id;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $request->quantity;
        } else {
            $cart[$key] = [
                'country' => $request->country,
                'company_id' => $request->company_id,
                'report_id' => $request->report_id,
                'quantity' => $request->quantity
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Item added to cart',
                'cart_count' => count($cart)
            ]);
        }

        return redirect()->back()->with('success', 'Item added to cart');
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $key)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = $request->quantity;
            session()->put('cart', $cart);

            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()->back()->with('success', 'Cart updated');
        }

        if ($request->ajax()) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        return redirect()->back()->with('error', 'Item not found');
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request, $key)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);

            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()->back()->with('success', 'Item removed from cart');
        }

        if ($request->ajax()) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        return redirect()->back()->with('error', 'Item not found');
    }

    /**
     * Clear the entire cart
     */
    public function clear()
    {
        session()->forget('cart');

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Cart cleared');
    }

    /**
     * Get cart count for AJAX requests
     */
    public function count()
    {
        $cart = session()->get('cart', []);
        $count = array_sum(array_column($cart, 'quantity'));

        return response()->json(['count' => $count]);
    }
}
