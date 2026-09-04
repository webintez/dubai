@extends('layouts.app')

@section('title', 'My VIP Meeting Passes - Dubai VIP')

@section('content')
<div class="user-portal-layout">
    <!-- Header -->
    <header class="user-header">
        <div class="user-header-left">
            <a href="{{ route('home') }}">
                <img src="{{ asset('logo.png') }}" alt="Dubai VIP Logo" class="user-logo">
            </a>
            <span class="vip-member-tag"><i class="fa-solid fa-crown gold-icon"></i> VIP Member</span>
        </div>

        <div class="user-header-right">
            <a href="{{ route('home') }}" class="btn btn-outline-gold btn-sm">
                <i class="fa-solid fa-compass"></i> Explore Meetings
            </a>

            <div class="user-dropdown-info">
                <span class="user-name"><i class="fa-regular fa-user"></i> {{ $user->name }}</span>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="user-logout-btn" title="Sign Out">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="user-main-container">
        @if(session('success'))
            <div class="portal-alert portal-alert-success animate-fade-in">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <div class="portal-welcome-card animate-slide-up">
            <div>
                <h1 class="portal-title">My VIP Meeting Passes</h1>
                <p class="portal-desc">Manage your booked sessions, track payment verification, access confidential meeting passwords, and launch your meeting rooms.</p>
            </div>
            <a href="{{ route('home') }}#meetings-section" class="btn btn-gold btn-sm">
                <i class="fa-solid fa-plus"></i> Book Another Meeting
            </a>
        </div>

        <!-- Bookings Grid -->
        <div class="bookings-grid">
            @forelse($bookings as $booking)
                <div class="booking-card animate-slide-up {{ $booking->status }}">
                    <!-- Card Header / Status Banner -->
                    <div class="booking-card-top">
                        <div class="meeting-type-badge type-{{ $booking->meeting->status ?? 'upcoming' }}">
                            @if(($booking->meeting->status ?? '') === 'ongoing')
                                <span class="pulse-dot"></span> LIVE / ONGOING
                            @else
                                <i class="fa-regular fa-calendar"></i> UPCOMING
                            @endif
                        </div>

                        <div class="booking-status-tag status-{{ $booking->status }}">
                            @if($booking->isApproved())
                                <i class="fa-solid fa-circle-check"></i> APPROVED & ACTIVE
                            @elseif($booking->isPending())
                                <i class="fa-solid fa-clock-rotate-left"></i> AWAITING APPROVAL
                            @else
                                <i class="fa-solid fa-circle-xmark"></i> REJECTED
                            @endif
                        </div>
                    </div>

                    <!-- Meeting Core Info -->
                    <div class="booking-meeting-info">
                        <div class="meeting-thumb-wrap">
                            @php
                                $thumb = $booking->meeting->thumbnail ?? null;
                                $thumbUrl = $thumb ? (\Illuminate\Support\Str::startsWith($thumb, 'http') ? $thumb : asset($thumb)) : 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=400&q=80';
                            @endphp
                            <img src="{{ $thumbUrl }}" alt="{{ $booking->meeting->title }}" class="meeting-thumb-img">
                        </div>

                        <div class="meeting-details-wrap">
                            <h2 class="meeting-card-title">{{ $booking->meeting->title }}</h2>
                            <div class="meeting-meta-row">
                                <span class="meta-item"><i class="fa-regular fa-clock gold-icon"></i> {{ $booking->meeting->duration }}</span>
                                <span class="meta-item"><i class="fa-solid fa-tag gold-icon"></i> {{ $booking->meeting->formatted_price }}</span>
                                @if($booking->meeting->start_time)
                                    <span class="meta-item"><i class="fa-regular fa-calendar-check gold-icon"></i> {{ $booking->meeting->start_time->format('M d, Y - h:i A') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Access Credentials Box -->
                    <div class="booking-credentials-box">
                        @if($booking->isApproved())
                            <!-- UNLOCKED STATE: Code, Password, and Join Link -->
                            <div class="credentials-unlocked animate-fade-in">
                                <!-- Assigned Code Badge -->
                                <div class="assigned-code-card">
                                    <div class="code-header">
                                        <i class="fa-solid fa-id-badge gold-icon"></i>
                                        <span>YOUR ASSIGNED VIP ACCESS CODE</span>
                                    </div>
                                    <div class="code-display">
                                        <strong class="code-value">{{ $booking->assigned_code ?: 'DXB-VIP-' . str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</strong>
                                        <button type="button" class="copy-code-btn" onclick="copyText('{{ $booking->assigned_code ?: 'DXB-VIP-' . str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}', this)" title="Copy Code">
                                            <i class="fa-regular fa-copy"></i> Copy
                                        </button>
                                    </div>
                                </div>

                                <!-- Meeting Password Box -->
                                <div class="password-card">
                                    <div class="pass-header">
                                        <i class="fa-solid fa-key gold-icon"></i>
                                        <span>CONFIDENTIAL MEETING PASSWORD</span>
                                    </div>
                                    <div class="pass-display">
                                        <input type="password" value="{{ $booking->meeting->password }}" id="pass-field-{{ $booking->id }}" readonly class="pass-input">
                                        <button type="button" class="pass-toggle-btn" onclick="togglePasswordVisibility('pass-field-{{ $booking->id }}', this)">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                        <button type="button" class="copy-pass-btn" onclick="copyText('{{ $booking->meeting->password }}', this)">
                                            <i class="fa-regular fa-copy"></i> Copy
                                        </button>
                                    </div>
                                </div>

                                <!-- Direct Join Button -->
                                <div class="join-action-wrapper">
                                    <a href="{{ $booking->meeting->link }}" target="_blank" class="btn btn-gold btn-block btn-glowing join-meeting-btn">
                                        <span class="btn-content">
                                            <i class="fa-solid fa-video"></i> Join Meeting Room Now
                                        </span>
                                    </a>
                                    <span class="join-hint"><i class="fa-solid fa-shield-halved gold-text"></i> Secure VIP redirection to meeting link</span>
                                </div>
                            </div>
                        @elseif($booking->isPending())
                            <!-- PENDING STATE: Locked credentials & message -->
                            <div class="credentials-locked">
                                <div class="locked-icon-banner">
                                    <i class="fa-solid fa-lock locked-padlock"></i>
                                    <h3>Meeting Password & Link Locked</h3>
                                    <p>Your payment screenshot has been received and is currently being reviewed by the Dubai VIP admin team. Once approved, your assigned access code, password, and the direct join link will be unlocked immediately.</p>
                                </div>

                                <div class="locked-details-summary">
                                    <div class="locked-row">
                                        <span class="text-muted"><i class="fa-solid fa-ticket"></i> Assigned Code:</span>
                                        <span class="badge-locked"><i class="fa-solid fa-lock"></i> Pending Approval</span>
                                    </div>
                                    <div class="locked-row">
                                        <span class="text-muted"><i class="fa-solid fa-key"></i> Meeting Password:</span>
                                        <span class="badge-locked"><i class="fa-solid fa-lock"></i> Hidden</span>
                                    </div>
                                    <div class="locked-row">
                                        <span class="text-muted"><i class="fa-solid fa-link"></i> Join Link:</span>
                                        <span class="badge-locked"><i class="fa-solid fa-lock"></i> Unlocks on Approval</span>
                                    </div>
                                </div>

                                @if($booking->screenshot_path)
                                    <div class="submitted-proof-row">
                                        <span class="text-sm text-muted">Uploaded Payment Screenshot:</span>
                                        <a href="{{ asset($booking->screenshot_path) }}" target="_blank" class="view-proof-link">
                                            <i class="fa-regular fa-image"></i> View My Proof
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- REJECTED STATE -->
                            <div class="credentials-rejected">
                                <i class="fa-solid fa-circle-xmark rejected-icon"></i>
                                <h3>Payment Verification Declined</h3>
                                <p>The uploaded payment screenshot could not be validated. Please check with your bank or contact our concierge.</p>
                                @if($booking->admin_notes)
                                    <p class="admin-note-text"><strong>Admin note:</strong> {{ $booking->admin_notes }}</p>
                                @endif
                                <a href="{{ route('home') }}#meetings-section" class="btn btn-outline-gold btn-sm" style="margin-top: 1rem;">
                                    Re-book Session
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Card Footer -->
                    <div class="booking-card-footer">
                        <span class="booked-at"><i class="fa-regular fa-calendar"></i> Booked on {{ $booking->created_at->format('M d, Y h:i A') }}</span>
                        <a href="tel:{{ $supportPhone }}" class="support-contact-link">
                            <i class="fa-solid fa-headset gold-icon"></i> VIP Concierge: {{ $supportPhone }}
                        </a>
                    </div>
                </div>
            @empty
                <div class="no-bookings-card animate-slide-up">
                    <div class="no-bookings-icon">
                        <i class="fa-solid fa-calendar-xmark"></i>
                    </div>
                    <h2>No Meeting Bookings Found</h2>
                    <p>You have not booked any Dubai VIP sessions yet. Browse our live and scheduled meetings to reserve your VIP pass.</p>
                    <a href="{{ route('home') }}#meetings-section" class="btn btn-gold btn-glowing" style="margin-top: 1.5rem;">
                        <i class="fa-solid fa-compass"></i> Browse Available Meetings
                    </a>
                </div>
            @endforelse
        </div>
    </main>
</div>

<style>
.user-portal-layout {
    min-height: 100vh;
    background: #040508;
    color: var(--text-primary);
    display: flex;
    flex-direction: column;
}

.user-header {
    background: #080b11;
    border-bottom: 1px solid rgba(212, 175, 55, 0.15);
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.user-header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.user-logo {
    height: 55px;
    width: auto;
    filter: drop-shadow(0 2px 5px rgba(0,0,0,0.5));
}

.vip-member-tag {
    background: rgba(212, 175, 55, 0.1);
    border: 1px solid rgba(212, 175, 55, 0.3);
    color: var(--gold-primary);
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.25rem 0.65rem;
    border-radius: 50px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.user-header-right {
    display: flex;
    align-items: center;
    gap: 1.25rem;
}

.user-dropdown-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding-left: 1rem;
    border-left: 1px solid rgba(255, 255, 255, 0.1);
}

.user-name {
    font-size: 0.9rem;
    font-weight: 600;
    color: #e2e8f0;
}

.user-logout-btn {
    background: transparent;
    border: none;
    color: var(--text-muted);
    font-size: 0.85rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.35rem;
    transition: var(--transition-smooth);
}
.user-logout-btn:hover {
    color: var(--danger-color);
}

.user-main-container {
    max-width: 1200px;
    width: 100%;
    margin: 0 auto;
    padding: 2.5rem 1.5rem;
    flex-grow: 1;
}

.portal-alert {
    padding: 1rem 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 600;
}
.portal-alert-success {
    background: rgba(46, 196, 182, 0.15);
    border: 1px solid rgba(46, 196, 182, 0.35);
    color: #5ce1e6;
}

.portal-welcome-card {
    background: linear-gradient(135deg, rgba(18, 22, 33, 0.8) 0%, rgba(10, 13, 19, 0.9) 100%);
    border: 1px solid rgba(212, 175, 55, 0.2);
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.5rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
}

.portal-title {
    font-size: 1.8rem;
    font-family: var(--font-heading);
    margin-bottom: 0.4rem;
    background: linear-gradient(135deg, #fff 0%, #f3e5ab 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.portal-desc {
    color: var(--text-muted);
    font-size: 0.9rem;
    max-width: 650px;
}

/* Bookings Grid */
.bookings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(540px, 1fr));
    gap: 2rem;
}

@media (max-width: 768px) {
    .bookings-grid {
        grid-template-columns: 1fr;
    }
}

.booking-card {
    background: #0d1118;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 10px 25px rgba(0,0,0,0.4);
    transition: var(--transition-smooth);
}
.booking-card.approved {
    border-color: rgba(46, 196, 182, 0.4);
    box-shadow: 0 10px 30px rgba(46, 196, 182, 0.08);
}
.booking-card.pending {
    border-color: rgba(255, 183, 3, 0.3);
}

.booking-card-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    background: rgba(255, 255, 255, 0.02);
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.meeting-type-badge {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    padding: 0.25rem 0.65rem;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}
.meeting-type-badge.type-ongoing {
    background: rgba(230, 57, 70, 0.2);
    color: #ff8b94;
    border: 1px solid rgba(230, 57, 70, 0.4);
}
.pulse-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #e63946;
    display: inline-block;
    box-shadow: 0 0 8px #e63946;
    animation: pulseRed 1.5s infinite;
}
@keyframes pulseRed {
    0% { transform: scale(0.9); opacity: 0.7; }
    50% { transform: scale(1.2); opacity: 1; }
    100% { transform: scale(0.9); opacity: 0.7; }
}

.meeting-type-badge.type-upcoming {
    background: rgba(212, 175, 55, 0.15);
    color: var(--gold-primary);
    border: 1px solid rgba(212, 175, 55, 0.3);
}

.booking-status-tag {
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}
.status-approved {
    background: rgba(46, 196, 182, 0.15);
    color: var(--success-color);
    border: 1px solid rgba(46, 196, 182, 0.3);
}
.status-pending {
    background: rgba(255, 183, 3, 0.15);
    color: var(--pending-color);
    border: 1px solid rgba(255, 183, 3, 0.3);
}
.status-rejected {
    background: rgba(230, 57, 70, 0.15);
    color: var(--danger-color);
    border: 1px solid rgba(230, 57, 70, 0.3);
}

.booking-meeting-info {
    display: flex;
    gap: 1.25rem;
    padding: 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.meeting-thumb-wrap {
    width: 110px;
    height: 100px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
    border: 1px solid rgba(212, 175, 55, 0.2);
}
.meeting-thumb-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.meeting-details-wrap {
    flex-grow: 1;
}

.meeting-card-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #fff;
    line-height: 1.4;
    margin-bottom: 0.6rem;
}

.meeting-meta-row {
    display: flex;
    flex-wrap: wrap;
    gap: 0.9rem;
    font-size: 0.8rem;
    color: var(--text-muted);
}
.meta-item {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}

/* Credentials Box */
.booking-credentials-box {
    padding: 1.5rem;
    flex-grow: 1;
    background: rgba(4, 5, 8, 0.4);
}

/* Unlocked Credentials */
.assigned-code-card {
    background: linear-gradient(135deg, rgba(212, 175, 55, 0.12) 0%, rgba(212, 175, 55, 0.04) 100%);
    border: 1px solid rgba(212, 175, 55, 0.35);
    border-radius: 8px;
    padding: 1rem 1.25rem;
    margin-bottom: 1rem;
}
.code-header {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--gold-primary);
    letter-spacing: 0.5px;
    margin-bottom: 0.4rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}
.code-display {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.code-value {
    font-size: 1.4rem;
    letter-spacing: 2px;
    color: #fff;
    font-family: monospace;
}
.copy-code-btn {
    background: rgba(212, 175, 55, 0.2);
    border: 1px solid rgba(212, 175, 55, 0.4);
    color: var(--gold-primary);
    padding: 0.35rem 0.85rem;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-smooth);
}
.copy-code-btn:hover {
    background: var(--gold-primary);
    color: #000;
}

.password-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.25rem;
}
.pass-header {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--text-muted);
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}
.pass-display {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.pass-input {
    background: rgba(0, 0, 0, 0.5);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #fff;
    font-size: 1.1rem;
    font-family: monospace;
    letter-spacing: 2px;
    padding: 0.4rem 0.8rem;
    border-radius: 4px;
    flex-grow: 1;
    outline: none;
}
.pass-toggle-btn, .copy-pass-btn {
    background: #171d26;
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #cbd5e1;
    padding: 0.45rem 0.85rem;
    border-radius: 4px;
    font-size: 0.85rem;
    cursor: pointer;
    transition: var(--transition-smooth);
}
.pass-toggle-btn:hover, .copy-pass-btn:hover {
    background: #252e3d;
    color: #fff;
}

.join-action-wrapper {
    margin-top: 1rem;
    text-align: center;
}
.join-meeting-btn {
    padding: 0.9rem 1.5rem;
    font-size: 1rem;
    font-weight: 700;
}
.join-hint {
    display: block;
    margin-top: 0.5rem;
    font-size: 0.75rem;
    color: var(--text-muted);
}

/* Locked Credentials */
.credentials-locked {
    text-align: center;
    padding: 0.5rem 0;
}
.locked-icon-banner {
    background: rgba(255, 183, 3, 0.05);
    border: 1px dashed rgba(255, 183, 3, 0.25);
    border-radius: 8px;
    padding: 1.25rem;
    margin-bottom: 1.25rem;
}
.locked-padlock {
    font-size: 1.8rem;
    color: var(--pending-color);
    margin-bottom: 0.5rem;
}
.locked-icon-banner h3 {
    font-size: 1rem;
    color: #fff;
    margin-bottom: 0.4rem;
}
.locked-icon-banner p {
    font-size: 0.8rem;
    color: var(--text-muted);
    line-height: 1.5;
}
.locked-details-summary {
    background: rgba(255, 255, 255, 0.02);
    border-radius: 6px;
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
}
.locked-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.35rem 0;
    font-size: 0.8rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.03);
}
.locked-row:last-child { border-bottom: none; }
.badge-locked {
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-muted);
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
}
.submitted-proof-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
}
.view-proof-link {
    color: var(--gold-primary);
    font-size: 0.8rem;
    font-weight: 600;
}
.view-proof-link:hover {
    text-decoration: underline;
}

