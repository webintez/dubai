@extends('layouts.app')

@section('title', 'Dubai VIP Meetings - Executive Summits & Private Sessions')

@section('content')
<div class="portal-main-wrapper">
    <!-- Top Navigation Header -->
    <header class="main-navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('logo.png') }}" alt="Dubai VIP Logo" class="brand-logo">
                </a>
            </div>

            <nav class="nav-links-center">
                <a href="#ongoing-section" class="nav-link">
                    <span class="live-dot-mini"></span> Ingoing Meetings
                </a>
                <a href="#upcoming-section" class="nav-link">
                    <i class="fa-regular fa-calendar"></i> Upcoming Schedule
                </a>
                <a href="tel:{{ $supportPhone }}" class="nav-link">
                    <i class="fa-solid fa-headset gold-icon"></i> Concierge: {{ $supportPhone }}
                </a>
            </nav>

            <div class="nav-auth-actions">
                @auth
                    <a href="{{ route('user.bookings') }}" class="btn btn-gold btn-sm btn-glowing">
                        <i class="fa-solid fa-ticket"></i> My Meeting Passes
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-auth-link" title="Logout">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-gold btn-sm">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i> VIP Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-gold btn-sm">
                        <i class="fa-solid fa-gift"></i> Free Register
                    </a>
                @endauth

                @if(session('admin_logged_in'))
                    <a href="{{ route('admin.dashboard') }}" class="admin-quick-badge" title="Go to Admin Dashboard">
                        <i class="fa-solid fa-shield-halved"></i> Admin
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="portal-hero">
        <div class="hero-overlay-dark"></div>
        <div class="hero-inner-container">
            <div class="hero-content animate-slide-up">
                <div class="hero-badge">
                    <span class="luxury-gold-pill"><i class="fa-solid fa-crown"></i> OFFICIAL DUBAI VIP MEETING GATEWAY</span>
                </div>
                
                <h1 class="hero-main-title">
                    Executive Dubai VIP Summits & <br>
                    <span class="gold-gradient-text">Private Closed-Door Briefings</span>
                </h1>

                <p class="hero-description">
                    Connect directly with Dubai's top real estate developers, wealth managers, and business luminaries. Book live ingoing sessions or reserve upcoming scheduled masterclasses with verified VIP access codes.
                </p>

                <!-- CTA Action Buttons -->
                <div class="hero-action-buttons">
                    <a href="#ongoing-section" class="btn btn-gold btn-glowing">
                        <span class="btn-content">
                            <i class="fa-solid fa-circle-play"></i> Ingoing Live Meetings ({{ $ongoingMeetings->count() }})
                        </span>
                    </a>
                    <a href="#today-section" class="btn btn-outline-gold">
                        <span class="btn-content">
                            <i class="fa-solid fa-calendar-day"></i> Today ({{ $todayCount }})
                        </span>
                    </a>
                    <a href="#tomorrow-section" class="btn btn-outline-gold">
                        <span class="btn-content">
                            <i class="fa-solid fa-calendar-plus"></i> Tomorrow ({{ $tomorrowCount }})
                        </span>
                    </a>
                    <a href="#upcoming-section" class="btn btn-outline-gold">
                        <span class="btn-content">
                            <i class="fa-regular fa-calendar-check"></i> Upcoming Schedule ({{ $upcomingMeetings->count() }})
                        </span>
                    </a>
                </div>

                <!-- Feature Strip -->
                <div class="feature-strip">
                    <div class="feature-item">
                        <i class="fa-solid fa-shield-halved gold-icon"></i>
                        <div>
                            <strong>Verified VIP Access</strong>
                            <span>Admin approved codes</span>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fa-solid fa-key gold-icon"></i>
                        <div>
                            <strong>Confidential Passwords</strong>
                            <span>Protected meeting credentials</span>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fa-solid fa-video gold-icon"></i>
                        <div>
                            <strong>Direct Room Links</strong>
                            <span>Zoom & Google Meet integration</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 1: INGOING / LIVE MEETINGS -->
    <section class="meetings-section" id="ongoing-section">
        <div class="section-container">
            <div class="section-header-box">
                <div class="section-badge-live">
                    <span class="pulse-live-indicator"></span> HAPPENING NOW &bull; LIVE ACCESS
                </div>
                <h2 class="section-title">Ingoing VIP Meetings</h2>
                <p class="section-subtitle">
                    Active sessions in progress right now. Scan the QR code to complete rapid payment, submit your verification screenshot, and receive your VIP access code to join immediately.
                </p>
            </div>

            @if($ongoingMeetings->count() > 0)
                <div class="meetings-grid">
                    @foreach($ongoingMeetings as $meeting)
                        <div class="meeting-card live-border animate-slide-up" data-meeting-day="today">
                            <!-- Card Image -->
                            <div class="meeting-card-media">
                                @php
                                    $thumb = $meeting->thumbnail;
                                    $thumbUrl = $thumb ? (\Illuminate\Support\Str::startsWith($thumb, 'http') ? $thumb : asset($thumb)) : 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=600&q=80';
                                @endphp
                                <img src="{{ $thumbUrl }}" alt="{{ $meeting->title }}" class="meeting-card-img">
                                
                                <div class="media-overlay-badges">
                                    <span class="live-tag">
                                        <span class="pulse-dot-red"></span> LIVE NOW
                                    </span>
                                    <span class="price-pill-gold">{{ $meeting->formatted_price }}</span>
                                </div>
                            </div>

                            <!-- Card Content -->
                            <div class="meeting-card-body">
                                <div class="meeting-meta-top">
                                    <span class="meta-tag"><i class="fa-regular fa-clock gold-icon"></i> {{ $meeting->duration }}</span>
                                    <span class="meta-tag gold-text"><i class="fa-solid fa-hourglass-half"></i> {{ $meeting->time_indicator }}</span>
                                </div>

                                <h3 class="meeting-title">{{ $meeting->title }}</h3>
                                <p class="meeting-desc">{{ \Illuminate\Support\Str::limit($meeting->description, 120) }}</p>

                                <div class="meeting-card-footer">
                                    <div class="pricing-display">
                                        <span class="pricing-label">Access Pass Fee</span>
                                        <strong class="pricing-amount">{{ $meeting->formatted_price }}</strong>
                                    </div>

                                    <button type="button" class="btn btn-gold btn-glowing book-trigger-btn" 
                                            onclick="openBookingModal({{ $meeting->id }})">
                                        <span class="btn-content">
                                            <i class="fa-solid fa-bolt"></i> Book & Join Now
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-meetings-box">
                    <i class="fa-solid fa-satellite-dish gold-icon empty-icon"></i>
                    <h3>No Meetings Currently In Progress</h3>
                    <p>There are no live meetings taking place at this exact moment. Please browse our upcoming scheduled meetings below to reserve your pass in advance.</p>
                    <a href="#upcoming-section" class="btn btn-outline-gold btn-sm" style="margin-top: 1rem;">
                        View Upcoming Sessions
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- SECTION 2: TODAY'S SESSIONS -->
    <section class="meetings-section today-section-bg" id="today-section">
        <div class="section-container">
            <div class="section-header-box">
                <div class="section-badge-today">
                    <i class="fa-solid fa-calendar-day"></i> TODAY'S VIP SESSIONS &bull; {{ date('l, M d, Y') }}
                </div>
                <h2 class="section-title">Today's VIP Sessions ({{ $todayCount }})</h2>
                <p class="section-subtitle">
                    All exclusive briefings and summits taking place today. Instant pass booking with priority admin verification.
                </p>
            </div>

            @if($todayMeetings->count() > 0)
                <div class="meetings-grid">
                    @foreach($todayMeetings as $meeting)
                        <div class="meeting-card {{ $meeting->isLive() ? 'live-border' : 'upcoming-card' }} animate-slide-up">
                            <!-- Card Image -->
                            <div class="meeting-card-media">
                                @php
                                    $thumb = $meeting->thumbnail;
                                    $thumbUrl = $thumb ? (\Illuminate\Support\Str::startsWith($thumb, 'http') ? $thumb : asset($thumb)) : 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=600&q=80';
                                @endphp
                                <img src="{{ $thumbUrl }}" alt="{{ $meeting->title }}" class="meeting-card-img">
                                
                                <div class="media-overlay-badges">
                                    @if($meeting->isLive())
                                        <span class="live-tag">
                                            <span class="pulse-dot-red"></span> LIVE NOW
                                        </span>
                                    @else
                                        <span class="schedule-tag">
                                            <i class="fa-regular fa-clock"></i> Today {{ $meeting->start_time ? $meeting->start_time->format('h:i A') : '' }}
                                        </span>
                                    @endif
                                    <span class="price-pill-gold">{{ $meeting->formatted_price }}</span>
                                </div>
                            </div>

                            <!-- Card Content -->
                            <div class="meeting-card-body">
                                <div class="meeting-meta-top">
                                    <span class="meta-tag"><i class="fa-regular fa-clock gold-icon"></i> {{ $meeting->duration }}</span>
                                    <span class="meta-tag gold-text">
                                        <i class="fa-solid {{ $meeting->isLive() ? 'fa-hourglass-half' : 'fa-stopwatch' }}"></i> {{ $meeting->time_indicator }}
                                    </span>
                                </div>

                                <h3 class="meeting-title">{{ $meeting->title }}</h3>
                                <p class="meeting-desc">{{ \Illuminate\Support\Str::limit($meeting->description, 120) }}</p>

                                <div class="meeting-card-footer">
                                    <div class="pricing-display">
                                        <span class="pricing-label">{{ $meeting->isLive() ? 'Access Pass Fee' : 'Registration Price' }}</span>
                                        <strong class="pricing-amount">{{ $meeting->formatted_price }}</strong>
                                    </div>

                                    <button type="button" class="btn {{ $meeting->isLive() ? 'btn-gold btn-glowing' : 'btn-outline-gold' }} book-trigger-btn" 
                                            onclick="openBookingModal({{ $meeting->id }})">
                                        <span class="btn-content">
                                            <i class="fa-solid {{ $meeting->isLive() ? 'fa-bolt' : 'fa-ticket' }}"></i> {{ $meeting->isLive() ? 'Book & Join Now' : 'Reserve Pass' }}
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-meetings-box">
                    <i class="fa-regular fa-calendar-xmark gold-icon empty-icon"></i>
                    <h3>No Sessions Scheduled For Today</h3>
                    <p>Check tomorrow's schedule below or explore upcoming summits.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- SECTION 3: TOMORROW'S SESSIONS -->
    <section class="meetings-section tomorrow-section-bg" id="tomorrow-section">
        <div class="section-container">
            <div class="section-header-box">
                <div class="section-badge-tomorrow">
                    <i class="fa-solid fa-calendar-plus"></i> TOMORROW'S VIP SCHEDULE &bull; {{ \Carbon\Carbon::tomorrow()->format('l, M d, Y') }}
                </div>
                <h2 class="section-title">Tomorrow's Sessions ({{ $tomorrowCount }})</h2>
                <p class="section-subtitle">
                    Advance registration for tomorrow's closed-door investor summits and executive masterclasses.
                </p>
            </div>

            @if($tomorrowMeetings->count() > 0)
                <div class="meetings-grid">
                    @foreach($tomorrowMeetings as $meeting)
                        <div class="meeting-card upcoming-card animate-slide-up">
                            <!-- Card Image -->
                            <div class="meeting-card-media">
                                @php
                                    $thumb = $meeting->thumbnail;
                                    $thumbUrl = $thumb ? (\Illuminate\Support\Str::startsWith($thumb, 'http') ? $thumb : asset($thumb)) : 'https://images.unsplash.com/photo-1546412414-e1885259563a?auto=format&fit=crop&w=600&q=80';
                                @endphp
                                <img src="{{ $thumbUrl }}" alt="{{ $meeting->title }}" class="meeting-card-img">
                                
                                <div class="media-overlay-badges">
                                    <span class="schedule-tag">
                                        <i class="fa-regular fa-clock"></i> Tomorrow {{ $meeting->start_time ? $meeting->start_time->format('h:i A') : '' }}
                                    </span>
                                    <span class="price-pill-gold">{{ $meeting->formatted_price }}</span>
                                </div>
                            </div>

                            <!-- Card Content -->
                            <div class="meeting-card-body">
                                <div class="meeting-meta-top">
                                    <span class="meta-tag"><i class="fa-regular fa-clock gold-icon"></i> {{ $meeting->duration }}</span>
                                    <span class="meta-tag gold-text"><i class="fa-solid fa-calendar-day"></i> {{ $meeting->start_time ? $meeting->start_time->format('M d, Y') : '' }}</span>
                                </div>

                                <h3 class="meeting-title">{{ $meeting->title }}</h3>
                                <p class="meeting-desc">{{ \Illuminate\Support\Str::limit($meeting->description, 120) }}</p>

                                <div class="meeting-card-footer">
                                    <div class="pricing-display">
                                        <span class="pricing-label">Registration Price</span>
                                        <strong class="pricing-amount">{{ $meeting->formatted_price }}</strong>
                                    </div>

                                    <button type="button" class="btn btn-outline-gold book-trigger-btn" 
                                            onclick="openBookingModal({{ $meeting->id }})">
                                        <span class="btn-content">
                                            <i class="fa-solid fa-ticket"></i> Reserve Pass
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-meetings-box">
                    <i class="fa-regular fa-calendar-xmark gold-icon empty-icon"></i>
                    <h3>No Sessions Scheduled For Tomorrow</h3>
                    <p>Browse our complete upcoming schedule below.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- SECTION 4: ALL UPCOMING MEETINGS -->
    <section class="meetings-section upcoming-bg" id="upcoming-section">
        <div class="section-container">
            <div class="section-header-box">
                <div class="section-badge-upcoming">
                    <i class="fa-regular fa-calendar-check"></i> SCHEDULED SESSIONS & MASTERCLASSES
                </div>
                <h2 class="section-title">Upcoming VIP Meetings</h2>
                <p class="section-subtitle">
                    Reserve your seat for upcoming private Dubai briefings, investor summits, and industry roundtables. Seats are strictly limited to ensure closed-door exclusivity.
                </p>
            </div>

            @if($upcomingMeetings->count() > 0)
                <div class="meetings-grid">
                    @foreach($upcomingMeetings as $meeting)
                        @php
                            $cardDay = ($meeting->start_time && $meeting->start_time->isToday()) ? 'today' : (($meeting->start_time && $meeting->start_time->isTomorrow()) ? 'tomorrow' : 'other');
                        @endphp
                        <div class="meeting-card upcoming-card animate-slide-up" data-meeting-day="{{ $cardDay }}">
                            <!-- Card Image -->
                            <div class="meeting-card-media">
                                @php
                                    $thumb = $meeting->thumbnail;
                                    $thumbUrl = $thumb ? (\Illuminate\Support\Str::startsWith($thumb, 'http') ? $thumb : asset($thumb)) : 'https://images.unsplash.com/photo-1546412414-e1885259563a?auto=format&fit=crop&w=600&q=80';
                                @endphp
                                <img src="{{ $thumbUrl }}" alt="{{ $meeting->title }}" class="meeting-card-img">
                                
                                <div class="media-overlay-badges">
                                    <span class="schedule-tag">
                                        <i class="fa-regular fa-calendar"></i> 
                                        {{ $meeting->start_time ? $meeting->start_time->format('M d, h:i A') : 'UPCOMING' }}
                                    </span>
                                    <span class="price-pill-gold">{{ $meeting->formatted_price }}</span>
                                </div>
                            </div>

                            <!-- Card Content -->
                            <div class="meeting-card-body">
                                <div class="meeting-meta-top">
                                    <span class="meta-tag"><i class="fa-regular fa-clock gold-icon"></i> {{ $meeting->duration }}</span>
                                    @if($meeting->start_time)
                                        <span class="meta-tag gold-text"><i class="fa-solid fa-calendar-day"></i> {{ $meeting->start_time->format('D, M d, Y') }}</span>
                                    @endif
                                </div>

                                <h3 class="meeting-title">{{ $meeting->title }}</h3>
                                <p class="meeting-desc">{{ \Illuminate\Support\Str::limit($meeting->description, 120) }}</p>

                                <div class="meeting-card-footer">
                                    <div class="pricing-display">
                                        <span class="pricing-label">Registration Price</span>
                                        <strong class="pricing-amount">{{ $meeting->formatted_price }}</strong>
                                    </div>

                                    <button type="button" class="btn btn-outline-gold book-trigger-btn" 
                                            onclick="openBookingModal({{ $meeting->id }})">
                                        <span class="btn-content">
                                            <i class="fa-solid fa-ticket"></i> Reserve Pass
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-meetings-box">
                    <i class="fa-regular fa-calendar-xmark gold-icon empty-icon"></i>
                    <h3>No Scheduled Meetings At The Moment</h3>
                    <p>New executive briefings are added regularly. Check back soon or contact concierge for customized private briefings.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="portal-footer">
        <div class="footer-inner">
            <div class="footer-top">
                <img src="{{ asset('logo.png') }}" alt="Dubai VIP" class="footer-logo">
                <p class="footer-text">Dubai VIP Gateway is the premier digital gateway for high-net-worth attendees, providing encrypted meeting links, confidential passwords, and dedicated VIP access codes.</p>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Dubai VIP Concierge & Meetings Gateway. All Rights Reserved.</p>
                <div class="footer-links">
                    <a href="{{ route('login') }}">Member Sign In</a> &bull; 
                    <a href="{{ route('register') }}">Free Registration</a> &bull; 
                    <a href="{{ route('admin.login') }}">Admin Login</a>
                </div>
            </div>
        </div>
    </footer>
