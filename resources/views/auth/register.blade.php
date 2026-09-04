@extends('layouts.app')

@section('title', 'Free VIP Registration - Dubai VIP Meetings')

@section('content')
<div class="auth-page-container">
    <div class="auth-overlay"></div>
    
    <div class="auth-box-wrapper animate-slide-up">
        <!-- Logo & Header -->
        <div class="auth-header">
            <a href="{{ route('home') }}">
                <img src="{{ asset('logo.png') }}" alt="Dubai VIP Logo" class="auth-logo">
            </a>
            <div class="free-badge">
                <i class="fa-solid fa-gift gold-icon"></i> 100% Free Registration
            </div>
            <h1 class="auth-title">Join Dubai VIP Network</h1>
            <p class="auth-subtitle">Create your free VIP attendee account to explore and book exclusive Dubai meetings & summits.</p>
        </div>

        @if(isset($errors) && $errors->any())
            <div class="auth-alert-error">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="auth-form">
            @csrf
            
            <div class="form-group">
                <label for="name" class="form-label">Full Name</label>
                <div class="input-wrapper">
                    <i class="fa-regular fa-user input-icon"></i>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Sheikh Hamdan" value="{{ old('name') }}" required autofocus autocomplete="name">
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-wrapper">
                    <i class="fa-regular fa-envelope input-icon"></i>
                    <input type="email" id="email" name="email" class="form-control" placeholder="hamdan@vipdubai.ae" value="{{ old('email') }}" required autocomplete="email">
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Phone / WhatsApp Number</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-phone input-icon"></i>
                    <input type="tel" id="phone" name="phone" class="form-control" placeholder="+971 50 123 4567" value="{{ old('phone') }}" required autocomplete="tel">
                </div>
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock input-icon"></i>
                        <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="new-password">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock-open input-icon"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="••••••••" required autocomplete="new-password">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-gold btn-block btn-glowing" style="margin-top: 1rem;">
                <span>Create Free VIP Account <i class="fa-solid fa-arrow-right"></i></span>
            </button>
        </form>

        <div class="auth-footer">
            <p>Already have a VIP account? <a href="{{ route('login') }}" class="gold-text font-bold">Sign In Here</a></p>
            <p class="mt-2 text-xs text-muted"><a href="{{ route('home') }}"><i class="fa-solid fa-arrow-left"></i> Return to Homepage</a></p>
        </div>
    </div>
</div>

<style>
.auth-page-container {
    position: relative;
    min-height: 100vh;
    background-image: url('https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=1920&q=80');
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
}
.auth-overlay {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background: linear-gradient(135deg, rgba(4, 5, 8, 0.88) 0%, rgba(7, 9, 14, 0.95) 100%);
    backdrop-filter: blur(8px);
}
.auth-box-wrapper {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 520px;
    background: rgba(18, 22, 33, 0.85);
    border: 1px solid rgba(212, 175, 55, 0.25);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.6), 0 0 25px rgba(212, 175, 55, 0.1);
    border-radius: 12px;
    padding: 2.5rem;
    backdrop-filter: blur(15px);
}
.auth-header {
    text-align: center;
    margin-bottom: 2rem;
}
.auth-logo {
    max-height: 70px;
    margin-bottom: 1rem;
    filter: drop-shadow(0 2px 8px rgba(0,0,0,0.5));
}
.free-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: rgba(212, 175, 55, 0.12);
    border: 1px solid rgba(212, 175, 55, 0.35);
    color: var(--gold-primary);
    font-size: 0.8rem;
    font-weight: 700;
    padding: 0.3rem 0.85rem;
    border-radius: 50px;
    margin-bottom: 0.8rem;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.auth-title {
    font-size: 1.6rem;
    color: #fff;
    margin-bottom: 0.4rem;
    font-family: var(--font-heading);
}
.auth-subtitle {
    color: var(--text-muted);
    font-size: 0.85rem;
    line-height: 1.5;
}
.auth-form .form-group {
    margin-bottom: 1.25rem;
}
.form-row-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}
.auth-alert-error {
    background: rgba(230, 57, 70, 0.15);
    border: 1px solid rgba(230, 57, 70, 0.4);
    color: #ff8b94;
    padding: 0.85rem 1rem;
    border-radius: 6px;
    margin-bottom: 1.5rem;
    font-size: 0.85rem;
    display: flex;
    gap: 0.75rem;
    align-items: flex-start;
}
.auth-footer {
    text-align: center;
    margin-top: 1.8rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    font-size: 0.85rem;
    color: var(--text-muted);
}
@media (max-width: 600px) {
    .auth-box-wrapper { padding: 1.8rem; }
    .form-row-2 { grid-template-columns: 1fr; }
}
</style>
@endsection
