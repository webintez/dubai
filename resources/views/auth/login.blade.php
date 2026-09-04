@extends('layouts.app')

@section('title', 'VIP Sign In - Dubai VIP Portal')

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
                <i class="fa-solid fa-crown gold-icon"></i> VIP Member Portal
            </div>
            <h1 class="auth-title">Welcome Back</h1>
            <p class="auth-subtitle">Sign in to access your booked meetings, passwords, and access codes.</p>
        </div>

        @if(session('success'))
            <div class="auth-alert-success">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

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

        <form action="{{ route('login') }}" method="POST" class="auth-form">
            @csrf
            
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-wrapper">
                    <i class="fa-regular fa-envelope input-icon"></i>
                    <input type="email" id="email" name="email" class="form-control" placeholder="hamdan@vipdubai.ae" value="{{ old('email') }}" required autofocus autocomplete="email">
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
                </div>
            </div>

            <div class="auth-remember-row">
                <label class="remember-checkbox-label">
                    <input type="checkbox" name="remember" value="1">
                    <span>Remember me on this device</span>
                </label>
            </div>

            <button type="submit" class="btn btn-gold btn-block btn-glowing" style="margin-top: 1rem;">
                <span>Sign In to VIP Portal <i class="fa-solid fa-arrow-right-to-bracket"></i></span>
            </button>
        </form>

        <div class="auth-footer">
            <p>New to Dubai VIP? <a href="{{ route('register') }}" class="gold-text font-bold">Register for Free</a></p>
            <p class="mt-2 text-xs text-muted">
                <a href="{{ route('home') }}"><i class="fa-solid fa-arrow-left"></i> Return to Homepage</a>
                &nbsp;|&nbsp;
                <a href="{{ route('admin.login') }}"><i class="fa-solid fa-shield-halved"></i> Admin Login</a>
            </p>
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
    max-width: 480px;
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
.auth-remember-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    font-size: 0.85rem;
    color: var(--text-muted);
}
.remember-checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}
.auth-alert-success {
    background: rgba(46, 196, 182, 0.15);
    border: 1px solid rgba(46, 196, 182, 0.4);
    color: #5ce1e6;
    padding: 0.85rem 1rem;
    border-radius: 6px;
    margin-bottom: 1.5rem;
    font-size: 0.85rem;
    display: flex;
    gap: 0.75rem;
    align-items: center;
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
}
</style>
@endsection