</div>

<!-- ======================================================== -->
<!-- INTERACTIVE BOOKING & QR PAYMENT MODAL                    -->
<!-- ======================================================== -->
<div id="bookingModal" class="booking-modal">
    <div class="booking-modal-overlay" onclick="closeBookingModal()"></div>
    
    <div class="booking-modal-dialog animate-scale-in">
        <div class="modal-header-luxury">
            <div class="modal-header-text">
                <span class="modal-pre-badge"><i class="fa-solid fa-crown gold-icon"></i> VIP MEETING BOOKING</span>
                <h3 class="modal-title-lux" id="booking-modal-title">Meeting Title</h3>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeBookingModal()">&times;</button>
        </div>

        <form id="bookingSubmissionForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="meeting_id" id="modal-meeting-id" value="">

            <div class="booking-modal-body">
                <!-- Summary Card -->
                <div class="meeting-summary-banner">
                    <div class="summary-thumb-box">
                        <img src="" id="booking-modal-thumb" class="summary-thumb-img" alt="Thumbnail">
                    </div>
                    <div class="summary-info-box">
                        <div class="summary-price-row">
                            <span class="summary-fee-label">Total Payment Required:</span>
                            <strong class="summary-fee-val" id="booking-modal-price">150 AED</strong>
                        </div>
                        <div class="summary-meta-row">
                            <span><i class="fa-regular fa-clock gold-icon"></i> <span id="booking-modal-duration">60 Mins</span></span>
                            <span id="booking-modal-status-badge" class="badge-mini-live">Live Now</span>
                        </div>
                    </div>
                </div>

                <!-- STEP 1: SCAN QR CODE -->
                <div class="payment-step-section">
                    <div class="step-header">
                        <span class="step-num">1</span>
                        <h4>Scan Official QR Code & Transfer</h4>
                    </div>

                    <div class="qr-scan-card-wrapper">
                        <div class="qr-image-container" onclick="openQrZoomModal()" title="Click to enlarge QR code">
                            @if($qrUrl)
                                <img src="{{ $qrUrl }}" alt="Banking QR Code" class="booking-qr-img" id="booking-modal-qr-img">
                            @else
                                <div class="booking-qr-ph">
                                    <i class="fa-solid fa-qrcode"></i>
                                    <span>Bank Transfer QR Code</span>
                                </div>
                            @endif
                            <div class="qr-zoom-hint">
                                <i class="fa-solid fa-magnifying-glass-plus"></i> Tap to enlarge
                            </div>
                        </div>

                        <div class="qr-details-container">
                            <div class="qr-payment-badge">
                                <span class="qr-badge-label">Transfer Amount</span>
                                <strong class="qr-badge-amount" id="booking-modal-price-inst">300 AED</strong>
                            </div>

                            <p class="qr-instruction-text">
                                Scan the QR code using your mobile banking application (<strong>ENBD, Mashreq, FAB, ADCB</strong>, or International wire transfer / digital wallet).
                            </p>

                            <div class="qr-feature-tips">
                                <div class="qr-tip-item">
                                    <i class="fa-solid fa-circle-check gold-text"></i>
                                    <span>Direct UAE Central Bank cleared instant transfer</span>
                                </div>
                                <div class="qr-tip-item">
                                    <i class="fa-solid fa-camera gold-text"></i>
                                    <span>Capture transaction screenshot to upload in Step 2</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: ATTENDEE DETAILS (IF GUEST) -->
                @guest
                    <div class="payment-step-section">
                        <div class="step-header">
                            <span class="step-num">2</span>
                            <h4>Attendee Free Registration</h4>
                        </div>
                        <p class="text-xs text-muted" style="margin-bottom: 0.75rem;">
                            Your VIP attendee account is created automatically for free so you can securely retrieve your meeting password and access code upon admin verification.
                        </p>
                        <div class="form-grid-guest">
                            <div class="form-group-modal">
                                <label class="label-modal">Full Name *</label>
                                <input type="text" name="name" class="input-modal" placeholder="Sheikh Hamdan" required>
                                <span class="modal-field-err" id="err-name"></span>
                            </div>
                            <div class="form-group-modal">
                                <label class="label-modal">Email Address *</label>
                                <input type="email" name="email" class="input-modal" placeholder="hamdan@vipdubai.ae" required>
                                <span class="modal-field-err" id="err-email"></span>
                            </div>
                            <div class="form-group-modal">
                                <label class="label-modal">Phone / WhatsApp *</label>
                                <input type="tel" name="phone" class="input-modal" placeholder="+971 50 123 4567" required>
                                <span class="modal-field-err" id="err-phone"></span>
                            </div>
                            <div class="form-group-modal">
                                <label class="label-modal">Create Portal Password *</label>
                                <input type="password" name="password" class="input-modal" placeholder="••••••••" required>
                                <span class="modal-field-err" id="err-password"></span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="logged-in-attendee-strip">
                        <i class="fa-solid fa-user-check gold-icon"></i>
                        <span>Booking as VIP Member: <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->email }})</span>
                    </div>
                @endguest

                <!-- STEP 3: UPLOAD PROOF SCREENSHOT -->
                <div class="payment-step-section" style="border-bottom: none;">
                    <div class="step-header">
                        <span class="step-num">{{ Auth::check() ? '2' : '3' }}</span>
                        <h4>Upload Payment Screenshot Proof</h4>
                    </div>

                    <div class="upload-dropzone" id="booking-dropzone">
                        <input type="file" id="booking-screenshot-input" name="screenshot" accept="image/*" class="dropzone-file-input" required>
                        
                        <div class="dropzone-content" id="dropzone-empty-view">
                            <i class="fa-solid fa-cloud-arrow-up upload-icon-lux"></i>
                            <span class="upload-prompt">Click to select screenshot or drag image here</span>
                            <span class="upload-formats">Supported: JPG, PNG, WEBP (Max 5MB)</span>
                        </div>

                        <div class="dropzone-preview-box hidden" id="dropzone-preview-view">
                            <img src="" id="screenshot-preview-img" class="screenshot-preview-display" alt="Proof Preview">
                            <button type="button" class="remove-preview-icon" id="btn-remove-screenshot">&times;</button>
                        </div>
                    </div>
                    <span class="modal-field-err text-center" id="err-screenshot"></span>
                </div>
            </div>

            <div class="booking-modal-footer">
                <button type="button" class="btn btn-outline-gold" onclick="closeBookingModal()">Cancel</button>
                <button type="submit" class="btn btn-gold btn-glowing" id="btn-submit-booking">
                    <span>Confirm Transfer & Submit Proof <i class="fa-solid fa-arrow-right"></i></span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Fullscreen QR Lightbox Modal -->
