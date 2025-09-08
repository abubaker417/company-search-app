@extends('layouts.app')

@section('title', $company->name)

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="max-w-6xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('companies.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <i class="fas fa-home mr-2"></i>
                        Search
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-1"></i>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $company->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Company Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <h1 class="text-3xl font-bold text-gray-900 mr-4">{{ $company->name }}</h1>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $country === 'SG' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                            {{ $country === 'SG' ? 'Singapore' : 'Mexico' }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Company Information</h3>
                            <dl class="space-y-2">
                                @if($country === 'SG')
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Registration Number</dt>
                                        <dd class="text-sm text-gray-900">{{ $company->registration_number }}</dd>
                                    </div>
                                    @if($company->slug)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Slug</dt>
                                            <dd class="text-sm text-gray-900">{{ $company->slug }}</dd>
                                        </div>
                                    @endif
                                @else
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Slug</dt>
                                        <dd class="text-sm text-gray-900">{{ $company->slug }}</dd>
                                    </div>
                                    @if($company->brand_name)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Brand Name</dt>
                                            <dd class="text-sm text-gray-900">{{ $company->brand_name }}</dd>
                                        </div>
                                    @endif
                                    @if($company->state)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">State</dt>
                                            <dd class="text-sm text-gray-900">{{ $company->state->name }}</dd>
                                        </div>
                                    @endif
                                @endif
                                
                                @if($company->address)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                                        <dd class="text-sm text-gray-900">{{ $company->address }}</dd>
                                    </div>
                                @endif
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                                    <dd class="text-sm text-gray-900">{{ $company->created_at->format('M d, Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Available Reports</h3>
                            <p class="text-sm text-gray-600 mb-4">
                                @if($country === 'SG')
                                    All Singapore companies have access to all available reports.
                                @else
                                    Reports available depend on the company's state: {{ $company->state ? $company->state->name : 'No state assigned' }}.
                                @endif
                            </p>
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                {{ count($reports) }} report(s) available
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports Section -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Available Reports</h2>
                <p class="text-sm text-gray-600 mt-1">Select reports to add to your cart</p>
            </div>
            
            @if(count($reports) > 0)
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($reports as $report)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-200">
                                <div class="flex justify-between items-start mb-3">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $report->name }}</h3>
                                    <span class="text-lg font-bold text-blue-600">
                                        ${{ number_format($country === 'SG' ? $report->amount : $report->price, 2) }}
                                    </span>
                                </div>
                                
                                <p class="text-sm text-gray-600 mb-4">{{ $report->info }}</p>
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <label for="quantity-{{ $report->id }}" class="text-sm text-gray-500 mr-2">Qty:</label>
                                        <input 
                                            type="number" 
                                            id="quantity-{{ $report->id }}"
                                            min="1" 
                                            value="1" 
                                            class="w-16 px-2 py-1 border border-gray-300 rounded text-sm"
                                        >
                                    </div>
                                    <button 
                                        onclick="addToCart('{{ $country }}', {{ $company->id }}, {{ $report->id }}, '{{ $report->name }}')"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200"
                                    >
                                        <i class="fas fa-cart-plus mr-1"></i>
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="p-6 text-center">
                    <i class="fas fa-file-alt text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Reports Available</h3>
                    <p class="text-gray-600">
                        @if($country === 'MX' && !$company->state)
                            This company is not assigned to any state, so no reports are available.
                        @else
                            No reports are currently available for this company.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function addToCart(country, companyId, reportId, reportName) {
    const quantity = document.getElementById('quantity-' + reportId).value;
    
    if (quantity < 1) {
        alert('Please enter a valid quantity');
        return;
    }
    
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Adding...';
    button.disabled = true;
    
    $.post('{{ route("cart.add") }}', {
        country: country,
        company_id: companyId,
        report_id: reportId,
        quantity: parseInt(quantity)
    }, function(response) {
        if (response.success) {
            // Show success message
            showNotification('Report added to cart successfully!', 'success');
            updateCartCount();
            
            // Reset button
            button.innerHTML = originalText;
            button.disabled = false;
            
            // Reset quantity
            document.getElementById('quantity-' + reportId).value = 1;
        }
    }).fail(function(xhr) {
        // Reset button
        button.innerHTML = originalText;
        button.disabled = false;
        
        if (xhr.status === 422) {
            const errors = xhr.responseJSON.errors;
            let errorMessage = 'Validation failed:\n';
            for (let field in errors) {
                errorMessage += errors[field][0] + '\n';
            }
            alert(errorMessage);
        } else {
            alert('Failed to add item to cart. Please try again.');
        }
    });
}

function showNotification(message, type) {
    const notification = $(`
        <div class="fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg fade-in
            ${type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'}">
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                ${message}
            </div>
        </div>
    `);
    
    $('body').append(notification);
    
    setTimeout(function() {
        notification.fadeOut(function() {
            notification.remove();
        });
    }, 3000);
}
</script>
@endsection