@extends('layouts.app')

@section('title', 'Company Search')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Company Search</h1>
            <p class="text-lg text-gray-600">Search across Singapore and Mexico company databases</p>
        </div>

        <!-- Search Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form id="search-form" method="GET" action="{{ route('companies.search') }}">
                <div class="flex">
                    <div class="flex-1">
                        <input 
                            type="text" 
                            name="q" 
                            id="search-input"
                            value="{{ $query ?? '' }}"
                            placeholder="Enter company name or registration number..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            autocomplete="off"
                        >
                    </div>
                    <button 
                        type="submit" 
                        class="px-6 py-3 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200"
                    >
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Search Results -->
        <div id="search-results" class="space-y-4">
            @if(isset($results) && count($results) > 0)
                <div class="bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">
                            Search Results ({{ count($results) }} found)
                        </h2>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($results as $company)
                            <div class="p-6 hover:bg-gray-50 transition duration-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                                            {{ $company['name'] }}
                                        </h3>
                                        <div class="text-sm text-gray-600 space-y-1">
                                            <p><strong>Registration:</strong> {{ $company['registration_number'] }}</p>
                                            @if(isset($company['state']))
                                                <p><strong>State:</strong> {{ $company['state'] }}</p>
                                            @endif
                                            @if($company['address'])
                                                <p><strong>Address:</strong> {{ $company['address'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $company['country'] === 'SG' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                            {{ $company['country'] === 'SG' ? 'Singapore' : 'Mexico' }}
                                        </span>
                                        <a 
                                            href="{{ $company['url'] }}" 
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200"
                                        >
                                            View Details
                                            <i class="fas fa-arrow-right ml-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @elseif(isset($query) && strlen($query) >= 2)
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <i class="fas fa-search text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No companies found</h3>
                    <p class="text-gray-600">Try adjusting your search terms or check the spelling.</p>
                </div>
            @elseif(isset($query) && strlen($query) < 2)
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <i class="fas fa-info-circle text-blue-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Enter at least 2 characters</h3>
                    <p class="text-gray-600">Please enter at least 2 characters to search for companies.</p>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <i class="fas fa-building text-blue-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Search Companies</h3>
                    <p class="text-gray-600">Enter a company name or registration number to get started.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let searchTimeout;
    
    // Real-time search as user types
    $('#search-input').on('input', function() {
        const query = $(this).val();
        
        clearTimeout(searchTimeout);
        
        if (query.length >= 2) {
            searchTimeout = setTimeout(function() {
                performSearch(query);
            }, 300);
        } else if (query.length === 0) {
            $('#search-results').html(`
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <i class="fas fa-building text-blue-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Search Companies</h3>
                    <p class="text-gray-600">Enter a company name or registration number to get started.</p>
                </div>
            `);
        }
    });

    function performSearch(query) {
        $('#search-results').html(`
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="loading mx-auto mb-4"></div>
                <p class="text-gray-600">Searching...</p>
            </div>
        `);

        $.get('{{ route("companies.search") }}', { q: query }, function(data) {
            if (data.length > 0) {
                let html = `
                    <div class="bg-white rounded-lg shadow-md">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">
                                Search Results (${data.length} found)
                            </h2>
                        </div>
                        <div class="divide-y divide-gray-200">
                `;

                data.forEach(function(company) {
                    html += `
                        <div class="p-6 hover:bg-gray-50 transition duration-200">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                                        ${company.name}
                                    </h3>
                                    <div class="text-sm text-gray-600 space-y-1">
                                        <p><strong>Registration:</strong> ${company.registration_number}</p>
                                        ${company.state ? `<p><strong>State:</strong> ${company.state}</p>` : ''}
                                        ${company.address ? `<p><strong>Address:</strong> ${company.address}</p>` : ''}
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        ${company.country === 'SG' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800'}">
                                        ${company.country === 'SG' ? 'Singapore' : 'Mexico'}
                                    </span>
                                    <a 
                                        href="${company.url}" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200"
                                    >
                                        View Details
                                        <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                });

                html += `
                        </div>
                    </div>
                `;

                $('#search-results').html(html);
            } else {
                $('#search-results').html(`
                    <div class="bg-white rounded-lg shadow-md p-6 text-center">
                        <i class="fas fa-search text-gray-400 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No companies found</h3>
                        <p class="text-gray-600">Try adjusting your search terms or check the spelling.</p>
                    </div>
                `);
            }
        }).fail(function() {
            $('#search-results').html(`
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <i class="fas fa-exclamation-triangle text-red-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Search Error</h3>
                    <p class="text-gray-600">An error occurred while searching. Please try again.</p>
                </div>
            `);
        });
    }
});
</script>
@endsection