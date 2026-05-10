@extends('layouts.app')

@section('page-title', 'Shipping Integration')
@section('page-subtitle', 'Manage shipments, track parcels, and view history via MyParcel Asia.')

@section('content')
<div x-data="{ activeTab: 'cart' }" class="space-y-6">
    <!-- Tab Navigation -->
    <div class="bg-white p-1 rounded-xl shadow-sm border border-slate-200 inline-flex flex-wrap gap-1">
        <button 
            @click="activeTab = 'cart'" 
            :class="activeTab === 'cart' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50'"
            class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 flex items-center gap-2">
            <i data-feather="shopping-cart" class="w-4 h-4"></i>
            Cart & Checkout
        </button>
        <button 
            @click="activeTab = 'create'" 
            :class="activeTab === 'create' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50'"
            class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 flex items-center gap-2">
            <i data-feather="plus-circle" class="w-4 h-4"></i>
            Create Shipment
        </button>
        <button 
            @click="activeTab = 'history'" 
            :class="activeTab === 'history' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50'"
            class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 flex items-center gap-2">
            <i data-feather="history" class="w-4 h-4"></i>
            History
        </button>
        <button 
            @click="activeTab = 'trace'" 
            :class="activeTab === 'trace' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50'"
            class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 flex items-center gap-2">
            <i data-feather="search" class="w-4 h-4"></i>
            Trace
        </button>
        <button 
            @click="activeTab = 'reference'" 
            :class="activeTab === 'reference' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50'"
            class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 flex items-center gap-2">
            <i data-feather="book-open" class="w-4 h-4"></i>
            Reference
        </button>
    </div>

    <!-- Content Area -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Cart & Checkout -->
        <div x-show="activeTab === 'cart'" class="p-6 space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Cart Items</h2>
                    <p class="text-sm text-slate-500">Select items then checkout to pay and get tracking numbers.</p>
                </div>
                <button type="button" id="btn-load-cart" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-colors shadow-sm gap-2">
                    <i data-feather="refresh-cw" class="w-4 h-4"></i>
                    Load Cart
                </button>
            </div>

            <div id="cart-loading" class="hidden py-12 text-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
                <p class="mt-4 text-sm text-slate-500 font-medium">Loading items...</p>
            </div>

            <div id="cart-error" class="hidden p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-xl text-sm font-medium"></div>
            <div id="cart-success" class="hidden p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl text-sm font-medium"></div>

            <div id="cart-table-wrap" class="hidden animate-in fade-in duration-300">
                <div class="overflow-x-auto rounded-xl border border-slate-100">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 text-slate-600 font-semibold uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3 w-10">
                                    <input type="checkbox" id="cart-select-all" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                </th>
                                <th class="px-4 py-3">Key</th>
                                <th class="px-4 py-3">Provider</th>
                                <th class="px-4 py-3">Tracking</th>
                                <th class="px-4 py-3">Created</th>
                            </tr>
                        </thead>
                        <tbody id="cart-tbody" class="divide-y divide-slate-100"></tbody>
                    </table>
                </div>
                <div class="flex justify-end pt-6">
                    <button type="button" id="btn-checkout" disabled class="inline-flex items-center px-6 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-lg hover:bg-emerald-700 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed gap-2">
                        <i data-feather="check-circle" class="w-4 h-4"></i>
                        Checkout Selected
                    </button>
                </div>
            </div>
        </div>

        <!-- Create Shipment -->
        <div x-show="activeTab === 'create'" class="p-6 space-y-8 animate-in fade-in duration-300">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Create New Shipment</h2>
                <p class="text-sm text-slate-500">Generate a new shipment in MyParcel Asia. Prices and references will be shown upon success.</p>
            </div>

            <div id="create-result" class="hidden"></div>
            <div id="create-loading" class="hidden py-8 text-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
                <p class="mt-4 text-sm text-slate-500">Creating shipment...</p>
            </div>

            <form id="form-create-shipment" class="grid grid-cols-1 lg:grid-cols-2 gap-x-12 gap-y-10">
                <!-- Sender Section -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <i data-feather="arrow-up-right" class="w-5 h-5"></i>
                        </div>
                        <h3 class="font-bold text-slate-900">Sender</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-xs font-bold text-slate-700 uppercase" for="sender_name">Name *</label>
                            <input type="text" id="sender_name" name="sender_name" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" required>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 uppercase" for="sender_phone">Phone *</label>
                            <input type="text" id="sender_phone" name="sender_phone" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" required>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 uppercase" for="sender_email">Email</label>
                            <input type="email" id="sender_email" name="sender_email" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                        </div>
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-xs font-bold text-slate-700 uppercase" for="sender_company_name">Company</label>
                            <input type="text" id="sender_company_name" name="sender_company_name" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" value="{{ config('app.name') }}">
                        </div>
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-xs font-bold text-slate-700 uppercase" for="sender_address_line_1">Address *</label>
                            <input type="text" id="sender_address_line_1" name="sender_address_line_1" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" required>
                        </div>
                        <div class="space-y-1.5 md:col-span-2">
                            <input type="text" id="sender_address_line_2" name="sender_address_line_2" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" placeholder="Apartment, suite, etc. (optional)">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 uppercase" for="sender_postcode">Postcode *</label>
                            <input type="text" id="sender_postcode" name="sender_postcode" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" required value="{{ config('services.myparcelasia.sender_postcode', '88000') }}">
                        </div>
                    </div>
                </div>

                <!-- Receiver Section -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                        <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i data-feather="arrow-down-left" class="w-5 h-5"></i>
                        </div>
                        <h3 class="font-bold text-slate-900">Receiver</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-xs font-bold text-slate-700 uppercase" for="receiver_name">Name *</label>
                            <input type="text" id="receiver_name" name="receiver_name" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" required>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 uppercase" for="receiver_phone">Phone *</label>
                            <input type="text" id="receiver_phone" name="receiver_phone" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" required>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 uppercase" for="receiver_email">Email</label>
                            <input type="email" id="receiver_email" name="receiver_email" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                        </div>
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-xs font-bold text-slate-700 uppercase" for="receiver_address_line_1">Address *</label>
                            <input type="text" id="receiver_address_line_1" name="receiver_address_line_1" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" required>
                        </div>
                        <div class="space-y-1.5 md:col-span-2">
                            <input type="text" id="receiver_address_line_2" name="receiver_address_line_2" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" placeholder="Apartment, suite, etc. (optional)">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 uppercase" for="receiver_postcode">Postcode *</label>
                            <input type="text" id="receiver_postcode" name="receiver_postcode" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" required>
                        </div>
                    </div>
                </div>

                <!-- Shipment Info -->
                <div class="lg:col-span-2 space-y-6 bg-slate-50 p-6 rounded-2xl border border-slate-200">
                    <div class="flex items-center gap-3 border-b border-slate-200 pb-4">
                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                            <i data-feather="package" class="w-5 h-5"></i>
                        </div>
                        <h3 class="font-bold text-slate-900">Parcel Information</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 uppercase" for="declared_weight">Weight (kg) *</label>
                            <input type="number" id="declared_weight" name="declared_weight" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" step="0.1" min="0.1" required value="1">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 uppercase" for="provider_code">Provider *</label>
                            <input type="text" id="provider_code" name="provider_code" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" required placeholder="poslaju">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 uppercase" for="parcel_size">Size</label>
                            <input type="text" id="parcel_size" name="parcel_size" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" value="box">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 uppercase" for="send_method">Method</label>
                            <select id="send_method" name="send_method" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                                <option value="pickup">Pickup</option>
                                <option value="dropoff">Dropoff</option>
                            </select>
                        </div>
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-xs font-bold text-slate-700 uppercase" for="content_description">Description</label>
                            <input type="text" id="content_description" name="content_description" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" placeholder="Shipment">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 uppercase" for="content_value">Value (MYR)</label>
                            <input type="number" id="content_value" name="content_value" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" step="0.01" min="0" value="0">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 uppercase" for="send_date">Send Date</label>
                            <input type="date" id="send_date" name="send_date" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 flex justify-end">
                    <button type="submit" id="btn-create-shipment" class="inline-flex items-center px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/20 gap-2">
                        <i data-feather="send" class="w-5 h-5"></i>
                        Generate Shipment
                    </button>
                </div>
            </form>
        </div>

        <!-- History -->
        <div x-show="activeTab === 'history'" class="p-6 space-y-6 animate-in fade-in duration-300">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Shipment History</h2>
                    <p class="text-sm text-slate-500">View and manage previously created shipments.</p>
                </div>
                <button type="button" id="btn-load-history" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-lg hover:bg-slate-50 transition-colors gap-2">
                    <i data-feather="refresh-cw" class="w-4 h-4"></i>
                    Refresh
                </button>
            </div>
            <div id="history-loading" class="hidden py-12 text-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
            </div>
            <div id="history-table-wrap" class="hidden overflow-x-auto rounded-xl border border-slate-100">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 text-slate-600 font-semibold uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Reference</th>
                            <th class="px-4 py-3">Tracking</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Created</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="history-tbody" class="divide-y divide-slate-100"></tbody>
                </table>
            </div>
        </div>

        <!-- Trace -->
        <div x-show="activeTab === 'trace'" class="p-6 space-y-6 animate-in fade-in duration-300">
            <div class="max-w-xl">
                <h2 class="text-lg font-bold text-slate-900">Trace Shipment</h2>
                <p class="text-sm text-slate-500 mb-6">Enter tracking number or reference key to trace parcel status.</p>
                
                <form id="form-trace" class="flex gap-2">
                    <input type="text" id="trace_key" class="flex-1 px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all" placeholder="Tracking No or Key...">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition-colors">Trace</button>
                </form>
            </div>
            <div id="trace-loading" class="hidden py-8 text-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
            </div>
            <div id="trace-result" class="hidden animate-in zoom-in-95 duration-200"></div>
        </div>

        <!-- Reference (Sizes, Types, Statuses) -->
        <div x-show="activeTab === 'reference'" class="p-6 space-y-8 animate-in fade-in duration-300">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Parcel Sizes -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-slate-900 flex items-center gap-2">
                            <i data-feather="maximize" class="w-4 h-4 text-indigo-500"></i>
                            Parcel Sizes
                        </h3>
                        <button type="button" id="btn-load-sizes" class="p-1.5 text-slate-400 hover:text-indigo-600 transition-colors">
                            <i data-feather="refresh-cw" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div id="sizes-loading" class="hidden"><div class="animate-spin h-4 w-4 border-2 border-indigo-600 border-t-transparent rounded-full mx-auto"></div></div>
                    <div id="sizes-content" class="hidden text-xs bg-slate-50 p-4 rounded-xl max-h-64 overflow-y-auto border border-slate-100"></div>
                </div>

                <!-- Content Types -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-slate-900 flex items-center gap-2">
                            <i data-feather="list" class="w-4 h-4 text-indigo-500"></i>
                            Content Types
                        </h3>
                        <button type="button" id="btn-load-content-types" class="p-1.5 text-slate-400 hover:text-indigo-600 transition-colors">
                            <i data-feather="refresh-cw" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div id="content-types-loading" class="hidden"><div class="animate-spin h-4 w-4 border-2 border-indigo-600 border-t-transparent rounded-full mx-auto"></div></div>
                    <div id="content-types-content" class="hidden text-xs bg-slate-50 p-4 rounded-xl max-h-64 overflow-y-auto border border-slate-100"></div>
                </div>

                <!-- Statuses -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-slate-900 flex items-center gap-2">
                            <i data-feather="info" class="w-4 h-4 text-indigo-500"></i>
                            Shipment Statuses
                        </h3>
                        <button type="button" id="btn-load-statuses" class="p-1.5 text-slate-400 hover:text-indigo-600 transition-colors">
                            <i data-feather="refresh-cw" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div id="statuses-loading" class="hidden"><div class="animate-spin h-4 w-4 border-2 border-indigo-600 border-t-transparent rounded-full mx-auto"></div></div>
                    <div id="statuses-content" class="hidden text-xs bg-slate-50 p-4 rounded-xl max-h-64 overflow-y-auto border border-slate-100"></div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* Custom Scrollbar for references */
    .scrollbar-thin::-webkit-scrollbar { width: 4px; }
    .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
    .scrollbar-thin::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    
    /* Animation helper */
    .animate-in { animation: animate-in 0.3s ease-out; }
    @keyframes animate-in {
        from { opacity: 0; transform: translateY(4px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrf = '{{ csrf_token() }}';
    const base = '{{ url("/admin/shipping/myparcel") }}';

    // Helper: Escape HTML
    const escapeHtml = (s) => {
        const div = document.createElement('div');
        div.textContent = s;
        return div.innerHTML;
    };

    // Helper: Fetch with CSRF
    const apiFetch = async (url, options = {}) => {
        const defaults = {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrf
            }
        };
        if (options.body && typeof options.body === 'object' && !(options.body instanceof FormData)) {
            options.body = JSON.stringify(options.body);
            defaults.headers['Content-Type'] = 'application/json';
        }
        const response = await fetch(url, { ...defaults, ...options });
        if (!response.ok) throw new Error('Network response was not ok');
        return await response.json();
    };

    // Set default send date
    const sendDateEl = document.getElementById('send_date');
    if (sendDateEl && !sendDateEl.value) {
        const d = new Date();
        d.setDate(d.getDate() + 1);
        sendDateEl.value = d.toISOString().slice(0, 10);
    }

    // --- CART & CHECKOUT ---
    const btnLoadCart = document.getElementById('btn-load-cart');
    const cartLoading = document.getElementById('cart-loading');
    const cartError = document.getElementById('cart-error');
    const cartSuccess = document.getElementById('cart-success');
    const cartWrap = document.getElementById('cart-table-wrap');
    const cartTbody = document.getElementById('cart-tbody');
    const cartSelectAll = document.getElementById('cart-select-all');
    const btnCheckout = document.getElementById('btn-checkout');

    btnLoadCart.addEventListener('click', async () => {
        cartError.classList.add('hidden');
        cartSuccess.classList.add('hidden');
        cartLoading.classList.remove('hidden');
        cartWrap.classList.add('hidden');

        try {
            const data = await apiFetch(`${base}/cart-items`);
            cartLoading.classList.add('hidden');
            if (data.success && Array.isArray(data.data)) {
                cartSelectAll.checked = false;
                cartTbody.innerHTML = data.data.length === 0
                    ? `<tr><td colspan="5" class="px-4 py-12 text-center text-slate-500 font-medium">Cart is empty.</td></tr>`
                    : data.data.map(item => {
                        const key = (item.key || '').replace(/"/g, '&quot;');
                        return `
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-3"><input type="checkbox" class="cart-key-cb rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" value="${key}"></td>
                                <td class="px-4 py-3 font-mono text-xs text-indigo-600 font-medium">${escapeHtml(item.key || '-')}</td>
                                <td class="px-4 py-3 text-slate-600 font-medium uppercase text-xs">${escapeHtml(item.provider_code || '-')}</td>
                                <td class="px-4 py-3 font-mono text-xs text-slate-600">${escapeHtml(item.tracking_no || '-')}</td>
                                <td class="px-4 py-3 text-slate-500 text-xs">${escapeHtml(item.created_at || '-')}</td>
                            </tr>
                        `;
                    }).join('');
                cartWrap.classList.remove('hidden');
                btnCheckout.disabled = true;
            } else {
                throw new Error(data.message || 'Failed to load cart');
            }
        } catch (err) {
            cartLoading.classList.add('hidden');
            cartError.textContent = err.message;
            cartError.classList.remove('hidden');
        }
    });

    cartSelectAll.addEventListener('change', () => {
        const cbs = document.querySelectorAll('.cart-key-cb');
        cbs.forEach(cb => cb.checked = cartSelectAll.checked);
        btnCheckout.disabled = !Array.from(cbs).some(cb => cb.checked);
    });

    cartTbody.addEventListener('change', (e) => {
        if (e.target.classList.contains('cart-key-cb')) {
            const cbs = document.querySelectorAll('.cart-key-cb');
            btnCheckout.disabled = !Array.from(cbs).some(cb => cb.checked);
            cartSelectAll.checked = Array.from(cbs).every(cb => cb.checked);
        }
    });

    btnCheckout.addEventListener('click', async () => {
        const keys = Array.from(document.querySelectorAll('.cart-key-cb:checked')).map(cb => cb.value).filter(Boolean);
        if (keys.length === 0) return;

        cartError.classList.add('hidden');
        cartSuccess.classList.add('hidden');
        btnCheckout.disabled = true;

        try {
            const data = await apiFetch(`${base}/checkout`, {
                method: 'POST',
                body: { shipment_keys: keys }
            });
            if (data.success) {
                cartSuccess.innerHTML = `<strong>Success!</strong> Checkout completed. Total: ${data.data?.total_price || '-'} MYR.`;
                cartSuccess.classList.remove('hidden');
                btnLoadCart.click();
            } else {
                throw new Error(data.message || 'Checkout failed');
            }
        } catch (err) {
            cartError.textContent = err.message;
            cartError.classList.remove('hidden');
            btnCheckout.disabled = false;
        }
    });

    // --- CREATE SHIPMENT ---
    const formCreate = document.getElementById('form-create-shipment');
    const createLoading = document.getElementById('create-loading');
    const createResult = document.getElementById('create-result');
    const btnCreate = document.getElementById('btn-create-shipment');

    formCreate.addEventListener('submit', async (e) => {
        e.preventDefault();
        createResult.classList.add('hidden');
        createLoading.classList.remove('hidden');
        btnCreate.disabled = true;

        const fd = new FormData(formCreate);
        const body = {};
        fd.forEach((v, k) => body[k] = v);

        try {
            const data = await apiFetch(`${base}/create-shipment`, {
                method: 'POST',
                body: body
            });
            createLoading.classList.add('hidden');
            btnCreate.disabled = false;
            createResult.classList.remove('hidden');

            if (data.success) {
                createResult.className = 'p-6 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-800 space-y-2';
                createResult.innerHTML = `
                    <div class="flex items-center gap-2 font-bold mb-2">
                        <i data-feather="check-circle" class="w-5 h-5 text-emerald-600"></i>
                        Shipment Created Successfully
                    </div>
                    ${data.total_price ? `<p class="text-2xl font-bold">RM ${parseFloat(data.total_price).toFixed(2)}</p>` : ''}
                    <p class="text-sm">Reference Key: <code class="bg-white/50 px-2 py-0.5 rounded border border-emerald-200">${escapeHtml(data.awb || '-')}</code></p>
                    ${data.message ? `<p class="text-sm mt-2 opacity-80">${escapeHtml(data.message)}</p>` : ''}
                `;
                feather.replace();
                formCreate.reset();
            } else {
                throw new Error(data.message || 'Create shipment failed');
            }
        } catch (err) {
            createLoading.classList.add('hidden');
            btnCreate.disabled = false;
            createResult.classList.remove('hidden');
            createResult.className = 'p-4 bg-rose-50 border border-rose-100 rounded-xl text-rose-800 text-sm font-medium';
            createResult.textContent = err.message;
        }
    });

    // --- HISTORY ---
    const btnLoadHistory = document.getElementById('btn-load-history');
    const historyLoading = document.getElementById('history-loading');
    const historyWrap = document.getElementById('history-table-wrap');
    const historyTbody = document.getElementById('history-tbody');

    btnLoadHistory.addEventListener('click', async () => {
        historyLoading.classList.remove('hidden');
        historyWrap.classList.add('hidden');

        try {
            const res = await apiFetch(`${base}/shipment-history`);
            historyLoading.classList.add('hidden');
            if (res.success && res.data) {
                const shipments = res.data.shipments || [];
                historyTbody.innerHTML = shipments.length === 0
                    ? `<tr><td colspan="5" class="px-4 py-12 text-center text-slate-500 font-medium">No history found.</td></tr>`
                    : shipments.map(s => `
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-4 py-3 text-slate-400 font-mono text-[10px]">${escapeHtml(s.id || '')}</td>
                            <td class="px-4 py-3 font-mono text-xs text-indigo-600 font-medium">${escapeHtml(s.key || '')}</td>
                            <td class="px-4 py-3 font-mono text-xs text-slate-600 font-semibold">${escapeHtml(s.tracking_no || '-')}</td>
                            <td class="px-4 py-3 text-slate-500 text-xs">${escapeHtml(s.created_at || '')}</td>
                            <td class="px-4 py-3">
                                <button onclick="window.open('${base}/consignment-note-single?tracking_no=${s.tracking_no}', '_blank')" class="text-indigo-600 hover:text-indigo-800 text-xs font-bold underline flex items-center gap-1">
                                    <i data-feather="file-text" class="w-3 h-3"></i> Label
                                </button>
                            </td>
                        </tr>
                    `).join('');
                historyWrap.classList.remove('hidden');
                feather.replace();
            }
        } catch (err) {
            historyLoading.classList.add('hidden');
        }
    });

    // --- TRACE ---
    const formTrace = document.getElementById('form-trace');
    const traceLoading = document.getElementById('trace-loading');
    const traceResult = document.getElementById('trace-result');

    formTrace.addEventListener('submit', async (e) => {
        e.preventDefault();
        const key = document.getElementById('trace_key').value.trim();
        if (!key) return;

        traceLoading.classList.remove('hidden');
        traceResult.classList.add('hidden');

        try {
            const formData = new FormData();
            formData.append('tracking_no', key);
            formData.append('_token', csrf);

            const res = await fetch(`${base}/trace`, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                body: formData
            }).then(r => r.json());

            traceLoading.classList.add('hidden');
            if (res.success && res.data) {
                traceResult.className = 'bg-slate-50 p-6 rounded-2xl border border-slate-200 animate-in fade-in duration-300';
                traceResult.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tracking No</p>
                            <p class="font-mono text-indigo-600 font-bold">${escapeHtml(res.data.tracking_no || key)}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700">
                                ${escapeHtml(res.data.status || 'Pending')}
                            </span>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Last Updated</p>
                            <p class="text-sm text-slate-600 font-medium">${escapeHtml(res.data.updated_at || '-')}</p>
                        </div>
                    </div>
                `;
                traceResult.classList.remove('hidden');
            } else {
                throw new Error(res.message || 'Tracking information not found');
            }
        } catch (err) {
            traceLoading.classList.add('hidden');
            traceResult.className = 'p-4 bg-rose-50 border border-rose-100 rounded-xl text-rose-800 text-sm font-medium';
            traceResult.textContent = err.message;
            traceResult.classList.remove('hidden');
        }
    });

    // --- REFERENCES ---
    const loadRef = async (type) => {
        const loading = document.getElementById(`${type}-loading`);
        const content = document.getElementById(`${type}-content`);
        const endpoint = type === 'sizes' ? 'parcel-sizes' : (type === 'content-types' ? 'content-types' : 'shipment-statuses');

        loading.classList.remove('hidden');
        content.classList.add('hidden');

        try {
            const data = await apiFetch(`${base}/${endpoint}`);
            loading.classList.add('hidden');
            if (data.success && data.data) {
                const entries = Object.entries(data.data);
                content.innerHTML = entries.map(([k, v]) => `
                    <div class="flex items-center justify-between py-2 border-b border-slate-200 last:border-0">
                        <code class="text-[10px] font-bold text-indigo-600 bg-white px-1.5 py-0.5 rounded border border-slate-200">${escapeHtml(k)}</code>
                        <span class="text-slate-600 font-medium truncate ml-2">${escapeHtml(v)}</span>
                    </div>
                `).join('');
                content.classList.remove('hidden');
            }
        } catch (err) {
            loading.classList.add('hidden');
            content.innerHTML = `<p class="text-rose-600 text-[10px]">Error loading</p>`;
            content.classList.remove('hidden');
        }
    };

    document.getElementById('btn-load-sizes').addEventListener('click', () => loadRef('sizes'));
    document.getElementById('btn-load-content-types').addEventListener('click', () => loadRef('content-types'));
    document.getElementById('btn-load-statuses').addEventListener('click', () => loadRef('statuses'));

    // Trigger initial load for cart
    btnLoadCart.click();
});
</script>
@endsection