<div id="qrZoomModal" class="qr-lightbox-overlay" onclick="closeQrZoomModal()">
    <div class="qr-lightbox-content animate-scale-in" onclick="event.stopPropagation()">
        <button type="button" class="qr-lightbox-close" onclick="closeQrZoomModal()">&times;</button>
        <div class="qr-lightbox-card">
            @if($qrUrl)
                <img src="{{ $qrUrl }}" alt="Banking QR Code Full Size" class="qr-lightbox-img">
            @endif
            <div class="qr-lightbox-footer">
                <strong>Payable Amount: <span id="qr-lightbox-price" class="gold-text">300 AED</span></strong>
                <p>Scan directly with your mobile banking or wallet application</p>
            </div>
        </div>
    </div>
</div>

<!-- Floating Customer Support Widget -->
<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $supportPhone) }}" target="_blank" class="floating-support" title="WhatsApp Customer Support">
    <div class="support-pulse"></div>
    <i class="fa-brands fa-whatsapp"></i>
</a>

<!-- Styles for Modern Luxury Layout -->
<style>
.portal-main-wrapper {
    background: #040508;
    color: var(--text-primary);
    min-height: 100vh;
}

/* Navbar */
.main-navbar {
    position: sticky;
    top: 0;
    z-index: 100;
    background: rgba(8, 11, 17, 0.92);
    border-bottom: 1px solid rgba(212, 175, 55, 0.15);
    backdrop-filter: blur(12px);
}
.nav-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0.85rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}
.brand-logo {
    height: 65px;
    width: auto;
    filter: drop-shadow(0 2px 8px rgba(0,0,0,0.5));
    transition: var(--transition-smooth);
}
.brand-logo:hover {
    transform: scale(1.03);
}
.nav-links-center {
    display: flex;
    align-items: center;
    gap: 1.8rem;
}
.nav-link {
    color: var(--text-muted);
    font-size: 0.9rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.4rem;
    transition: var(--transition-smooth);
}
.nav-link:hover {
    color: var(--gold-primary);
}
.live-dot-mini {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #e63946;
    display: inline-block;
    box-shadow: 0 0 8px #e63946;
    animation: pulseRed 1.5s infinite;
}
.nav-auth-actions {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.nav-auth-link {
    background: transparent;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    font-size: 1rem;
    padding: 0.4rem;
    transition: var(--transition-smooth);
}
.nav-auth-link:hover { color: var(--danger-color); }
.admin-quick-badge {
    background: var(--gold-primary);
    color: #000;
    font-size: 0.75rem;
    font-weight: 800;
    padding: 0.3rem 0.65rem;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    text-transform: uppercase;
}

@media (max-width: 880px) {
    .nav-links-center { display: none; }
}

/* Hero Section */
.portal-hero {
    position: relative;
    min-height: 75vh;
    background-image: url('https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=1920&q=80');
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 4rem 1.5rem;
}
.hero-overlay-dark {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(4, 5, 8, 0.75) 0%, rgba(4, 5, 8, 0.9) 65%, #040508 100%);
    backdrop-filter: blur(4px);
}
.hero-inner-container {
    position: relative;
    z-index: 2;
    max-width: 950px;
    margin: 0 auto;
    text-align: center;
}
.luxury-gold-pill {
    background: rgba(212, 175, 55, 0.12);
    border: 1px solid rgba(212, 175, 55, 0.35);
    color: var(--gold-primary);
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.35rem 1rem;
    border-radius: 50px;
    letter-spacing: 1px;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    margin-bottom: 1.25rem;
}
.hero-main-title {
    font-size: 2.8rem;
    line-height: 1.2;
    font-family: var(--font-heading);
    color: #fff;
    margin-bottom: 1.25rem;
}
.hero-description {
    font-size: 1.1rem;
    color: var(--text-muted);
    line-height: 1.6;
    max-width: 780px;
    margin: 0 auto 2.2rem auto;
}
.hero-action-buttons {
    display: flex;
    justify-content: center;
    gap: 1.25rem;
    flex-wrap: wrap;
    margin-bottom: 3rem;
}

.feature-strip {
    display: flex;
    justify-content: center;
    gap: 2.5rem;
    flex-wrap: wrap;
    padding-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
}
.feature-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    text-align: left;
}
.feature-item i {
    font-size: 1.5rem;
}
.feature-item strong {
    display: block;
    color: #fff;
    font-size: 0.9rem;
}
.feature-item span {
    display: block;
    color: var(--text-muted);
    font-size: 0.75rem;
}

