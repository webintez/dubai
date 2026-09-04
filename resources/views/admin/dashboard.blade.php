@extends('layouts.app')

@section('title', 'Admin Dashboard - Dubai VIP Meetings Portal')

@section('content')
<div class="admin-dashboard-layout">
    <!-- Top Header -->
    <header class="admin-header">
        <div class="admin-header-brand">
            <a href="{{ route('home') }}">
                <img src="{{ asset('logo.png') }}" alt="Dubai VIP Logo" class="admin-logo-img">
            </a>
            <span class="admin-badge"><i class="fa-solid fa-shield-halved"></i> Master Admin</span>
        </div>

        <div class="admin-header-actions">
            <a href="{{ route('home') }}" class="btn btn-outline-gold btn-sm" target="_blank">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Live Website
            </a>
            <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </div>
    </header>

    <!-- Main Content Container -->
    <div class="admin-main">
        <!-- Alerts -->
        @if(session('success'))
            <div class="alert-message alert-success animate-fade-in">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="alert-message alert-danger animate-fade-in">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div>
                    @foreach($errors->all() as $err)
                        <p>{{ $err }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Stats Overview Cards -->
        <div class="stats-overview-grid animate-slide-up">
            <div class="stat-card">
                <div class="stat-icon-box gold">
                    <i class="fa-solid fa-video"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-number">{{ $stats['total_meetings'] }}</span>
                    <span class="stat-label">Total Meetings</span>
                </div>
                <div class="stat-sub">
                    <span class="text-danger font-bold">🔴 {{ $stats['ongoing_meetings'] }} Live</span> &bull; 
                    <span class="gold-text font-bold">📅 {{ $stats['upcoming_meetings'] }} Upcoming</span> &bull;
                    <span class="text-muted">⏱️ {{ $stats['past_meetings'] }} Past</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon-box warning">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-number">{{ $stats['pending_bookings'] }}</span>
                    <span class="stat-label">Pending Verifications</span>
                </div>
                <div class="stat-sub">Awaiting QR screenshot review</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon-box success">
                    <i class="fa-solid fa-ticket"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-number">{{ $stats['approved_bookings'] }}</span>
                    <span class="stat-label">Approved Passes</span>
                </div>
                <div class="stat-sub">Access code & password granted</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon-box info">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-number">{{ $stats['total_bookings'] }}</span>
                    <span class="stat-label">Total Bookings</span>
                </div>
                <div class="stat-sub">Across all sessions</div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="admin-tabs-nav animate-slide-up">
            <button type="button" class="tab-btn active" data-tab="tab-bookings" id="btn-tab-bookings">
                <i class="fa-solid fa-receipt"></i> Bookings & Approvals 
                @if($stats['pending_bookings'] > 0)
                    <span class="tab-counter-badge">{{ $stats['pending_bookings'] }}</span>
                @endif
            </button>
            <button type="button" class="tab-btn" data-tab="tab-meetings" id="btn-tab-meetings">
                <i class="fa-solid fa-video"></i> Meetings Management ({{ $stats['total_meetings'] }})
            </button>
            <button type="button" class="tab-btn" data-tab="tab-settings" id="btn-tab-settings">
                <i class="fa-solid fa-sliders"></i> Portal Settings & QR Code
            </button>
        </div>

        <!-- TAB 1: BOOKINGS & APPROVALS -->
        <div class="tab-content active" id="tab-bookings">
            <section class="admin-card">
                <div class="card-header-flex">
                    <div>
                        <h2 class="card-title"><i class="fa-solid fa-file-invoice-dollar gold-icon"></i> Payment Proofs & Code Assignment</h2>
                        <p class="card-desc">Review uploaded bank transfer screenshots. Approve bookings and assign VIP Access Codes to unlock meeting passwords and join links.</p>
                    </div>

                    <!-- Filter Dropdown -->
                    <form action="{{ route('admin.dashboard') }}" method="GET" class="filter-row">
                        <select name="booking_status" onchange="this.form.submit()" class="admin-filter-select">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('booking_status') == 'pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="approved" {{ request('booking_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('booking_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        @if(request('booking_status'))
                            <a href="{{ route('admin.dashboard') }}" class="clear-filter-link" title="Clear Filter">
                                <i class="fa-solid fa-xmark"></i>
                            </a>
                        @endif
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Booking ID & Date</th>
                                <th>Attendee Details</th>
                                <th>Meeting Session</th>
                                <th>Price</th>
                                <th>Payment Proof</th>
                                <th>Status</th>
                                <th>Assigned Code</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                                <tr id="booking-row-{{ $booking->id }}">
                                    <td>
                                        <strong class="gold-text">#BK-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</strong>
                                        <div class="text-xs text-muted">{{ $booking->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-muted">{{ $booking->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <div class="font-bold">{{ $booking->name }}</div>
                                        <div class="text-xs text-muted"><i class="fa-regular fa-envelope"></i> {{ $booking->email }}</div>
                                        <div class="text-xs text-muted"><i class="fa-solid fa-phone"></i> {{ $booking->phone }}</div>
                                    </td>
                                    <td>
                                        <div class="font-bold font-sm" style="max-width: 200px;">
                                            {{ $booking->meeting->title ?? 'Meeting Removed' }}
                                        </div>
                                        <span class="meeting-type-mini type-{{ $booking->meeting->status ?? 'upcoming' }}">
                                            {{ strtoupper($booking->meeting->status ?? 'upcoming') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="gold-badge">{{ $booking->meeting->formatted_price ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        @if($booking->screenshot_path)
                                            <div class="screenshot-thumbnail-container zoom-img" data-large="{{ asset($booking->screenshot_path) }}">
                                                <img src="{{ asset($booking->screenshot_path) }}" class="screenshot-thumbnail" alt="Payment Proof">
                                                <span class="zoom-hover-overlay"><i class="fa-solid fa-magnifying-glass-plus"></i></span>
                                            </div>
                                        @else
                                            <span class="text-xs text-muted"><i class="fa-solid fa-minus"></i> No proof</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $booking->status }}">
                                            {{ strtoupper($booking->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($booking->assigned_code)
                                            <div class="code-pill">
                                                <i class="fa-solid fa-ticket gold-icon"></i>
                                                <span>{{ $booking->assigned_code }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-muted"><i class="fa-solid fa-lock"></i> Unassigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons-group">
                                            @if($booking->status !== 'approved')
                                                <!-- Approve & Assign Code Button -->
                                                <button type="button" class="btn-action-approve" onclick="openApproveModal({{ $booking->id }}, '{{ addslashes($booking->name) }}', '{{ $booking->assigned_code ?: 'DXB-VIP-' . strtoupper(\Illuminate\Support\Str::random(4)) . '-' . rand(100, 999) }}')" title="Approve & Assign Code">
                                                    <i class="fa-solid fa-circle-check"></i> Approve
                                                </button>
                                            @else
                                                <!-- Edit Assigned Code Button -->
                                                <button type="button" class="btn-action-edit-code" onclick="openApproveModal({{ $booking->id }}, '{{ addslashes($booking->name) }}', '{{ $booking->assigned_code }}')" title="Update Access Code">
                                                    <i class="fa-solid fa-pen-to-square"></i> Code
                                                </button>
                                            @endif

                                            @if($booking->status !== 'rejected')
                                                <form action="{{ route('admin.bookings.reject', $booking->id) }}" method="POST" onsubmit="return confirm('Reject this booking?')" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn-action-reject" title="Reject Payment">
                                                        <i class="fa-solid fa-ban"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('admin.bookings.delete', $booking->id) }}" method="POST" onsubmit="return confirm('Permanently delete this booking record?')" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action-delete" title="Delete Booking">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted" style="padding: 3rem;">
                                        <i class="fa-solid fa-inbox" style="font-size: 2.5rem; color: var(--gold-primary); display:block; margin-bottom: 0.5rem;"></i>
                                        No bookings match the selected criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-container">
                    {{ $bookings->appends(request()->query())->links() }}
                </div>
            </section>
        </div>

        <!-- TAB 2: MEETINGS MANAGEMENT -->
        <div class="tab-content" id="tab-meetings">
            <section class="admin-card">
                <div class="card-header-flex">
                    <div>
                        <h2 class="card-title"><i class="fa-solid fa-video gold-icon"></i> VIP Meetings Management</h2>
                        <p class="card-desc">Create, schedule, edit, and organize Ingoing (Live) and Upcoming meetings with links, duration, pricing, confidential passwords, and custom thumbnails.</p>
                    </div>

                    <button type="button" class="btn btn-gold btn-sm" onclick="openCreateMeetingModal()">
                        <i class="fa-solid fa-circle-plus"></i> Create New Meeting
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Thumbnail</th>
                                <th>Meeting Title & Description</th>
                                <th>Status / Type</th>
                                <th>Duration</th>
                                <th>Price</th>
                                <th>Meeting Password</th>
                                <th>Direct Link</th>
                                <th>Bookings</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($meetings as $meeting)
                                <tr>
                                    <td>
                                        @php
                                            $thumb = $meeting->thumbnail;
                                            $thumbUrl = $thumb ? (\Illuminate\Support\Str::startsWith($thumb, 'http') ? $thumb : asset($thumb)) : 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=400&q=80';
                                        @endphp
                                        <div class="admin-meeting-thumb-wrap">
                                            <img src="{{ $thumbUrl }}" alt="{{ $meeting->title }}" class="admin-meeting-thumb-img">
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="meeting-table-title">{{ $meeting->title }}</strong>
                                        <div class="text-xs text-muted" style="max-width: 250px;">{{ \Illuminate\Support\Str::limit($meeting->description, 70) }}</div>
                                        @if($meeting->start_time)
                                            <div class="text-xs gold-text mt-1"><i class="fa-regular fa-clock"></i> {{ $meeting->start_time->format('M d, Y h:i A') }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($meeting->isLive())
                                            <span class="meeting-status-pill pill-live">
                                                <span class="pulse-dot-red"></span> LIVE NOW
                                            </span>
                                        @elseif($meeting->isUpcoming())
                                            <span class="meeting-status-pill pill-upcoming">
                                                <i class="fa-regular fa-calendar-check"></i> UPCOMING
                                            </span>
                                        @else
                                            <span class="meeting-status-pill pill-past">
                                                <i class="fa-solid fa-clock-rotate-left"></i> PAST / ENDED
                                            </span>
                                        @endif
                                        <div class="text-xs text-muted mt-1" style="white-space: nowrap;">
                                            {{ $meeting->time_indicator }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-sm font-bold">{{ $meeting->duration }}</span>
                                    </td>
                                    <td>
                                        <span class="gold-badge">{{ $meeting->formatted_price }}</span>
                                    </td>
                                    <td>
                                        <div class="admin-password-box">
                                            <code>{{ $meeting->password }}</code>
                                            <button type="button" class="copy-tiny-btn" onclick="navigator.clipboard.writeText('{{ $meeting->password }}'); alert('Password copied: {{ $meeting->password }}');" title="Copy Password">
                                                <i class="fa-regular fa-copy"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ $meeting->link }}" target="_blank" class="admin-link-btn" title="{{ $meeting->link }}">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i> Open
                                        </a>
                                    </td>
                                    <td>
                                        <span class="stat-count-pill">{{ $meeting->bookings_count }} Bookings</span>
                                    </td>
                                    <td>
                                        <div class="action-buttons-group">
                                            <button type="button" class="btn-action-edit" onclick="openEditMeetingModal({{ json_encode($meeting) }})" title="Edit Meeting">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </button>
                                            <form action="{{ route('admin.meetings.delete', $meeting->id) }}" method="POST" onsubmit="return confirm('Delete this meeting? All associated bookings will also be deleted.')" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action-delete" title="Delete Meeting">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted" style="padding: 3rem;">
                                        No meetings found. Click "Create New Meeting" to add your first session.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <!-- TAB 3: SETTINGS & PAYMENT QR -->
        <div class="tab-content" id="tab-settings">
            <div class="admin-grid-settings">
                <!-- Settings Form -->
                <section class="admin-card">
                    <h2 class="card-title"><i class="fa-solid fa-qrcode gold-icon"></i> Payment QR & Gateway Settings</h2>
                    <p class="card-desc">Upload the official banking or crypto payment QR code shown to visitors when booking meeting passes.</p>
                    
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
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
                                        <i class="fa-solid fa-upload"></i> Choose QR Image
                                    </button>
                                    <span class="upload-hint">Recommended: Clear Square PNG or JPG</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 1.5rem;">
                            <label for="support_phone" class="form-label">Customer Support & WhatsApp Contact</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-phone input-icon"></i>
                                <input type="text" id="support_phone" name="support_phone" class="form-control" value="{{ old('support_phone', $settings['support_phone']) }}" required placeholder="+971 4 301 7777">
                            </div>
                            <span class="field-hint">Displayed across the VIP portal, user dashboard, and WhatsApp floating widget.</span>
                        </div>

                        <div class="form-group">
                            <label for="today_link" class="form-label">Optional Today Redirect Link</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-link input-icon"></i>
                                <input type="url" id="today_link" name="today_link" class="form-control" placeholder="https://..." value="{{ old('today_link', $settings['today_link']) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tomorrow_link" class="form-label">Optional Tomorrow Redirect Link</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-link input-icon"></i>
                                <input type="url" id="tomorrow_link" name="tomorrow_link" class="form-control" placeholder="https://..." value="{{ old('tomorrow_link', $settings['tomorrow_link']) }}">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-gold btn-block" style="margin-top: 1.5rem;">
                            <span>Save Portal Configurations <i class="fa-solid fa-circle-check"></i></span>
                        </button>
                    </form>
                </section>

                <!-- Instructions & QR Preview Card -->
                <section class="admin-card">
                    <h2 class="card-title"><i class="fa-solid fa-circle-info gold-icon"></i> Workflow Guide</h2>
                    <p class="card-desc">How attendee registrations, QR payments, and access codes operate:</p>

                    <div class="workflow-steps-list">
                        <div class="workflow-step-item">
                            <div class="workflow-step-num">1</div>
                            <div>
                                <strong>Free Registration:</strong> Visitors can sign up freely with no required fee to create a VIP account.
                            </div>
                        </div>
                        <div class="workflow-step-item">
                            <div class="workflow-step-num">2</div>
                            <div>
                                <strong>Meeting Booking:</strong> User picks an Ongoing or Upcoming meeting, scans your QR Code, and uploads their transfer screenshot.
                            </div>
                        </div>
                        <div class="workflow-step-item">
                            <div class="workflow-step-num">3</div>
                            <div>
                                <strong>Admin Verification & Code:</strong> You review the screenshot in the Bookings tab and click "Approve". An Access Code (e.g. <code>DXB-VIP-8291</code>) is assigned.
                            </div>
                        </div>
                        <div class="workflow-step-item">
                            <div class="workflow-step-num">4</div>
                            <div>
                                <strong>Instant Access Granted:</strong> The user's dashboard automatically displays the assigned code, reveals the confidential password, and activates the Join Meeting link!
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: APPROVE BOOKING & ASSIGN CODE -->
<div id="approveModal" class="admin-modal">
    <div class="admin-modal-dialog">
        <div class="admin-modal-header">
            <h3><i class="fa-solid fa-certificate gold-icon"></i> Approve Booking & Assign Code</h3>
            <button type="button" class="admin-modal-close" onclick="closeApproveModal()">&times;</button>
        </div>
        <form id="approveForm" method="POST">
            @csrf
            <div class="admin-modal-body">
                <p class="modal-subtext">Approving this payment will unlock the meeting password and join link for <strong id="modal-attendee-name" class="gold-text">Attendee</strong>.</p>
                
                <div class="form-group" style="margin-top: 1rem;">
                    <label for="modal-assigned-code" class="form-label">VIP Access Code</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-ticket input-icon"></i>
                        <input type="text" id="modal-assigned-code" name="assigned_code" class="form-control" placeholder="e.g. DXB-VIP-9821" required>
                    </div>
                    <span class="field-hint">A unique access code provided to the attendee upon approval. You can customize it or keep the auto-generated code.</span>
                </div>

                <div class="form-group" style="margin-top: 1rem;">
                    <label for="modal-admin-notes" class="form-label">Admin Notes (Optional)</label>
                    <textarea id="modal-admin-notes" name="admin_notes" class="form-control" rows="2" placeholder="e.g. Verified via Emirates NBD transfer"></textarea>
                </div>
            </div>
            <div class="admin-modal-footer">
                <button type="button" class="btn btn-outline-gold" onclick="closeApproveModal()">Cancel</button>
                <button type="submit" class="btn btn-gold btn-glowing">
                    <i class="fa-solid fa-check-double"></i> Confirm Approval & Assign Code
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: CREATE MEETING -->
<div id="createMeetingModal" class="admin-modal">
    <div class="admin-modal-dialog admin-modal-lg">
        <div class="admin-modal-header">
            <h3><i class="fa-solid fa-video gold-icon"></i> Create New VIP Meeting</h3>
            <button type="button" class="admin-modal-close" onclick="closeCreateMeetingModal()">&times;</button>
        </div>
        <form action="{{ route('admin.meetings.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="admin-modal-body">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Meeting Title *</label>
                        <input type="text" name="title" class="form-control" placeholder="Dubai Prime Real Estate & Visa Summit" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Scheduled Start Time *</label>
                        <input type="datetime-local" name="start_time" class="form-control" required>
                        <span class="field-hint" style="color: var(--gold-primary);"><i class="fa-solid fa-wand-magic-sparkles"></i> Status (Live, Upcoming, Past) is calculated automatically.</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description (Brief overview)</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Executive briefing on investment opportunities, offshore setups, and VIP access..."></textarea>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Meeting Join URL (Zoom, Meet, Teams) *</label>
                        <input type="url" name="link" class="form-control" placeholder="https://zoom.us/j/123456789" required>
                        <span class="field-hint">Kept hidden from attendees until their booking payment is approved.</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Meeting Password *</label>
                        <input type="text" name="password" class="form-control" placeholder="VIPDXB2026" required>
                        <span class="field-hint">Only revealed to approved attendees on their dashboard.</span>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Duration *</label>
                        <input type="text" name="duration" class="form-control" placeholder="60 Mins" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pricing / Fee *</label>
                        <input type="text" name="price" class="form-control" placeholder="150 AED" required>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Upload Thumbnail (Image file)</label>
                        <input type="file" name="thumbnail" accept="image/*" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Or Thumbnail URL (e.g. Unsplash)</label>
                        <input type="url" name="thumbnail_url" class="form-control" placeholder="https://images.unsplash.com/...">
                    </div>
                </div>
            </div>
            <div class="admin-modal-footer">
                <button type="button" class="btn btn-outline-gold" onclick="closeCreateMeetingModal()">Cancel</button>
                <button type="submit" class="btn btn-gold btn-glowing">
                    <i class="fa-solid fa-plus"></i> Save & Publish Meeting
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: EDIT MEETING -->
<div id="editMeetingModal" class="admin-modal">
    <div class="admin-modal-dialog admin-modal-lg">
        <div class="admin-modal-header">
            <h3><i class="fa-solid fa-pen-to-square gold-icon"></i> Edit VIP Meeting</h3>
            <button type="button" class="admin-modal-close" onclick="closeEditMeetingModal()">&times;</button>
        </div>
        <form id="editMeetingForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="admin-modal-body">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Meeting Title *</label>
                        <input type="text" id="edit_title" name="title" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Scheduled Start Time *</label>
                        <input type="datetime-local" id="edit_start_time" name="start_time" class="form-control" required>
                        <span class="field-hint" style="color: var(--gold-primary);"><i class="fa-solid fa-wand-magic-sparkles"></i> Status is automatically updated based on this time.</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea id="edit_description" name="description" class="form-control" rows="2"></textarea>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Meeting Join URL *</label>
                        <input type="url" id="edit_link" name="link" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Meeting Password *</label>
                        <input type="text" id="edit_password" name="password" class="form-control" required>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Duration *</label>
                        <input type="text" id="edit_duration" name="duration" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pricing *</label>
                        <input type="text" id="edit_price" name="price" class="form-control" required>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Replace Thumbnail File</label>
                        <input type="file" name="thumbnail" accept="image/*" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Or Thumbnail URL</label>
                        <input type="url" id="edit_thumbnail_url" name="thumbnail_url" class="form-control">
                    </div>
                </div>
            </div>
            <div class="admin-modal-footer">
                <button type="button" class="btn btn-outline-gold" onclick="closeEditMeetingModal()">Cancel</button>
                <button type="submit" class="btn btn-gold btn-glowing">
                    <i class="fa-solid fa-circle-check"></i> Update Meeting Details
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Image Lightbox Modal -->
<div id="imageLightbox" class="lightbox-modal">
    <span class="lightbox-close" id="closeLightboxBtn">&times;</span>
    <img class="lightbox-content" id="lightbox-img">
    <div id="lightbox-caption" class="lightbox-caption">Payment Screenshot Proof</div>
</div>

<style>
/* Admin Custom Dashboard Styles */
.admin-dashboard-layout {
    min-height: 100vh;
    background: #04060a;
    color: var(--text-primary);
    display: flex;
    flex-direction: column;
}
.admin-header {
    background: #090c13;
    border-bottom: 1px solid rgba(212, 175, 55, 0.15);
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.admin-header-brand {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.admin-logo-img {
    height: 55px;
    width: auto;
    filter: drop-shadow(0 2px 5px rgba(0,0,0,0.5));
}
.admin-badge {
    background: var(--gold-primary);
    color: #000;
    font-size: 0.75rem;
    font-weight: 800;
    padding: 0.2rem 0.6rem;
    border-radius: 4px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.admin-header-actions {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}
.logout-btn {
    background: transparent;
    border: none;
    color: var(--text-muted);
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: var(--transition-smooth);
    display: flex;
    align-items: center;
    gap: 0.4rem;
}
.logout-btn:hover { color: var(--danger-color); }

.admin-main {
    max-width: 1400px;
    width: 100%;
    margin: 0 auto;
    padding: 2rem;
    flex-grow: 1;
}

/* Stats Cards */
.stats-overview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.25rem;
    margin-bottom: 2rem;
}
.stat-card {
    background: #0c1017;
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 10px;
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    position: relative;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}
.stat-icon-box {
    width: 44px;
    height: 44px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    margin-bottom: 0.75rem;
}
.stat-icon-box.gold { background: rgba(212, 175, 55, 0.15); color: var(--gold-primary); }
.stat-icon-box.warning { background: rgba(255, 183, 3, 0.15); color: var(--pending-color); }
.stat-icon-box.success { background: rgba(46, 196, 182, 0.15); color: var(--success-color); }
.stat-icon-box.info { background: rgba(58, 134, 255, 0.15); color: #3a86ff; }
.stat-number {
    font-size: 1.8rem;
    font-weight: 800;
    line-height: 1;
    color: #fff;
}
.stat-label {
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-top: 0.2rem;
    display: block;
}
.stat-sub {
    margin-top: 0.75rem;
    padding-top: 0.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    font-size: 0.75rem;
    color: var(--text-muted);
}

/* Tabs */
.admin-tabs-nav {
    display: flex;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    padding-bottom: 0.5rem;
    overflow-x: auto;
}
.tab-btn {
    background: transparent;
    border: none;
    color: var(--text-muted);
    font-family: var(--font-body);
    font-size: 0.95rem;
    font-weight: 600;
    padding: 0.65rem 1.25rem;
    border-radius: 6px;
    cursor: pointer;
    transition: var(--transition-smooth);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    white-space: nowrap;
}
.tab-btn:hover {
    color: #fff;
    background: rgba(255, 255, 255, 0.03);
}
.tab-btn.active {
    background: rgba(212, 175, 55, 0.15);
    color: var(--gold-primary);
    border: 1px solid rgba(212, 175, 55, 0.3);
}
.tab-counter-badge {
    background: var(--pending-color);
    color: #000;
    font-size: 0.7rem;
    font-weight: 800;
    padding: 0.1rem 0.45rem;
    border-radius: 50px;
}

.tab-content { display: none; }
.tab-content.active { display: block; animation: fadeIn 0.3s ease; }

/* Admin Cards & Tables */
.admin-card {
    background: #0b0f16;
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 10px;
    padding: 1.75rem;
    box-shadow: 0 8px 25px rgba(0,0,0,0.4);
}
.card-header-flex {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    padding-bottom: 1rem;
}
.card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.card-desc {
    font-size: 0.85rem;
    color: var(--text-muted);
    margin-top: 0.2rem;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
}
.admin-table th {
    padding: 0.85rem 1rem;
    text-align: left;
    color: var(--gold-primary);
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    border-bottom: 2px solid rgba(212, 175, 55, 0.15);
    background: rgba(255, 255, 255, 0.01);
}
.admin-table td {
    padding: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.04);
    vertical-align: middle;
}
.admin-table tbody tr:hover {
    background: rgba(255, 255, 255, 0.015);
}

.screenshot-thumbnail-container {
    width: 50px;
    height: 50px;
    border-radius: 4px;
    overflow: hidden;
    position: relative;
    cursor: pointer;
    border: 1px solid rgba(212, 175, 55, 0.2);
}
.screenshot-thumbnail {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.zoom-hover-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: 0.2s;
    color: #fff;
    font-size: 0.8rem;
}
.screenshot-thumbnail-container:hover .zoom-hover-overlay { opacity: 1; }

.status-badge {
    font-size: 0.7rem;
    font-weight: 700;
    padding: 0.2rem 0.6rem;
    border-radius: 50px;
    letter-spacing: 0.5px;
    display: inline-block;
}
.status-badge.status-approved { background: rgba(46, 196, 182, 0.15); color: var(--success-color); border: 1px solid rgba(46, 196, 182, 0.3); }
.status-badge.status-pending { background: rgba(255, 183, 3, 0.15); color: var(--pending-color); border: 1px solid rgba(255, 183, 3, 0.3); }
.status-badge.status-rejected { background: rgba(230, 57, 70, 0.15); color: var(--danger-color); border: 1px solid rgba(230, 57, 70, 0.3); }

.code-pill {
    background: rgba(212, 175, 55, 0.12);
    border: 1px solid rgba(212, 175, 55, 0.35);
    padding: 0.25rem 0.6rem;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-family: monospace;
    font-weight: 700;
    color: #fff;
    font-size: 0.8rem;
}

.gold-badge {
    background: rgba(212, 175, 55, 0.1);
    color: var(--gold-primary);
    border: 1px solid rgba(212, 175, 55, 0.25);
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-weight: 700;
    font-size: 0.75rem;
}

.action-buttons-group {
    display: flex;
    align-items: center;
    gap: 0.4rem;
}
.btn-action-approve {
    background: rgba(46, 196, 182, 0.15);
    border: 1px solid rgba(46, 196, 182, 0.3);
    color: var(--success-color);
    padding: 0.35rem 0.75rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 700;
    cursor: pointer;
    transition: var(--transition-smooth);
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}
.btn-action-approve:hover { background: var(--success-color); color: #000; }

.btn-action-edit-code {
    background: rgba(212, 175, 55, 0.15);
    border: 1px solid rgba(212, 175, 55, 0.3);
    color: var(--gold-primary);
    padding: 0.35rem 0.6rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 700;
    cursor: pointer;
}
.btn-action-reject {
    background: rgba(230, 57, 70, 0.1);
    border: 1px solid rgba(230, 57, 70, 0.3);
    color: var(--danger-color);
    padding: 0.35rem 0.55rem;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.75rem;
}
.btn-action-delete {
    background: transparent;
    border: none;
    color: var(--text-muted);
    font-size: 0.95rem;
    cursor: pointer;
    padding: 0.3rem;
    transition: 0.2s;
}
.btn-action-delete:hover { color: var(--danger-color); }
.btn-action-edit {
    background: #171d26;
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #cbd5e1;
    padding: 0.35rem 0.6rem;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.8rem;
}

/* Meeting Specific Elements */
.admin-meeting-thumb-wrap {
    width: 60px;
    height: 48px;
    border-radius: 4px;
    overflow: hidden;
    border: 1px solid rgba(212, 175, 55, 0.2);
}
.admin-meeting-thumb-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.meeting-table-title {
    color: #fff;
    font-size: 0.9rem;
    display: block;
}
.meeting-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.72rem;
    font-weight: 800;
    padding: 0.3rem 0.65rem;
    border-radius: 4px;
    letter-spacing: 0.5px;
}
.meeting-status-pill.pill-live {
    background: rgba(230, 57, 70, 0.2);
    color: #ff8b94;
    border: 1px solid rgba(230, 57, 70, 0.45);
}
.meeting-status-pill.pill-upcoming {
    background: rgba(212, 175, 55, 0.15);
    color: var(--gold-primary);
    border: 1px solid rgba(212, 175, 55, 0.35);
}
.meeting-status-pill.pill-past {
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-muted);
    border: 1px solid rgba(255, 255, 255, 0.1);
}
.admin-password-box {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    background: rgba(0,0,0,0.5);
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-family: monospace;
    font-size: 0.8rem;
    color: #f3e5ab;
}
.copy-tiny-btn {
    background: transparent;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    font-size: 0.75rem;
}
.copy-tiny-btn:hover { color: var(--gold-primary); }
.admin-link-btn {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: var(--text-muted);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
}
.admin-link-btn:hover { color: #fff; background: rgba(255, 255, 255, 0.1); }
.stat-count-pill {
    background: rgba(255, 255, 255, 0.04);
    color: var(--text-muted);
    padding: 0.2rem 0.5rem;
    border-radius: 50px;
    font-size: 0.75rem;
}

/* Settings Grid */
.admin-grid-settings {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 2rem;
}
@media (max-width: 900px) {
    .admin-grid-settings { grid-template-columns: 1fr; }
}

.workflow-steps-list {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    margin-top: 1.25rem;
}
.workflow-step-item {
    display: flex;
    gap: 1rem;
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.04);
    padding: 1rem;
    border-radius: 8px;
    font-size: 0.85rem;
    line-height: 1.5;
}
.workflow-step-num {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: rgba(212, 175, 55, 0.2);
    color: var(--gold-primary);
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* Modals */
.admin-modal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 999;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(8px);
    overflow-y: auto;
    padding: 2rem 1rem;
    align-items: center;
    justify-content: center;
}
.admin-modal.active { display: flex; animation: fadeIn 0.25s ease; }
.admin-modal-dialog {
    background: #0e131b;
    border: 1px solid rgba(212, 175, 55, 0.3);
    border-radius: 10px;
    width: 100%;
    max-width: 550px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.6);
}
.admin-modal-lg { max-width: 800px; }
.admin-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}
.admin-modal-header h3 {
    font-size: 1.15rem;
    color: #fff;
}
.admin-modal-close {
    background: transparent;
    border: none;
    color: var(--text-muted);
    font-size: 1.5rem;
    cursor: pointer;
}
.admin-modal-close:hover { color: #fff; }
.admin-modal-body {
    padding: 1.5rem;
}
.admin-modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    background: rgba(0,0,0,0.2);
}

.form-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}
.form-grid-3 {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 1rem;
}
@media (max-width: 650px) {
    .form-grid-2, .form-grid-3 { grid-template-columns: 1fr; }
}

.modal-subtext {
    font-size: 0.85rem;
    color: var(--text-muted);
    line-height: 1.5;
}
.meeting-type-mini {
    display: inline-block;
    font-size: 0.65rem;
    font-weight: 700;
    padding: 0.15rem 0.45rem;
    border-radius: 3px;
    margin-top: 0.2rem;
}
.meeting-type-mini.type-ongoing { background: rgba(230, 57, 70, 0.2); color: #ff8b94; }
.meeting-type-mini.type-upcoming { background: rgba(212, 175, 55, 0.15); color: var(--gold-primary); }
</style>

@push('scripts')
<script>
// Tab Switching
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        
        this.classList.add('active');
        const targetTab = this.getAttribute('data-tab');
        const targetContent = document.getElementById(targetTab);
        if (targetContent) {
            targetContent.classList.add('active');
        }
    });
});

// Lightbox
const lightbox = document.getElementById('imageLightbox');
const lightboxImg = document.getElementById('lightbox-img');
const closeLightboxBtn = document.getElementById('closeLightboxBtn');

document.querySelectorAll('.zoom-img').forEach(el => {
    el.addEventListener('click', function() {
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

// Approve & Assign Code Modal
function openApproveModal(bookingId, attendeeName, code) {
    document.getElementById('approveForm').action = '/admin/bookings/' + bookingId + '/approve';
    document.getElementById('modal-attendee-name').textContent = attendeeName;
    document.getElementById('modal-assigned-code').value = code;
    document.getElementById('approveModal').classList.add('active');
}
function closeApproveModal() {
    document.getElementById('approveModal').classList.remove('active');
}

// Create Meeting Modal
function openCreateMeetingModal() {
    document.getElementById('createMeetingModal').classList.add('active');
}
function closeCreateMeetingModal() {
    document.getElementById('createMeetingModal').classList.remove('active');
}

// Edit Meeting Modal
function openEditMeetingModal(meeting) {
    document.getElementById('editMeetingForm').action = '/admin/meetings/' + meeting.id;
    document.getElementById('edit_title').value = meeting.title;
    document.getElementById('edit_description').value = meeting.description || '';
    document.getElementById('edit_link').value = meeting.link;
    document.getElementById('edit_password').value = meeting.password;
    document.getElementById('edit_duration').value = meeting.duration;
    document.getElementById('edit_price').value = meeting.price;
    
    if (meeting.start_time) {
        // Format for datetime-local (YYYY-MM-DDTHH:MM)
        const d = new Date(meeting.start_time);
        const pad = n => String(n).padStart(2, '0');
        const formatted = d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()) + 'T' + pad(d.getHours()) + ':' + pad(d.getMinutes());
        document.getElementById('edit_start_time').value = formatted;
    } else {
        document.getElementById('edit_start_time').value = '';
    }

    if (meeting.thumbnail && meeting.thumbnail.startsWith('http')) {
        document.getElementById('edit_thumbnail_url').value = meeting.thumbnail;
    } else {
        document.getElementById('edit_thumbnail_url').value = '';
    }

    document.getElementById('editMeetingModal').classList.add('active');
}
function closeEditMeetingModal() {
    document.getElementById('editMeetingModal').classList.remove('active');
}

// QR Selection Preview
const qrInput = document.getElementById('payment_qr');
const qrDisplay = document.getElementById('qr-display');
const selectBtn = document.querySelector('.select-qr-btn');

if (selectBtn && qrInput) {
    selectBtn.addEventListener('click', () => qrInput.click());
}
if (qrInput) {
    qrInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (qrDisplay) {
                    qrDisplay.src = e.target.result;
                } else {
                    const container = document.querySelector('.qr-thumbnail-box');
                    container.innerHTML = `<img src="${e.target.result}" id="qr-display" class="admin-qr-img">`;
                }
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
}
</script>
@endpush
@endsection
