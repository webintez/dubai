@extends('layouts.app')

@section('title', 'Admin Dashboard - Dubai VIP Portal')

@section('content')
<div class="admin-dashboard-layout">
    <!-- Top Header -->
    <header class="admin-header">
        <div class="logo">
            <a href="{{ route('home') }}">
                <img src="{{ asset('logo.png') }}" alt="Dubai VIP Logo" class="logo-img" style="height: 35px; vertical-align: middle;">
            </a>
            <span class="admin-badge">Admin</span>
        </div>
        <div class="header-actions">
            <a href="{{ route('home') }}" class="btn btn-outline-gold" style="padding: 0.5rem 1.2rem; font-size: 0.85rem;" target="_blank">
                <i class="fa-solid fa-earth-americas"></i> Live Site
            </a>
            <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">
                    Logout <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </header>

    <!-- Main Container -->
    <div class="admin-main">
        <!-- Display general messages -->
        @if(session('success'))
            <div class="alert-message alert-success">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-message alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i> Correct validation issues before updating settings.
            </div>
        @endif

        <div class="admin-grid">
            <!-- COLUMN 1: Settings Form -->
            <section class="admin-card settings-card">
                <h2 class="card-title"><i class="fa-solid fa-sliders gold-icon"></i> Portal Configurations</h2>
                <p class="card-desc">Configure redirect links, support contacts, and upload payment QR codes.</p>
                
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-group">
                        <label for="today_link" class="form-label">Today Button Redirect URL</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-link input-icon"></i>
                            <input type="url" id="today_link" name="today_link" class="form-control" placeholder="Leave empty for local form" value="{{ old('today_link', $settings['today_link']) }}">
                        </div>
                        <span class="field-hint">If specified, clicking the 'Today' button will open this URL directly. Leave empty to open the registration form.</span>
                        @error('today_link')
                            <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tomorrow_link" class="form-label">Tomorrow Button Redirect URL</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-link input-icon"></i>
                            <input type="url" id="tomorrow_link" name="tomorrow_link" class="form-control" placeholder="Leave empty for local form" value="{{ old('tomorrow_link', $settings['tomorrow_link']) }}">
                        </div>
                        <span class="field-hint">If specified, clicking the 'Tomorrow' button will open this URL directly. Leave empty to open the registration form.</span>
                        @error('tomorrow_link')
                            <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="support_phone" class="form-label">Customer Support Number</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-phone input-icon"></i>
                            <input type="text" id="support_phone" name="support_phone" class="form-control" placeholder="+971 4 301 7777" value="{{ old('support_phone', $settings['support_phone']) }}" required>
                        </div>
                        @error('support_phone')
                            <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Payment QR Code</label>
                        <div class="admin-qr-preview-container">
                            <div class="qr-thumbnail-box">
                                @if($settings['payment_qr'])
                                    <img src="{{ asset($settings['payment_qr']) }}" id="qr-display" class="admin-qr-img">
                                @else
                                    <div class="admin-qr-placeholder" id="qr-preview-ph">
                                        <i class="fa-solid fa-qrcode"></i>
                                        <span>No QR Uploaded</span>
                                    </div>
                                @endif
                            </div>
                            <div class="qr-upload-actions">
                                <input type="file" id="payment_qr" name="payment_qr" accept="image/*" class="qr-file-input">
                                <button type="button" class="btn btn-outline-gold select-qr-btn">
                                    <i class="fa-solid fa-upload"></i> Upload QR Image
                                </button>
                                <span class="upload-hint">JPG, PNG, WEBP (Max 2MB)</span>
                            </div>
                        </div>
                        @error('payment_qr')
                            <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-gold btn-block" style="margin-top: 1.5rem;">
                        <span>Save Configuration <i class="fa-solid fa-circle-check"></i></span>
                    </button>
                </form>
            </section>

            <!-- COLUMN 2: Submissions Dashboard -->
            <section class="admin-card submissions-card">
                <div class="submissions-header">
                    <div>
                        <h2 class="card-title"><i class="fa-solid fa-users gold-icon"></i> Registrations & Submissions</h2>
                        <p class="card-desc">Review and manage VIP pass registrations, payments, and confirmations.</p>
                    </div>
                    
                    <!-- Filters -->
                    <div class="filters-container">
                        <form action="{{ route('admin.dashboard') }}" method="GET" class="filters-form">
                            <select name="day" onchange="this.form.submit()" class="filter-select">
                                <option value="">All Days</option>
                                <option value="today" {{ request('day') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="tomorrow" {{ request('day') == 'tomorrow' ? 'selected' : '' }}>Tomorrow</option>
                            </select>
                            
                            <select name="status" onchange="this.form.submit()" class="filter-select">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>

                            @if(request('day') || request('status'))
                                <a href="{{ route('admin.dashboard') }}" class="clear-filters-btn" title="Clear Filters">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                </a>
                            @endif
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="submissions-table">
                        <thead>
                            <tr>
                                <th>Date/Day</th>
                                <th>Visitor Info</th>
                                <th>Proof of Payment</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($submissions as $sub)
                                <tr id="sub-row-{{ $sub->id }}">
                                    <td>
                                        <div class="cell-day font-bold uppercase">{{ $sub->day }}</div>
                                        <div class="cell-date text-xs text-muted">{{ $sub->created_at->format('M d, H:i') }}</div>
                                    </td>
                                    <td>
                                        <div class="cell-name font-bold">{{ $sub->name }}</div>
                                        <div class="cell-email text-xs text-muted"><i class="fa-regular fa-envelope"></i> {{ $sub->email }}</div>
                                        <div class="cell-phone text-xs text-muted"><i class="fa-solid fa-phone"></i> {{ $sub->phone }}</div>
                                    </td>
                                    <td>
                                        @if($sub->screenshot_path)
                                            <div class="screenshot-thumbnail-container">
                                                <img src="{{ asset($sub->screenshot_path) }}" class="screenshot-thumbnail zoom-img" data-large="{{ asset($sub->screenshot_path) }}" alt="Screenshot Proof">
                                                <span class="zoom-hover-overlay"><i class="fa-solid fa-magnifying-glass-plus"></i></span>
                                            </div>
                                        @else
                                            <span class="text-xs text-muted"><i class="fa-solid fa-circle-minus"></i> No proof uploaded</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="status-dropdown-wrapper">
                                            <select class="status-select status-{{ $sub->status }}" data-id="{{ $sub->id }}" onchange="updateSubStatus(this)">
                                                <option value="pending" {{ $sub->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="approved" {{ $sub->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                                <option value="rejected" {{ $sub->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                            <span class="status-spinner hidden" id="spinner-{{ $sub->id }}"><i class="fa-solid fa-spinner fa-spin"></i></span>
                                        </div>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.submissions.delete', $sub->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this submission?')" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-delete-btn" title="Delete Submission">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted" style="padding: 3rem;">
                                        <i class="fa-solid fa-users-slash" style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem; color: var(--gold-primary);"></i>
                                        No registrations found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination links -->
                <div class="pagination-container">
                    {{ $submissions->appends(request()->query())->links() }}
                </div>
            </section>
        </div>
    </div>
</div>

<!-- Image Lightbox Modal -->
<div id="imageLightbox" class="lightbox-modal">
    <span class="lightbox-close" id="closeLightboxBtn">&times;</span>
    <img class="lightbox-content" id="lightbox-img">
    <div id="lightbox-caption" class="lightbox-caption">Screenshot Proof</div>
</div>

<!-- Dashboard Specific Extra Styling -->
<style>
    /* Admin dashboard layout overrides and grid system */
    .admin-dashboard-layout {
        min-height: 100vh;
        background: #06080c;
        display: flex;
        flex-direction: column;
    }
    
    .admin-header {
        background: #0a0d13;
        border-bottom: 1px solid rgba(212, 175, 55, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 2rem;
    }

    .admin-badge {
        font-size: 0.75rem;
        background: var(--gold-primary);
        color: var(--text-dark);
        padding: 0.15rem 0.5rem;
        border-radius: 3px;
        vertical-align: middle;
        font-weight: 700;
        margin-left: 0.5rem;
        letter-spacing: 0;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .logout-btn {
        background: transparent;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        font-weight: 600;
        font-family: var(--font-body);
        font-size: 0.95rem;
        transition: var(--transition-smooth);
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .logout-btn:hover {
        color: var(--danger-color);
    }

    .admin-main {
        flex-grow: 1;
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    .alert-message {
        padding: 1rem 1.5rem;
        border-radius: 6px;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        font-weight: 600;
    }

    .alert-success {
        background: rgba(46, 196, 182, 0.1);
        color: var(--success-color);
        border: 1px solid rgba(46, 196, 182, 0.2);
    }

    .alert-danger {
        background: rgba(230, 57, 70, 0.1);
        color: var(--danger-color);
        border: 1px solid rgba(230, 57, 70, 0.2);
    }

    .admin-grid {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 2rem;
    }

    .admin-card {
        background: #0f131a;
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }

    .card-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 0.3rem;
        display: flex;
        align-items: center;
    }

    .card-desc {
        color: var(--text-muted);
        font-size: 0.85rem;
        margin-bottom: 2rem;
    }

    .field-hint {
        display: block;
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 0.4rem;
    }

    /* QR Code upload elements in dashboard */
    .admin-qr-preview-container {
        display: flex;
        gap: 1.25rem;
        align-items: center;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        padding: 1rem;
        border-radius: 6px;
    }

    .qr-thumbnail-box {
        width: 90px;
        height: 90px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px dashed rgba(255, 255, 255, 0.1);
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .admin-qr-img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .admin-qr-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        color: var(--text-muted);
        font-size: 0.75rem;
        text-align: center;
    }

    .admin-qr-placeholder i {
        font-size: 1.5rem;
        color: var(--gold-primary);
        margin-bottom: 0.3rem;
    }

    .qr-upload-actions {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
        position: relative;
    }

    .qr-file-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 5;
    }

    .select-qr-btn {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
    }

    /* Submissions card header & table styling */
    .submissions-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
    }

    .filters-form {
        display: flex;
        gap: 0.8rem;
        align-items: center;
    }

    .filter-select {
        background: #171d26;
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: var(--text-primary);
        padding: 0.45rem 1rem;
        border-radius: 4px;
        font-family: var(--font-body);
        font-size: 0.85rem;
        outline: none;
        cursor: pointer;
        transition: var(--transition-smooth);
    }

    .filter-select:focus {
        border-color: var(--gold-primary);
    }

    .clear-filters-btn {
        color: var(--text-muted);
        font-size: 1.25rem;
        transition: var(--transition-smooth);
    }

    .clear-filters-btn:hover {
        color: var(--danger-color);
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .submissions-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .submissions-table th {
        padding: 1rem;
        border-bottom: 2px solid rgba(255, 255, 255, 0.05);
        color: var(--gold-primary);
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .submissions-table td {
        padding: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        vertical-align: middle;
    }

    .cell-day {
        letter-spacing: 1px;
    }

    /* Screenshot thumbnail */
    .screenshot-thumbnail-container {
        position: relative;
        width: 60px;
        height: 60px;
        border-radius: 4px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
        cursor: pointer;
    }

    .screenshot-thumbnail {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition-smooth);
    }

    .zoom-hover-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(4, 5, 8, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.9rem;
        opacity: 0;
        transition: var(--transition-smooth);
    }

    .screenshot-thumbnail-container:hover .zoom-hover-overlay {
        opacity: 1;
    }

    .screenshot-thumbnail-container:hover .screenshot-thumbnail {
        transform: scale(1.15);
    }

    /* Status Selection box */
    .status-dropdown-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-select {
        padding: 0.35rem 0.8rem;
        border-radius: 50px;
        font-family: var(--font-body);
        font-size: 0.8rem;
        font-weight: 600;
        outline: none;
        border: 1px solid transparent;
        cursor: pointer;
        transition: var(--transition-smooth);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-select.status-pending {
        background: rgba(255, 183, 3, 0.15);
        color: var(--pending-color);
        border-color: rgba(255, 183, 3, 0.25);
    }

    .status-select.status-approved {
        background: rgba(46, 196, 182, 0.15);
        color: var(--success-color);
        border-color: rgba(46, 196, 182, 0.25);
    }

    .status-select.status-rejected {
        background: rgba(230, 57, 70, 0.15);
        color: var(--danger-color);
        border-color: rgba(230, 57, 70, 0.25);
    }

    .status-spinner {
        color: var(--gold-primary);
        font-size: 0.85rem;
    }

    .action-delete-btn {
        background: transparent;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        font-size: 1.1rem;
        transition: var(--transition-smooth);
    }

    .action-delete-btn:hover {
        color: var(--danger-color);
    }

    .pagination-container {
        margin-top: 2rem;
    }

    /* Custom elegant pagination styles to override bootstrap defaults */
    .pagination-container nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .pagination-container .relative {
        background: #171d26 !important;
        border-color: rgba(255,255,255,0.05) !important;
        color: var(--text-primary) !important;
    }

    /* Lightbox Modal */
    .lightbox-modal {
        display: none;
        position: fixed;
        z-index: 200;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(4, 5, 8, 0.95);
        backdrop-filter: blur(12px);
    }

    .lightbox-content {
        margin: auto;
        display: block;
        max-width: 80%;
        max-height: 75vh;
        object-fit: contain;
        border-radius: 4px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        animation: scaleIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .lightbox-caption {
        margin: auto;
        display: block;
        width: 80%;
        text-align: center;
        color: var(--text-primary);
        padding: 1rem 0;
        font-weight: 500;
        letter-spacing: 1px;
    }

    .lightbox-close {
        position: absolute;
        top: 30px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
        cursor: pointer;
    }

    .lightbox-close:hover,
    .lightbox-close:focus {
        color: var(--gold-primary);
    }

    /* Responsive grid settings */
    @media (max-width: 1024px) {
        .admin-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .admin-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }
        .header-actions {
            width: 100%;
            justify-content: space-between;
        }
        .admin-main {
            padding: 1rem;
        }
    }
</style>
@endsection

@push('scripts')
<script>
    // Preview QR Code selection locally before saving
    const qrInput = document.getElementById('payment_qr');
    const qrDisplay = document.getElementById('qr-display');
    const qrPreviewPH = document.getElementById('qr-preview-ph');
    const selectBtn = document.querySelector('.select-qr-btn');

    if (selectBtn && qrInput) {
        selectBtn.addEventListener('click', () => {
            qrInput.click();
        });
    }

    if (qrInput) {
        qrInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (qrDisplay) {
                        qrDisplay.src = e.target.result;
                    } else {
                        // If no image element was there (no QR set previously), replace placeholder
                        const container = document.querySelector('.qr-thumbnail-box');
                        container.innerHTML = `<img src="${e.target.result}" id="qr-display" class="admin-qr-img">`;
                    }
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    // Lightbox modal functionality
    const lightbox = document.getElementById('imageLightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const closeLightboxBtn = document.getElementById('closeLightboxBtn');
    
    document.querySelectorAll('.zoom-img').forEach(img => {
        img.addEventListener('click', function() {
            lightbox.style.display = "block";
            lightboxImg.src = this.getAttribute('data-large');
            document.body.style.overflow = 'hidden';
        });
    });

    if (closeLightboxBtn) {
        closeLightboxBtn.addEventListener('click', function() {
            lightbox.style.display = "none";
            document.body.style.overflow = '';
        });
    }

    if (lightbox) {
        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) {
                lightbox.style.display = "none";
                document.body.style.overflow = '';
            }
        });
    }

    // AJAX status updates
    function updateSubStatus(selectEl) {
        const subId = selectEl.getAttribute('data-id');
        const newStatus = selectEl.value;
        const spinner = document.getElementById(`spinner-${subId}`);
        
        // Show spinner
        if (spinner) spinner.classList.remove('hidden');
        selectEl.disabled = true;

        fetch(`/admin/submissions/${subId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            // Hide spinner
            if (spinner) spinner.classList.add('hidden');
            selectEl.disabled = false;

            if (res.status === 200 && res.body.success) {
                // Update select class for style coloring
                selectEl.className = `status-select status-${newStatus}`;
            } else {
                alert('Failed to update status. Please try again.');
            }
        })
        .catch(err => {
            if (spinner) spinner.classList.add('hidden');
            selectEl.disabled = false;
            console.error('Error:', err);
            alert('Connection error. Failed to update status.');
        });
    }
</script>
@endpush