@media (max-width: 768px) {
    .hero-main-title { font-size: 2rem; }
    .hero-description { font-size: 0.95rem; }
    .feature-strip { gap: 1.25rem; }
}

/* Day Filter Bar */
.day-filter-wrapper {
    background: #040508;
    padding: 2.5rem 1.5rem 0.5rem 1.5rem;
    position: relative;
    z-index: 10;
}
.day-filter-container {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
    flex-wrap: wrap;
    padding: 0.45rem 0.85rem;
    background: rgba(14, 18, 28, 0.9);
    border: 1px solid rgba(212, 175, 55, 0.25);
    border-radius: 50px;
    backdrop-filter: blur(12px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.5), 0 0 20px rgba(212, 175, 55, 0.08);
}
.filter-label {
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 0 0.5rem;
}
.day-tab-btn {
    background: transparent;
    border: 1px solid transparent;
    color: var(--text-muted);
    font-size: 0.85rem;
    font-weight: 700;
    padding: 0.5rem 1.15rem;
    border-radius: 50px;
    cursor: pointer;
    transition: var(--transition-smooth);
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    font-family: inherit;
}
.day-tab-btn:hover {
    color: var(--gold-primary);
    border-color: rgba(212, 175, 55, 0.35);
    background: rgba(212, 175, 55, 0.08);
}
.day-tab-btn.active {
    background: linear-gradient(135deg, #aa8410 0%, #d4af37 50%, #f3e5ab 100%);
    color: #050608;
    box-shadow: 0 4px 15px rgba(212, 175, 55, 0.35);
    font-weight: 800;
}

/* Meetings Section Layout */
.meetings-section {
    padding: 5rem 1.5rem;
}
.upcoming-bg {
    background: #070a10;
    border-top: 1px solid rgba(255, 255, 255, 0.03);
}
.today-section-bg {
    background: #060912;
    border-top: 1px solid rgba(212, 175, 55, 0.2);
    border-bottom: 1px solid rgba(255, 255, 255, 0.04);
}
.tomorrow-section-bg {
    background: #070b16;
    border-bottom: 1px solid rgba(255, 255, 255, 0.04);
}
.section-badge-today {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(212, 175, 55, 0.12);
    border: 1px solid rgba(212, 175, 55, 0.4);
    color: var(--gold-primary);
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.35rem 0.95rem;
    border-radius: 50px;
    margin-bottom: 0.75rem;
    letter-spacing: 0.5px;
}
.section-badge-tomorrow {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(77, 171, 247, 0.12);
    border: 1px solid rgba(77, 171, 247, 0.35);
    color: #74c0fc;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.35rem 0.95rem;
    border-radius: 50px;
    margin-bottom: 0.75rem;
    letter-spacing: 0.5px;
}
.section-container {
    max-width: 1280px;
    margin: 0 auto;
}
.section-header-box {
    text-align: center;
    max-width: 750px;
    margin: 0 auto 3.5rem auto;
}
.section-badge-live {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(230, 57, 70, 0.15);
    border: 1px solid rgba(230, 57, 70, 0.4);
    color: #ff8b94;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.3rem 0.85rem;
    border-radius: 50px;
    margin-bottom: 0.75rem;
    letter-spacing: 0.5px;
}
.pulse-live-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #e63946;
    animation: pulseRed 1.5s infinite;
}
.section-badge-upcoming {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(212, 175, 55, 0.12);
    border: 1px solid rgba(212, 175, 55, 0.35);
    color: var(--gold-primary);
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.3rem 0.85rem;
    border-radius: 50px;
    margin-bottom: 0.75rem;
    letter-spacing: 0.5px;
}
.section-title {
    font-size: 2.2rem;
    color: #fff;
    font-family: var(--font-heading);
    margin-bottom: 0.6rem;
}
.section-subtitle {
    color: var(--text-muted);
    font-size: 0.95rem;
    line-height: 1.6;
}