/* Rejected Credentials */
.credentials-rejected {
    text-align: center;
    padding: 1.5rem;
    background: rgba(230, 57, 70, 0.05);
    border: 1px solid rgba(230, 57, 70, 0.2);
    border-radius: 8px;
}
.rejected-icon {
    font-size: 2.2rem;
    color: var(--danger-color);
    margin-bottom: 0.5rem;
}
.admin-note-text {
    font-size: 0.8rem;
    color: #ff8b94;
    margin-top: 0.5rem;
}

/* Card Footer */
.booking-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.85rem 1.5rem;
    background: rgba(0, 0, 0, 0.4);
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    font-size: 0.75rem;
    color: var(--text-muted);
    flex-wrap: wrap;
    gap: 0.5rem;
}
.support-contact-link {
    color: var(--text-muted);
    transition: var(--transition-smooth);
}
.support-contact-link:hover {
    color: var(--gold-primary);
}

/* Empty State */
.no-bookings-card {
    grid-column: 1 / -1;
    background: #0d1118;
    border: 1px dashed rgba(212, 175, 55, 0.25);
    border-radius: 12px;
    padding: 4rem 2rem;
    text-align: center;
}
.no-bookings-icon {
    font-size: 3.5rem;
    color: var(--gold-primary);
    margin-bottom: 1rem;
    opacity: 0.8;
}
.no-bookings-card h2 {
    font-size: 1.6rem;
    margin-bottom: 0.5rem;
    color: #fff;
}
.no-bookings-card p {
    color: var(--text-muted);
    max-width: 500px;
    margin: 0 auto;
    font-size: 0.95rem;
}
</style>

@push('scripts')
<script>
function copyText(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
        setTimeout(() => {
            btn.innerHTML = orig;
        }, 2000);
    });
}

function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type = 'text';
        btn.innerHTML = '<i class="fa-regular fa-eye-slash"></i>';
    } else {
        input.type = 'password';
        btn.innerHTML = '<i class="fa-regular fa-eye"></i>';
    }
}
</script>
@endpush
@endsection
