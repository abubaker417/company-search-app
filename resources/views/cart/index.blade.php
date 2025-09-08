@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Shopping Cart</h1>
            @if(count($cartItems) > 0)
                <button 
                    onclick="clearCart()"
                    class="inline-flex items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200"
                >
                    <i class="fas fa-trash mr-2"></i>
                    Clear Cart
                </button>
            @endif
        </div>

        @if(count($cartItems) > 0)
            <!-- Cart Items -->
            <div class="bg-white rounded-lg shadow-md mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Cart Items ({{ count($cartItems) }})</h2>
                </div>
                
                <div class="divide-y divide-gray-200">
                    @foreach($cartItems as $index => $item)
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <h3 class="text-lg font-medium text-gray-900 mr-3">{{ $item['company']->name }}</h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $item['country'] === 'SG' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                            {{ $item['country'] === 'SG' ? 'Singapore' : 'Mexico' }}
                                        </span>
                                    </div>
                                    
                                    <div class="text-sm text-gray-600 mb-3">
                                        <p><strong>Report:</strong> {{ $item['report']->name }}</p>
                                        <p><strong>Description:</strong> {{ $item['report']->info }}</p>
                                        @if($item['country'] === 'MX' && $item['company']->state)
                                            <p><strong>State:</strong> {{ $item['company']->state->name }}</p>
                                        @endif
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <label for="quantity-{{ $index }}" class="text-sm text-gray-500 mr-2">Quantity:</label>
                                        <input 
                                            type="number" 
                                            id="quantity-{{ $index }}"
                                            min="1" 
                                            value="{{ $item['quantity'] }}" 
                                            class="w-20 px-2 py-1 border border-gray-300 rounded text-sm mr-2"
                                            onchange="updateQuantity('{{ $item['country'] }}_{{ $item['company']->id }}_{{ $item['report']->id }}', this.value)"
                                        >
                                        <button 
                                            onclick="updateQuantity('{{ $item['country'] }}_{{ $item['company']->id }}_{{ $item['report']->id }}', document.getElementById('quantity-{{ $index }}').value)"
                                            class="text-sm text-blue-600 hover:text-blue-800 mr-4"
                                        >
                                            Update
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-4">
                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-gray-900">
                                            ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            ${{ number_format($item['price'], 2) }} each
                                        </p>
                                    </div>
                                    
                                    <button 
                                        onclick="removeItem('{{ $item['country'] }}_{{ $item['company']->id }}_{{ $item['report']->id }}')"
                                        class="text-red-600 hover:text-red-800 p-2"
                                        title="Remove item"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Order Summary</h2>
                </div>
                
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-lg font-medium text-gray-900">Total Items:</span>
                        <span class="text-lg font-medium text-gray-900">{{ array_sum(array_column($cartItems, 'quantity')) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-xl font-bold text-gray-900">Total Amount:</span>
                        <span class="text-2xl font-bold text-blue-600">${{ number_format($total, 2) }}</span>
                    </div>
                    
                    <div class="flex space-x-4">
                        <a 
                            href="{{ route('companies.index') }}" 
                            class="flex-1 inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200"
                        >
                            <i class="fas fa-arrow-left mr-2"></i>
                            Continue Shopping
                        </a>
                        
                        <button 
                            onclick="checkout()"
                            class="flex-1 inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200"
                        >
                            <i class="fas fa-credit-card mr-2"></i>
                            Proceed to Checkout
                        </button>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <i class="fas fa-shopping-cart text-gray-400 text-6xl mb-6"></i>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Your cart is empty</h2>
                <p class="text-gray-600 mb-8">Start shopping to add items to your cart.</p>
                <a 
                    href="{{ route('companies.index') }}" 
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200"
                >
                    <i class="fas fa-search mr-2"></i>
                    Search Companies
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
function updateQuantity(key, quantity) {
    if (quantity < 1) {
        alert('Quantity must be at least 1');
        return;
    }
    
    $.ajax({
        url: '{{ route("cart.update", ":key") }}'.replace(':key', key),
        type: 'PUT',
        data: {
            quantity: parseInt(quantity)
        },
        success: function(response) {
            if (response.success) {
                location.reload();
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                let errorMessage = 'Validation failed:\n';
                for (let field in errors) {
                    errorMessage += errors[field][0] + '\n';
                }
                alert(errorMessage);
            } else {
                alert('Failed to update quantity. Please try again.');
            }
        }
    });
}

function removeItem(key) {
    if (confirm('Are you sure you want to remove this item from your cart?')) {
        $.ajax({
            url: '{{ route("cart.remove", ":key") }}'.replace(':key', key),
            type: 'DELETE',
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            },
            error: function() {
                alert('Failed to remove item. Please try again.');
            }
        });
    }
}

function clearCart() {
    if (confirm('Are you sure you want to clear your entire cart?')) {
        $.ajax({
            url: '{{ route("cart.clear") }}',
            type: 'DELETE',
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            },
            error: function() {
                alert('Failed to clear cart. Please try again.');
            }
        });
    }
}

function checkout() {
    alert('Checkout functionality would be implemented here. This is a demo application.');
}
</script>
@endsection