/* Cards Grid */
.meetings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
    gap: 2rem;
}
@media (max-width: 450px) {
    .meetings-grid { grid-template-columns: 1fr; }
}

.meeting-card {
    background: #0c1017;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    transition: var(--transition-smooth);
}
.meeting-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6), 0 0 20px rgba(212, 175, 55, 0.15);
}
.meeting-card.live-border {
    border-color: rgba(230, 57, 70, 0.3);
}
.meeting-card.live-border:hover {
    border-color: rgba(230, 57, 70, 0.6);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6), 0 0 25px rgba(230, 57, 70, 0.2);
}
.meeting-card.upcoming-card {
    border-color: rgba(212, 175, 55, 0.2);
}
.meeting-card.upcoming-card:hover {
    border-color: rgba(212, 175, 55, 0.5);
}

.meeting-card-media {
    position: relative;
    height: 210px;
    overflow: hidden;
}
.meeting-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}
.meeting-card:hover .meeting-card-img {
    transform: scale(1.08);
}
.media-overlay-badges {
    position: absolute;
    inset: 0;
    padding: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    background: linear-gradient(to bottom, rgba(4,5,8,0.7) 0%, rgba(4,5,8,0) 50%, rgba(4,5,8,0.8) 100%);
}
.live-tag {
    background: rgba(230, 57, 70, 0.9);
    color: #fff;
    font-size: 0.75rem;
    font-weight: 800;
    padding: 0.3rem 0.75rem;
    border-radius: 4px;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}
