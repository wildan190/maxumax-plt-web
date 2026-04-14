@extends('layouts.app')

@section('page-title', 'MyParcel Asia')

@section('content')
    <div class="myparcel-page">
        <div class="myparcel-header">
            <h1 class="myparcel-title">MyParcel Asia</h1>
            <p class="myparcel-desc">Cart, checkout, parcel sizes, content types & shipment statuses</p>
        </div>

        <div class="myparcel-tabs">
            <button type="button" class="myparcel-tab active" data-panel="cart">Cart & Checkout</button>
            <button type="button" class="myparcel-tab" data-panel="create-shipment">Create Shipment</button>
            <button type="button" class="myparcel-tab" data-panel="parcel-sizes">Parcel Sizes</button>
            <button type="button" class="myparcel-tab" data-panel="content-types">Content Types</button>
            <button type="button" class="myparcel-tab" data-panel="statuses">Shipment Statuses</button>
            <button type="button" class="myparcel-tab" data-panel="trace">Trace</button>
            <button type="button" class="myparcel-tab" data-panel="shipment-history">Shipment History</button>
            <button type="button" class="myparcel-tab" data-panel="consignment-note">Consignment Note</button>
        </div>

        <div class="myparcel-content">
            <!-- Cart & Checkout -->
            <div id="panel-cart" class="myparcel-panel active">
                <div class="myparcel-panel-inner">
                    <h2 class="myparcel-panel-title">Cart Items</h2>
                    <p class="myparcel-panel-desc">Shipments in your MyParcel cart. Select items then checkout to pay and get tracking numbers.</p>

                    <div class="myparcel-actions">
                        <button type="button" class="myparcel-btn myparcel-btn-primary" id="btn-load-cart">Load Cart Items</button>
                    </div>

                    <div id="cart-loading" class="myparcel-loading" style="display:none;">
                        <span class="myparcel-spinner"></span> Loading…
                    </div>
                    <div id="cart-error" class="myparcel-alert myparcel-alert-error" style="display:none;"></div>
                    <div id="cart-success" class="myparcel-alert myparcel-alert-success" style="display:none;"></div>

                    <div id="cart-table-wrap" class="myparcel-table-wrap" style="display:none;">
                        <div class="myparcel-table-scroll">
                            <table class="myparcel-table">
                                <thead>
                                    <tr>
                                        <th class="myparcel-th-check"><input type="checkbox" id="cart-select-all" title="Select all" aria-label="Select all"></th>
                                        <th>Key</th>
                                        <th>Provider</th>
                                        <th>Tracking</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody id="cart-tbody"></tbody>
                            </table>
                        </div>
                        <div class="myparcel-actions myparcel-actions-end">
                            <button type="button" class="myparcel-btn myparcel-btn-success" id="btn-checkout" disabled>Checkout Selected</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Create Shipment -->
            <div id="panel-create-shipment" class="myparcel-panel">
                <div class="myparcel-panel-inner">
                    <h2 class="myparcel-panel-title">Create Shipment (Order)</h2>
                    <p class="myparcel-panel-desc">Buat shipment baru ke MyParcel. Setelah berhasil, harga (total) dan reference akan ditampilkan.</p>

                    <div id="create-result" class="myparcel-create-result" style="display:none;"></div>
                    <div id="create-loading" class="myparcel-loading" style="display:none;"><span class="myparcel-spinner"></span> Creating shipment…</div>

                    <form id="form-create-shipment" class="myparcel-form">
                        <div class="myparcel-form-section">
                            <h3 class="myparcel-form-section-title">Pengirim (Sender)</h3>
                            <div class="myparcel-form-grid">
                                <div class="myparcel-field">
                                    <label class="myparcel-label" for="sender_name">Nama *</label>
                                    <input type="text" id="sender_name" name="sender_name" class="myparcel-input" required>
                                </div>
                                <div class="myparcel-field">
                                    <label class="myparcel-label" for="sender_phone">Telepon *</label>
                                    <input type="text" id="sender_phone" name="sender_phone" class="myparcel-input" required>
                                </div>
                                <div class="myparcel-field myparcel-field-full">
                                    <label class="myparcel-label" for="sender_email">Email</label>
                                    <input type="email" id="sender_email" name="sender_email" class="myparcel-input">
                                </div>
                                <div class="myparcel-field">
                                    <label class="myparcel-label" for="sender_company_name">Nama Perusahaan</label>
                                    <input type="text" id="sender_company_name" name="sender_company_name" class="myparcel-input" value="{{ config('app.name') }}">
                                </div>
                                <div class="myparcel-field myparcel-field-full">
                                    <label class="myparcel-label" for="sender_address_line_1">Alamat 1 *</label>
                                    <input type="text" id="sender_address_line_1" name="sender_address_line_1" class="myparcel-input" required>
                                </div>
                                <div class="myparcel-field myparcel-field-full">
                                    <label class="myparcel-label" for="sender_address_line_2">Alamat 2</label>
                                    <input type="text" id="sender_address_line_2" name="sender_address_line_2" class="myparcel-input">
                                </div>
                                <div class="myparcel-field">
                                    <label class="myparcel-label" for="sender_postcode">Poskod *</label>
                                    <input type="text" id="sender_postcode" name="sender_postcode" class="myparcel-input" required value="{{ config('services.myparcelasia.sender_postcode', '88000') }}">
                                </div>
                            </div>
                        </div>

                        <div class="myparcel-form-section">
                            <h3 class="myparcel-form-section-title">Penerima (Receiver)</h3>
                            <div class="myparcel-form-grid">
                                <div class="myparcel-field">
                                    <label class="myparcel-label" for="receiver_name">Nama *</label>
                                    <input type="text" id="receiver_name" name="receiver_name" class="myparcel-input" required>
                                </div>
                                <div class="myparcel-field">
                                    <label class="myparcel-label" for="receiver_phone">Telepon *</label>
                                    <input type="text" id="receiver_phone" name="receiver_phone" class="myparcel-input" required>
                                </div>
                                <div class="myparcel-field myparcel-field-full">
                                    <label class="myparcel-label" for="receiver_email">Email</label>
                                    <input type="email" id="receiver_email" name="receiver_email" class="myparcel-input">
                                </div>
                                <div class="myparcel-field myparcel-field-full">
                                    <label class="myparcel-label" for="receiver_address_line_1">Alamat 1 *</label>
                                    <input type="text" id="receiver_address_line_1" name="receiver_address_line_1" class="myparcel-input" required>
                                </div>
                                <div class="myparcel-field myparcel-field-full">
                                    <label class="myparcel-label" for="receiver_address_line_2">Alamat 2</label>
                                    <input type="text" id="receiver_address_line_2" name="receiver_address_line_2" class="myparcel-input">
                                </div>
                                <div class="myparcel-field">
                                    <label class="myparcel-label" for="receiver_postcode">Poskod *</label>
                                    <input type="text" id="receiver_postcode" name="receiver_postcode" class="myparcel-input" required>
                                </div>
                            </div>
                        </div>

                        <div class="myparcel-form-section">
                            <h3 class="myparcel-form-section-title">Shipment</h3>
                            <div class="myparcel-form-grid">
                                <div class="myparcel-field">
                                    <label class="myparcel-label" for="declared_weight">Berat (kg) *</label>
                                    <input type="number" id="declared_weight" name="declared_weight" class="myparcel-input" step="0.1" min="0.1" required value="1">
                                </div>
                                <div class="myparcel-field">
                                    <label class="myparcel-label" for="provider_code">Provider (e.g. poslaju) *</label>
                                    <input type="text" id="provider_code" name="provider_code" class="myparcel-input" required placeholder="poslaju">
                                </div>
                                <div class="myparcel-field">
                                    <label class="myparcel-label" for="parcel_size">Saiz Parcel</label>
                                    <input type="text" id="parcel_size" name="parcel_size" class="myparcel-input" value="box" placeholder="box, flyers_s, ...">
                                </div>
                                <div class="myparcel-field">
                                    <label class="myparcel-label" for="content_type">Content Type</label>
                                    <input type="text" id="content_type" name="content_type" class="myparcel-input" value="general" placeholder="general">
                                </div>
                                <div class="myparcel-field myparcel-field-full">
                                    <label class="myparcel-label" for="content_description">Keterangan Kandungan</label>
                                    <input type="text" id="content_description" name="content_description" class="myparcel-input" placeholder="Shipment">
                                </div>
                                <div class="myparcel-field">
                                    <label class="myparcel-label" for="content_value">Nilai (MYR)</label>
                                    <input type="number" id="content_value" name="content_value" class="myparcel-input" step="0.01" min="0" value="0">
                                </div>
                                <div class="myparcel-field">
                                    <label class="myparcel-label" for="send_date">Tarikh Hantar</label>
                                    <input type="date" id="send_date" name="send_date" class="myparcel-input">
                                </div>
                                <div class="myparcel-field">
                                    <label class="myparcel-label" for="send_method">Kaedah</label>
                                    <select id="send_method" name="send_method" class="myparcel-input">
                                        <option value="pickup">Pickup</option>
                                        <option value="dropoff">Dropoff</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="myparcel-actions">
                            <button type="submit" class="myparcel-btn myparcel-btn-primary" id="btn-create-shipment">Create Shipment</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Parcel Sizes -->
            <div id="panel-parcel-sizes" class="myparcel-panel">
                <div class="myparcel-panel-inner">
                    <h2 class="myparcel-panel-title">Parcel Sizes</h2>
                    <div class="myparcel-actions">
                        <button type="button" class="myparcel-btn myparcel-btn-primary" id="btn-load-sizes">Load Parcel Sizes</button>
                    </div>
                    <div id="sizes-loading" class="myparcel-loading" style="display:none;"><span class="myparcel-spinner"></span> Loading…</div>
                    <div id="sizes-content" class="myparcel-ref-grid" style="display:none;"></div>
                </div>
            </div>

            <!-- Content Types -->
            <div id="panel-content-types" class="myparcel-panel">
                <div class="myparcel-panel-inner">
                    <h2 class="myparcel-panel-title">Content Types</h2>
                    <div class="myparcel-actions">
                        <button type="button" class="myparcel-btn myparcel-btn-primary" id="btn-load-types">Load Content Types</button>
                    </div>
                    <div id="types-loading" class="myparcel-loading" style="display:none;"><span class="myparcel-spinner"></span> Loading…</div>
                    <div id="types-content" class="myparcel-ref-grid" style="display:none;"></div>
                </div>
            </div>

            <!-- Shipment Statuses -->
            <div id="panel-statuses" class="myparcel-panel">
                <div class="myparcel-panel-inner">
                    <h2 class="myparcel-panel-title">Shipment Statuses</h2>
                    <div class="myparcel-actions">
                        <button type="button" class="myparcel-btn myparcel-btn-primary" id="btn-load-statuses">Load Statuses</button>
                    </div>
                    <div id="statuses-loading" class="myparcel-loading" style="display:none;"><span class="myparcel-spinner"></span> Loading…</div>
                    <div id="statuses-content" class="myparcel-ref-grid" style="display:none;"></div>
                </div>
            </div>

            <!-- Trace -->
            <div id="panel-trace" class="myparcel-panel">
                <div class="myparcel-panel-inner">
                    <h2 class="myparcel-panel-title">Trace Shipment</h2>
                    <p class="myparcel-panel-desc">Look up shipment status by tracking number. Only shipments that belong to your account can be traced.</p>
                    <form id="form-trace" class="myparcel-form" style="max-width: 480px;">
                        <div class="myparcel-field">
                            <label class="myparcel-label" for="trace_tracking_no">Tracking number *</label>
                            <input type="text" id="trace_tracking_no" name="tracking_no" class="myparcel-input" required placeholder="e.g. ERA2918222323MY">
                        </div>
                        <div class="myparcel-actions">
                            <button type="submit" class="myparcel-btn myparcel-btn-primary" id="btn-trace">Trace</button>
                        </div>
                    </form>
                    <div id="trace-loading" class="myparcel-loading" style="display:none;"><span class="myparcel-spinner"></span> Tracing…</div>
                    <div id="trace-error" class="myparcel-alert myparcel-alert-error" style="display:none;"></div>
                    <div id="trace-result" class="myparcel-trace-result" style="display:none;">
                        <h3 class="myparcel-trace-result-title">Result</h3>
                        <dl class="myparcel-trace-dl">
                            <dt>Tracking no</dt>
                            <dd id="trace-result-tracking"></dd>
                            <dt>Status</dt>
                            <dd id="trace-result-status"></dd>
                            <dt>Updated at</dt>
                            <dd id="trace-result-updated"></dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Shipment History -->
            <div id="panel-shipment-history" class="myparcel-panel">
                <div class="myparcel-panel-inner">
                    <h2 class="myparcel-panel-title">Shipment History</h2>
                    <p class="myparcel-panel-desc">List of past shipments from your account with pagination.</p>
                    <div class="myparcel-actions">
                        <button type="button" class="myparcel-btn myparcel-btn-primary" id="btn-load-history">Load Shipment History</button>
                    </div>
                    <div id="history-loading" class="myparcel-loading" style="display:none;"><span class="myparcel-spinner"></span> Loading…</div>
                    <div id="history-error" class="myparcel-alert myparcel-alert-error" style="display:none;"></div>
                    <div id="history-pagination" class="myparcel-pagination" style="display:none; margin-top: 0.75rem; font-size: 0.875rem; color: #6b7280;"></div>
                    <div id="history-table-wrap" class="myparcel-table-wrap" style="display:none; margin-top: 0.5rem;">
                        <div class="myparcel-table-scroll">
                            <table class="myparcel-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Key</th>
                                        <th>Tracking no</th>
                                        <th>Created</th>
                                        <th>Modified</th>
                                    </tr>
                                </thead>
                                <tbody id="history-tbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Consignment Note -->
            <div id="panel-consignment-note" class="myparcel-panel">
                <div class="myparcel-panel-inner">
                    <h2 class="myparcel-panel-title">Consignment Note</h2>
                    <p class="myparcel-panel-desc">Get consignment note (e.g. label/PDF) for one or more tracking numbers. Enter one per line or comma-separated.</p>
                    <form id="form-consignment-note" class="myparcel-form" style="max-width: 560px;">
                        <div class="myparcel-field">
                            <label class="myparcel-label" for="consignment_tracking">Tracking numbers *</label>
                            <textarea id="consignment_tracking" name="tracking_no" class="myparcel-input" rows="4" required placeholder="ERA311010700MY&#10;ERA311010695MY"></textarea>
                            <span class="myparcel-hint">One per line or comma-separated</span>
                        </div>
                        <div class="myparcel-actions">
                            <button type="submit" class="myparcel-btn myparcel-btn-primary" id="btn-consignment-note">Get Consignment Note</button>
                        </div>
                    </form>
                    <div id="consignment-loading" class="myparcel-loading" style="display:none;"><span class="myparcel-spinner"></span> Loading…</div>
                    <div id="consignment-error" class="myparcel-alert myparcel-alert-error" style="display:none;"></div>
                    <div id="consignment-result" class="myparcel-consignment-result" style="display:none; margin-top: 1rem;"></div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .myparcel-page {
            max-width: 1000px;
            margin: 0 auto;
        }

        .myparcel-header {
            margin-bottom: 1.5rem;
        }

        .myparcel-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1a1f36;
            margin: 0 0 0.25rem 0;
        }

        .myparcel-desc {
            font-size: 0.9375rem;
            color: #6b7280;
            margin: 0;
        }

        .myparcel-tabs {
            display: flex;
            gap: 0;
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 0;
            flex-wrap: wrap;
        }

        .myparcel-tab {
            padding: 0.75rem 1.25rem;
            border: none;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            background: none;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.9rem;
            color: #6b7280;
            transition: color 0.15s, border-color 0.15s;
        }

        .myparcel-tab:hover {
            color: #374151;
        }

        .myparcel-tab.active {
            color: #2563eb;
            border-bottom-color: #2563eb;
        }

        .myparcel-content {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .myparcel-panel {
            display: none;
            padding: 0;
        }

        .myparcel-panel.active {
            display: block;
        }

        .myparcel-panel-inner {
            padding: 1.5rem;
        }

        @media (min-width: 768px) {
            .myparcel-panel-inner {
                padding: 2rem;
            }
        }

        .myparcel-panel-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1a1f36;
            margin: 0 0 0.5rem 0;
        }

        .myparcel-panel-desc {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0 0 1.25rem 0;
        }

        .myparcel-actions {
            margin-bottom: 1.25rem;
        }

        .myparcel-actions-end {
            margin-top: 1.25rem;
            margin-bottom: 0;
        }

        .myparcel-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            border: none;
            transition: background-color 0.15s;
        }

        .myparcel-btn-primary {
            background: #2563eb;
            color: #fff;
        }

        .myparcel-btn-primary:hover:not(:disabled) {
            background: #1d4ed8;
        }

        .myparcel-btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .myparcel-btn-success {
            background: #059669;
            color: #fff;
        }

        .myparcel-btn-success:hover:not(:disabled) {
            background: #047857;
        }

        .myparcel-btn-success:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .myparcel-loading {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #6b7280;
            font-size: 0.875rem;
            padding: 1rem 0;
        }

        .myparcel-spinner {
            width: 18px;
            height: 18px;
            border: 2px solid #e5e7eb;
            border-top-color: #2563eb;
            border-radius: 50%;
            animation: myparcel-spin 0.7s linear infinite;
        }

        @keyframes myparcel-spin {
            to { transform: rotate(360deg); }
        }

        .myparcel-alert {
            padding: 1rem 1.25rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }

        .myparcel-alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .myparcel-alert-success {
            background: #f0fdf4;
            color: #065f46;
            border: 1px solid #bbf7d0;
        }

        .myparcel-table-wrap {
            margin-top: 0.5rem;
        }

        .myparcel-table-scroll {
            overflow-x: auto;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }

        .myparcel-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
            min-width: 520px;
        }

        .myparcel-table th,
        .myparcel-table td {
            padding: 12px 14px;
            text-align: left;
            border-bottom: 1px solid #f3f4f6;
        }

        .myparcel-table thead th {
            background: #f9fafb;
            color: #6b7280;
            font-weight: 600;
            white-space: nowrap;
        }

        .myparcel-table tbody tr:hover {
            background: #fafafa;
        }

        .myparcel-table tbody tr:last-child td {
            border-bottom: none;
        }

        .myparcel-th-check {
            width: 2.5rem;
        }

        .myparcel-table code {
            font-size: 0.75rem;
            background: #f3f4f6;
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
            word-break: break-all;
        }

        .myparcel-ref-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0;
            font-size: 0.875rem;
        }

        @media (min-width: 640px) {
            .myparcel-ref-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 900px) {
            .myparcel-ref-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .myparcel-ref-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            background: #fafafa;
            border-radius: 6px;
            margin-bottom: 0.5rem;
        }

        .myparcel-ref-item code {
            font-size: 0.8125rem;
            background: #fff;
            padding: 0.25rem 0.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            flex-shrink: 0;
        }

        .myparcel-ref-item span {
            color: #374151;
            text-align: right;
        }

        .myparcel-form-section {
            margin-bottom: 1.5rem;
        }

        .myparcel-form-section-title {
            font-size: 1rem;
            font-weight: 600;
            color: #374151;
            margin: 0 0 0.75rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .myparcel-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .myparcel-field-full {
            grid-column: 1 / -1;
        }

        @media (max-width: 640px) {
            .myparcel-form-grid {
                grid-template-columns: 1fr;
            }
        }

        .myparcel-field {
            display: flex;
            flex-direction: column;
        }

        .myparcel-label {
            font-size: 0.8125rem;
            font-weight: 500;
            color: #4b5563;
            margin-bottom: 0.375rem;
        }

        .myparcel-input {
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.875rem;
        }

        .myparcel-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
        }

        .myparcel-create-result {
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }

        .myparcel-create-result.success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #065f46;
        }

        .myparcel-create-result.error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .myparcel-trace-result {
            margin-top: 1.5rem;
            padding: 1.25rem;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
        }

        .myparcel-trace-result-title {
            font-size: 1rem;
            font-weight: 600;
            color: #065f46;
            margin: 0 0 0.75rem 0;
        }

        .myparcel-trace-dl {
            margin: 0;
            font-size: 0.875rem;
        }

        .myparcel-trace-dl dt {
            font-weight: 600;
            color: #4b5563;
            margin-top: 0.5rem;
        }

        .myparcel-trace-dl dt:first-child { margin-top: 0; }

        .myparcel-trace-dl dd {
            margin: 0.25rem 0 0 0;
            color: #111827;
        }

        .myparcel-create-result .price {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0.25rem 0;
        }

        .myparcel-hint {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        .myparcel-consignment-result {
            padding: 1rem;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.875rem;
            word-break: break-all;
        }
    </style>

    <script>
(function() {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    const base = '{{ url("/admin/shipping/myparcel") }}';

    var sendDateEl = document.getElementById('send_date');
    if (sendDateEl && !sendDateEl.value) {
        var d = new Date();
        d.setDate(d.getDate() + 1);
        sendDateEl.value = d.toISOString().slice(0, 10);
    }

    document.getElementById('form-create-shipment').addEventListener('submit', function(e) {
        e.preventDefault();
        var resultEl = document.getElementById('create-result');
        var loadingEl = document.getElementById('create-loading');
        var btn = document.getElementById('btn-create-shipment');
        resultEl.style.display = 'none';
        resultEl.className = 'myparcel-create-result';
        loadingEl.style.display = 'flex';
        btn.disabled = true;

        var form = document.getElementById('form-create-shipment');
        var fd = new FormData(form);
        var body = {};
        fd.forEach(function(v, k) { body[k] = v; });
        if (!body.send_date) {
            var d = new Date();
            d.setDate(d.getDate() + 1);
            body.send_date = d.toISOString().slice(0, 10);
        }

        fetch(base + '/create-shipment', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify(body)
        })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                loadingEl.style.display = 'none';
                btn.disabled = false;
                resultEl.style.display = 'block';
                if (data.success) {
                    resultEl.className = 'myparcel-create-result success';
                    var html = '<strong>Shipment created.</strong>';
                    if (data.total_price != null) {
                        html += '<div class="price">Harga: RM ' + parseFloat(data.total_price).toFixed(2) + '</div>';
                    }
                    if (data.awb) {
                        html += '<div>Reference / Shipment key: <code>' + escapeHtml(data.awb) + '</code></div>';
                    }
                    if (data.message) html += '<div style="margin-top:0.5rem">' + escapeHtml(data.message) + '</div>';
                    resultEl.innerHTML = html;
                } else {
                    resultEl.className = 'myparcel-create-result error';
                    resultEl.textContent = data.message || 'Create shipment failed.';
                }
            })
            .catch(function() {
                loadingEl.style.display = 'none';
                btn.disabled = false;
                resultEl.style.display = 'block';
                resultEl.className = 'myparcel-create-result error';
                resultEl.textContent = 'Network error.';
            });
    });

    document.querySelectorAll('.myparcel-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.myparcel-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.myparcel-panel').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('panel-' + this.dataset.panel).classList.add('active');
        });
    });

    function renderRefGrid(entries) {
        return entries.map(function(pair) {
            var k = pair[0], v = pair[1];
            return '<div class="myparcel-ref-item"><code>' + escapeHtml(k || '') + '</code><span>' + escapeHtml(v || '') + '</span></div>';
        }).join('');
    }

    function escapeHtml(s) {
        const div = document.createElement('div');
        div.textContent = s;
        return div.innerHTML;
    }

    document.getElementById('btn-load-cart').addEventListener('click', function() {
        var loading = document.getElementById('cart-loading');
        var err = document.getElementById('cart-error');
        var wrap = document.getElementById('cart-table-wrap');
        var tbody = document.getElementById('cart-tbody');
        var success = document.getElementById('cart-success');
        err.style.display = 'none';
        success.style.display = 'none';
        loading.style.display = 'flex';
        wrap.style.display = 'none';

        fetch(base + '/cart-items', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(function(data) {
                loading.style.display = 'none';
                if (data.success && Array.isArray(data.data)) {
                    document.getElementById('cart-select-all').checked = false;
                    tbody.innerHTML = data.data.length === 0
                        ? '<tr><td colspan="5" style="padding:2rem;text-align:center;color:#6b7280">Cart is empty.</td></tr>'
                        : data.data.map(function(item) {
                            var key = (item.key || '').replace(/"/g, '&quot;');
                            return '<tr><td class="myparcel-th-check"><input type="checkbox" class="cart-key-cb" value="' + key + '" aria-label="Select"></td>' +
                                '<td><code>' + escapeHtml(item.key || '-') + '</code></td>' +
                                '<td>' + escapeHtml(item.provider_code || '-') + '</td>' +
                                '<td>' + escapeHtml(item.tracking_no || '-') + '</td>' +
                                '<td>' + escapeHtml(item.created_at || '-') + '</td></tr>';
                        }).join('');
                    wrap.style.display = 'block';
                    document.getElementById('btn-checkout').disabled = true;
                } else {
                    err.textContent = data.message || 'Failed to load cart';
                    err.style.display = 'block';
                }
            })
            .catch(function() {
                loading.style.display = 'none';
                err.textContent = 'Network error.';
                err.style.display = 'block';
            });
    });

    document.getElementById('cart-select-all').addEventListener('change', function() {
        document.querySelectorAll('.cart-key-cb').forEach(function(cb) { cb.checked = this.checked; }.bind(this));
        document.getElementById('btn-checkout').disabled = !document.querySelector('.cart-key-cb:checked');
    });
    document.getElementById('cart-tbody').addEventListener('change', function(e) {
        if (e.target.classList.contains('cart-key-cb')) {
            document.getElementById('btn-checkout').disabled = !document.querySelector('.cart-key-cb:checked');
        }
    });

    document.getElementById('btn-checkout').addEventListener('click', function() {
        var keys = Array.from(document.querySelectorAll('.cart-key-cb:checked')).map(function(cb) { return cb.value; }).filter(Boolean);
        if (keys.length === 0) return;
        var btn = this;
        var err = document.getElementById('cart-error');
        var success = document.getElementById('cart-success');
        err.style.display = 'none';
        success.style.display = 'none';
        btn.disabled = true;

        fetch(base + '/checkout', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ shipment_keys: keys })
        })
            .then(r => r.json())
            .then(function(data) {
                if (data.success) {
                    success.innerHTML = 'Checkout berhasil. Total: ' + (data.data && data.data.total_price != null ? data.data.total_price : '-') + ' MYR. Tracking numbers tersedia di data.shipments.';
                    success.style.display = 'block';
                    document.getElementById('btn-load-cart').click();
                } else {
                    err.textContent = data.message || 'Checkout failed';
                    err.style.display = 'block';
                }
                btn.disabled = false;
            })
            .catch(function() {
                err.textContent = 'Network error.';
                err.style.display = 'block';
                btn.disabled = false;
            });
    });

    document.getElementById('form-trace').addEventListener('submit', function(e) {
        e.preventDefault();
        var trackingNo = (document.getElementById('trace_tracking_no').value || '').trim();
        if (!trackingNo) return;
        var loading = document.getElementById('trace-loading');
        var errEl = document.getElementById('trace-error');
        var resultEl = document.getElementById('trace-result');
        var btn = document.getElementById('btn-trace');
        errEl.style.display = 'none';
        resultEl.style.display = 'none';
        loading.style.display = 'flex';
        btn.disabled = true;

        var formData = new FormData();
        formData.append('tracking_no', trackingNo);
        formData.append('_token', csrf);

        fetch(base + '/trace', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            body: formData
        })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                loading.style.display = 'none';
                btn.disabled = false;
                if (res.success && res.data) {
                    document.getElementById('trace-result-tracking').textContent = res.data.tracking_no || trackingNo;
                    document.getElementById('trace-result-status').textContent = res.data.status || '-';
                    document.getElementById('trace-result-updated').textContent = res.data.updated_at || '-';
                    resultEl.style.display = 'block';
                } else {
                    errEl.textContent = res.message || 'Trace failed.';
                    errEl.style.display = 'block';
                }
            })
            .catch(function() {
                loading.style.display = 'none';
                btn.disabled = false;
                errEl.textContent = 'Network error.';
                errEl.style.display = 'block';
            });
    });

    document.getElementById('btn-load-history').addEventListener('click', function() {
        var loading = document.getElementById('history-loading');
        var errEl = document.getElementById('history-error');
        var wrap = document.getElementById('history-table-wrap');
        var tbody = document.getElementById('history-tbody');
        var paginationEl = document.getElementById('history-pagination');
        errEl.style.display = 'none';
        loading.style.display = 'flex';
        wrap.style.display = 'none';
        paginationEl.style.display = 'none';

        fetch(base + '/shipment-history', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                loading.style.display = 'none';
                if (res.success && res.data) {
                    var shipments = res.data.shipments || [];
                    var pagination = res.data.pagination || {};
                    tbody.innerHTML = shipments.length === 0
                        ? '<tr><td colspan="5" style="padding:2rem;text-align:center;color:#6b7280">No shipments found.</td></tr>'
                        : shipments.map(function(s) {
                            return '<tr><td>' + escapeHtml(s.id || '') + '</td><td><code>' + escapeHtml(s.key || '') + '</code></td><td>' + escapeHtml(s.tracking_no || '') + '</td><td>' + escapeHtml(s.created_at || '') + '</td><td>' + escapeHtml(s.modified_at || '') + '</td></tr>';
                        }).join('');
                    wrap.style.display = 'block';
                    if (pagination.total_page != null) {
                        paginationEl.innerHTML = 'Page ' + (pagination.current_page || 1) + ' of ' + (pagination.total_page || 1) + ' &middot; ' + (pagination.total_item || 0) + ' items';
                        paginationEl.style.display = 'block';
                    }
                } else {
                    errEl.textContent = res.message || 'Failed to load shipment history';
                    errEl.style.display = 'block';
                }
            })
            .catch(function() {
                loading.style.display = 'none';
                errEl.textContent = 'Network error.';
                errEl.style.display = 'block';
            });
    });

    document.getElementById('form-consignment-note').addEventListener('submit', function(e) {
        e.preventDefault();
        var raw = (document.getElementById('consignment_tracking').value || '').trim();
        var trackingNos = raw.split(/[\n,]+/).map(function(s) { return s.trim(); }).filter(Boolean);
        if (trackingNos.length === 0) return;
        var loading = document.getElementById('consignment-loading');
        var errEl = document.getElementById('consignment-error');
        var resultEl = document.getElementById('consignment-result');
        var btn = document.getElementById('btn-consignment-note');
        errEl.style.display = 'none';
        resultEl.style.display = 'none';
        loading.style.display = 'flex';
        btn.disabled = true;

        var formData = new FormData();
        formData.append('_token', csrf);
        trackingNos.forEach(function(t) { formData.append('tracking_no[]', t); });

        fetch(base + '/consignment-note', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            body: formData
        })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                loading.style.display = 'none';
                btn.disabled = false;
                if (res.success) {
                    var data = res.data || {};
                    if (data.url) {
                        resultEl.innerHTML = '<a href="' + escapeHtml(data.url) + '" target="_blank" rel="noopener" class="myparcel-btn myparcel-btn-primary">Download Consignment Note</a>';
                    } else if (data.pdf_base64 || data.file) {
                        resultEl.innerHTML = '<p>Consignment note received. <a href="data:application/pdf;base64,' + (data.pdf_base64 || data.file) + '" target="_blank" download="consignment-note.pdf">Download PDF</a></p>';
                    } else {
                        resultEl.textContent = JSON.stringify(data);
                    }
                    resultEl.style.display = 'block';
                } else {
                    errEl.textContent = res.message || 'Failed to get consignment note';
                    errEl.style.display = 'block';
                }
            })
            .catch(function() {
                loading.style.display = 'none';
                btn.disabled = false;
                errEl.textContent = 'Network error.';
                errEl.style.display = 'block';
            });
    });

    ['sizes', 'types', 'statuses'].forEach(function(name) {
        var btn = document.getElementById('btn-load-' + name);
        var loading = document.getElementById(name + '-loading');
        var content = document.getElementById(name + '-content');
        var endpoint = name === 'sizes' ? 'parcel-sizes' : (name === 'types' ? 'content-types' : 'shipment-statuses');
        btn.addEventListener('click', function() {
            loading.style.display = 'flex';
            content.style.display = 'none';
            fetch(base + '/' + endpoint, { headers: { 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(function(data) {
                    loading.style.display = 'none';
                    if (data.success && data.data && typeof data.data === 'object') {
                        content.innerHTML = renderRefGrid(Object.entries(data.data));
                        content.style.display = 'block';
                    } else {
                        content.innerHTML = '<p class="myparcel-alert myparcel-alert-error">' + escapeHtml(data.message || 'Failed') + '</p>';
                        content.style.display = 'block';
                    }
                })
                .catch(function() {
                    loading.style.display = 'none';
                    content.innerHTML = '<p class="myparcel-alert myparcel-alert-error">Network error.</p>';
                    content.style.display = 'block';
                });
        });
    });
})();
    </script>
@endsection