.pulse-dot-red {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #fff;
    animation: pulseRed 1.5s infinite;
}
.schedule-tag {
    background: rgba(14, 18, 25, 0.85);
    border: 1px solid rgba(212, 175, 55, 0.3);
    color: var(--gold-primary);
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.3rem 0.75rem;
    border-radius: 4px;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    backdrop-filter: blur(4px);
}
.price-pill-gold {
    background: linear-gradient(135deg, #d4af37 0%, #aa8410 100%);
    color: #000;
    font-size: 0.85rem;
    font-weight: 800;
    padding: 0.3rem 0.85rem;
    border-radius: 50px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.4);
}

.meeting-card-body {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}
.meeting-meta-top {
    display: flex;
    gap: 0.8rem;
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-bottom: 0.6rem;
}
.meta-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}
.meeting-title {
    font-size: 1.25rem;
    color: #fff;
    line-height: 1.35;
    margin-bottom: 0.6rem;
    font-weight: 700;
}
.meeting-desc {
    font-size: 0.85rem;
    color: var(--text-muted);
    line-height: 1.5;
    margin-bottom: 1.5rem;
    flex-grow: 1;
}
.meeting-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1.25rem;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    gap: 0.8rem;
}
.pricing-display {
    display: flex;
    flex-direction: column;
}
.pricing-label {
    font-size: 0.7rem;
    color: var(--text-muted);
    text-transform: uppercase;
}
.pricing-amount {
    font-size: 1.2rem;
    color: var(--gold-primary);
    font-weight: 800;
}
.book-trigger-btn {
    padding: 0.65rem 1.15rem;
    font-size: 0.85rem;
}

.empty-meetings-box {
    background: #0c1017;
    border: 1px dashed rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 3.5rem 2rem;
    text-align: center;
    max-width: 600px;
    margin: 0 auto;
}
.empty-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.8;
}
.empty-meetings-box h3 {
    font-size: 1.3rem;
    color: #fff;
    margin-bottom: 0.4rem;
}
.empty-meetings-box p {
    font-size: 0.85rem;
    color: var(--text-muted);
}

/* Footer */
.portal-footer {
    background: #020305;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    padding: 3.5rem 1.5rem 2rem 1.5rem;
}
.footer-inner {
    max-width: 1280px;
    margin: 0 auto;
}
.footer-top {
    margin-bottom: 2rem;
    max-width: 600px;
}
.footer-logo {
    height: 55px;
    margin-bottom: 0.85rem;
}
.footer-text {
    font-size: 0.85rem;
    color: var(--text-muted);
    line-height: 1.6;
}
.footer-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.04);
    font-size: 0.8rem;
    color: var(--text-muted);
}
.footer-links a {
    color: var(--text-muted);
}
.footer-links a:hover {
    color: var(--gold-primary);
}

/* =================================================== */
/* MODAL LUXURY STYLES                                 */
/* =================================================== */
.booking-modal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 9999;
    overflow-y: auto;
    padding: 1.5rem 1rem;
    background: rgba(0, 0, 0, 0.85);
    backdrop-filter: blur(10px);
}
.booking-modal.active {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    animation: fadeIn 0.2s ease;
}
.booking-modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 1;
}
.booking-modal-dialog {
    position: relative;
    z-index: 2;
    background: #0c1018;
    border: 1px solid rgba(212, 175, 55, 0.4);
    border-radius: 16px;
    width: 100%;
    max-width: 660px;
    margin: auto;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.95), 0 0 35px rgba(212, 175, 55, 0.15);
    display: flex;
    flex-direction: column;
    max-height: calc(100vh - 3rem);
    overflow: hidden;
}

#bookingSubmissionForm {
    display: flex;
    flex-direction: column;
    flex: 1;
    min-height: 0;
    overflow: hidden;
}

.modal-header-luxury {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    background: #070a10;
    flex-shrink: 0;
}
.modal-pre-badge {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 1px;
    color: var(--gold-primary);
    display: block;
    margin-bottom: 0.15rem;
}
.modal-title-lux {
    font-size: 1.15rem;
    color: #fff;
    line-height: 1.3;
    font-weight: 700;
}
.modal-close-btn {
    background: transparent;
    border: none;
    color: var(--text-muted);
    font-size: 1.8rem;
    cursor: pointer;
    line-height: 1;
    padding: 0.2rem 0.5rem;
    border-radius: 6px;
    transition: var(--transition-smooth);
}
.modal-close-btn:hover {
    color: #fff;
    background: rgba(255, 255, 255, 0.08);
}

.booking-modal-body {
    padding: 1.25rem 1.5rem;
    overflow-y: auto;
    flex: 1;
    min-height: 0;
    display: flex;
    flex-direction: column;
    gap: 1.15rem;
}

/* Summary Banner */
.meeting-summary-banner {
    display: flex;
    gap: 0.85rem;
    align-items: center;
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(212, 175, 55, 0.2);
    border-radius: 8px;
    padding: 0.65rem 0.85rem;
    flex-shrink: 0;
}
.summary-thumb-box {
    width: 60px;
    height: 50px;
    border-radius: 6px;
    overflow: hidden;
    flex-shrink: 0;
}
.summary-thumb-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.summary-info-box {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    flex: 1;
    gap: 0.75rem;
    flex-wrap: wrap;
}
.summary-price-row {
    display: flex;
    align-items: baseline;
    gap: 0.45rem;
}
.summary-fee-label { font-size: 0.75rem; color: var(--text-muted); }
.summary-fee-val { font-size: 1.15rem; color: var(--gold-primary); font-weight: 800; }
.summary-meta-row {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    font-size: 0.75rem;
    color: var(--text-muted);
}
.badge-mini-live {
    background: rgba(230, 57, 70, 0.2);
    color: #ff8b94;
    padding: 0.15rem 0.5rem;
    border-radius: 3px;
    font-weight: 700;
}

/* Step Sections */
.payment-step-section {
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    padding-bottom: 1.15rem;
}
.step-header {
    display: flex;
    align-items: center;
    gap: 0.55rem;
    margin-bottom: 0.75rem;
}
.step-num {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: var(--gold-primary);
    color: #000;
    font-size: 0.75rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
}
.step-header h4 {
    font-size: 0.9rem;
    color: #fff;
    font-weight: 700;
}

.qr-scan-card-wrapper {
    display: flex;
    gap: 1.25rem;
    align-items: center;
    background: linear-gradient(135deg, rgba(14, 19, 29, 0.95) 0%, rgba(8, 11, 18, 0.98) 100%);
    border: 1px solid rgba(212, 175, 55, 0.35);
    border-radius: 12px;
    padding: 1rem 1.15rem;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.05);
}
.qr-image-container {
    width: 180px;
    min-width: 180px;
    height: 180px;
    background: #ffffff;
    border-radius: 10px;
    padding: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    border: 2px solid var(--gold-primary);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5), 0 0 15px rgba(212, 175, 55, 0.2);
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    overflow: hidden;
}
.qr-image-container:hover {
    transform: scale(1.02);
    box-shadow: 0 8px 25px rgba(212, 175, 55, 0.35);
}
.booking-qr-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    image-rendering: -webkit-optimize-contrast;
}
.qr-zoom-hint {
    position: absolute;
    bottom: 5px;
    right: 5px;
    background: rgba(0, 0, 0, 0.85);
    color: #f3e5ab;
    font-size: 0.65rem;
    font-weight: 700;
    padding: 0.15rem 0.45rem;
    border-radius: 4px;
    pointer-events: none;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    border: 1px solid rgba(212, 175, 55, 0.3);
}
.booking-qr-ph {
    background: #111;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--gold-primary);
    font-size: 0.75rem;
    text-align: center;
}
.booking-qr-ph i { font-size: 2.2rem; margin-bottom: 0.3rem; }

.qr-details-container {
    display: flex;
    flex-direction: column;
    gap: 0.65rem;
    flex: 1;
}
.qr-payment-badge {
    background: rgba(212, 175, 55, 0.1);
    border: 1px solid rgba(212, 175, 55, 0.35);
    border-radius: 8px;
    padding: 0.5rem 0.85rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.qr-badge-label {
    font-size: 0.75rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}
.qr-badge-amount {
    font-size: 1.25rem;
    color: var(--gold-primary);
    font-weight: 800;
}
.qr-instruction-text {
    font-size: 0.8rem;
    color: #cbd5e1;
    line-height: 1.4;
}
.qr-feature-tips {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}
.qr-tip-item {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    font-size: 0.75rem;
    color: var(--text-muted);
}

/* Fullscreen QR Lightbox */
.qr-lightbox-overlay {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 100000;
    background: rgba(0, 0, 0, 0.88);
    backdrop-filter: blur(8px);
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
}
.qr-lightbox-overlay.active {
    display: flex;
}
.qr-lightbox-content {
    position: relative;
    max-width: 440px;
    width: 100%;
}
.qr-lightbox-close {
    position: absolute;
    top: -38px;
    right: 0;
    background: transparent;
    border: none;
    color: #fff;
    font-size: 2rem;
    cursor: pointer;
    line-height: 1;
}
.qr-lightbox-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 1.5rem;
    text-align: center;
    border: 3px solid var(--gold-primary);
    box-shadow: 0 15px 45px rgba(0, 0, 0, 0.9), 0 0 30px rgba(212, 175, 55, 0.4);
}
.qr-lightbox-img {
    width: 100%;
    max-height: 55vh;
    object-fit: contain;
    border-radius: 8px;
}
.qr-lightbox-footer {
    margin-top: 1rem;
    color: #111;
}
.qr-lightbox-footer strong {
    font-size: 1.15rem;
    display: block;
    color: #0b0f17;
}
.qr-lightbox-footer p {
    font-size: 0.85rem;
    color: #555;
    margin-top: 0.25rem;
}

@media (max-width: 600px) {
    .qr-scan-card-wrapper {
        flex-direction: column;
        text-align: center;
    }
    .qr-image-container {
        width: 100%;
        max-width: 220px;
        height: 180px;
        margin: 0 auto;
    }
    .qr-payment-badge {
        flex-direction: column;
        gap: 0.25rem;
    }
    .qr-tip-item {
        justify-content: center;
    }
}

/* Guest form grid */
.form-grid-guest {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.85rem;
}
@media (max-width: 500px) {
    .form-grid-guest { grid-template-columns: 1fr; }
}

.form-group-modal {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}
.label-modal {
    font-size: 0.75rem;
    color: var(--text-muted);
    font-weight: 600;
}
.input-modal {
    background: #141923;
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #fff;
    padding: 0.55rem 0.85rem;
    border-radius: 6px;
    font-size: 0.85rem;
    font-family: var(--font-body);
    outline: none;
    transition: var(--transition-smooth);
}
.input-modal:focus {
    border-color: var(--gold-primary);
}
.modal-field-err {
    color: var(--danger-color);
    font-size: 0.75rem;
    display: none;
}

.logged-in-attendee-strip {
    background: rgba(46, 196, 182, 0.08);
    border: 1px solid rgba(46, 196, 182, 0.25);
    color: #5ce1e6;
    padding: 0.5rem 0.85rem;
    border-radius: 6px;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Upload Dropzone - Compact & Reasonable */
.upload-dropzone {
    position: relative;
    background: rgba(0, 0, 0, 0.3);
    border: 1px dashed rgba(212, 175, 55, 0.35);
    border-radius: 8px;
    padding: 0.85rem 0.85rem;
    text-align: center;
    cursor: pointer;
    transition: var(--transition-smooth);
}
.upload-dropzone:hover {
    border-color: var(--gold-primary);
    background: rgba(212, 175, 55, 0.04);
}
.dropzone-file-input {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 5;
}
.upload-icon-lux {
    font-size: 1.4rem;
    color: var(--gold-primary);
    margin-bottom: 0.2rem;
    display: block;
}
.upload-prompt {
    display: block;
    font-size: 0.8rem;
    color: #fff;
    font-weight: 600;
}
.upload-formats {
    display: block;
    font-size: 0.7rem;
    color: var(--text-muted);
    margin-top: 0.1rem;
}

.dropzone-preview-box {
    position: relative;
    max-width: 180px;
    margin: 0 auto;
}
.screenshot-preview-display {
    width: 100%;
    max-height: 140px;
    object-fit: contain;
    border-radius: 6px;
    border: 1px solid rgba(212, 175, 55, 0.3);
}
.remove-preview-icon {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: var(--danger-color);
    color: #fff;
    border: none;
    font-size: 1rem;
    cursor: pointer;
    z-index: 10;
}

.booking-modal-footer {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 0.75rem;
    padding: 0.85rem 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    background: #070a10;
    flex-shrink: 0;
}
</style>

@push('scripts')
<script>
// Booking Modal Logic
const bookingModal = document.getElementById('bookingModal');
const bookingForm = document.getElementById('bookingSubmissionForm');
const fileInput = document.getElementById('booking-screenshot-input');
const dropzoneEmpty = document.getElementById('dropzone-empty-view');
const dropzonePreview = document.getElementById('dropzone-preview-view');
const previewImg = document.getElementById('screenshot-preview-img');
const removePreviewBtn = document.getElementById('btn-remove-screenshot');

const allMeetingsMap = {!! json_encode($allMeetings->keyBy('id')) !!};

function openBookingModal(meetingOrId) {
    let meeting = meetingOrId;
    if (typeof meetingOrId === 'number' || typeof meetingOrId === 'string') {
        meeting = allMeetingsMap[meetingOrId];
    }
    if (!meeting) {
        console.error('Meeting not found:', meetingOrId);
        return;
    }

    document.getElementById('modal-meeting-id').value = meeting.id;
    document.getElementById('booking-modal-title').textContent = meeting.title;
    
    const formattedPrice = meeting.formatted_price || meeting.price;
    document.getElementById('booking-modal-price').textContent = formattedPrice;
    document.getElementById('booking-modal-price-inst').textContent = formattedPrice;
    document.getElementById('booking-modal-duration').textContent = meeting.duration;

    const statusBadge = document.getElementById('booking-modal-status-badge');
    if (meeting.status === 'live' || meeting.status === 'ongoing') {
        statusBadge.textContent = '🔴 LIVE NOW';
        statusBadge.className = 'badge-mini-live';
    } else {
        statusBadge.textContent = '📅 SCHEDULED';
        statusBadge.className = 'gold-badge';
    }

    const thumbUrl = (meeting.thumbnail && meeting.thumbnail.startsWith('http')) 
        ? meeting.thumbnail 
        : (meeting.thumbnail ? '/' + meeting.thumbnail : 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=400&q=80');
    document.getElementById('booking-modal-thumb').src = thumbUrl;

    // Reset errors and file upload
    resetBookingModalErrors();
    resetDropzone();

    bookingModal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeBookingModal() {
    bookingModal.classList.remove('active');
    document.body.style.overflow = '';
}

function openQrZoomModal() {
    const qrModal = document.getElementById('qrZoomModal');
    if (qrModal) {
        const currPrice = document.getElementById('booking-modal-price').textContent;
        const qrPriceEl = document.getElementById('qr-lightbox-price');
        if (qrPriceEl) qrPriceEl.textContent = currPrice;
        qrModal.classList.add('active');
    }
}

function closeQrZoomModal() {
    const qrModal = document.getElementById('qrZoomModal');
    if (qrModal) {
        qrModal.classList.remove('active');
    }
}

function resetBookingModalErrors() {
    document.querySelectorAll('.modal-field-err').forEach(el => {
        el.textContent = '';
        el.style.display = 'none';
    });
}

function resetDropzone() {
    fileInput.value = '';
    previewImg.src = '';
    dropzoneEmpty.classList.remove('hidden');
    dropzonePreview.classList.add('hidden');
}

// File preview
if (fileInput) {
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                dropzoneEmpty.classList.add('hidden');
                dropzonePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
}

if (removePreviewBtn) {
    removePreviewBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        resetDropzone();
    });
}

// Submit Booking Form via AJAX
if (bookingForm) {
    bookingForm.addEventListener('submit', function(e) {
        e.preventDefault();
        resetBookingModalErrors();

        const submitBtn = document.getElementById('btn-submit-booking');
        const origBtnText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span>Uploading Proof... <i class="fa-solid fa-spinner fa-spin"></i></span>';

        const formData = new FormData(bookingForm);

        fetch("{{ route('meeting.book') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = origBtnText;

            if (res.status === 200 && res.body.success) {
                alert(res.body.message);
                window.location.href = res.body.redirect;
            } else if (res.status === 422 && res.body.errors) {
                for (const [key, msgs] of Object.entries(res.body.errors)) {
                    const errEl = document.getElementById('err-' + key);
                    if (errEl) {
                        errEl.textContent = msgs[0];
                        errEl.style.display = 'block';
                    }
                }
            } else {
                alert('Submission failed. Please verify your details.');
            }
        })
        .catch(err => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = origBtnText;
            console.error('Error:', err);
            alert('Connection issue. Please verify your internet connection.');
        });
    });
}
</script>
@endpush
@endsection
