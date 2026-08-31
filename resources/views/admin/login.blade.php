@extends('layouts.app')

@section('title', 'Admin Gateway - Dubai VIP Tourism')

@section('content')
<div class="hero-section">
    <div class="hero-overlay"></div>
    
    <div class="hero-container" style="justify-content: center; align-items: center; min-height: 100vh;">
        <div class="glass-modal active" style="position: relative; max-width: 420px; width: 100%;">
            <!-- Logo header -->
            <div class="text-center" style="margin-bottom: 2rem;">
                <h2 style="font-size: 2.2rem; font-weight: 800; letter-spacing: 2px;">
                    <span class="gold-text">DUBAI</span> VIP
                </h2>
                <p class="text-muted" style="font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase; margin-top: 0.25rem;">Admin Control Portal</p>
            </div>

            <!-- Display success messages -->
            @if(session('success'))
                <div class="payment-instructions" style="background: rgba(46, 196, 182, 0.1); border-color: var(--success-color); color: var(--success-color); margin-bottom: 1.5rem; text-align: center;">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Display error messages -->
            @if(session('error'))
                <div class="payment-instructions" style="background: rgba(230, 57, 70, 0.1); border-color: var(--danger-color); color: var(--danger-color); margin-bottom: 1.5rem; text-align: center;">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @if($errors->has('login'))
                <div class="payment-instructions" style="background: rgba(230, 57, 70, 0.1); border-color: var(--danger-color); color: var(--danger-color); margin-bottom: 1.5rem; text-align: center;">
                    <p>{{ $errors->first('login') }}</p>
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-wrapper">
                        <i class="fa-regular fa-user input-icon"></i>
                        <input type="text" id="username" name="username" class="form-control @error('username') is-invalid @enderror" placeholder="Enter username" value="{{ old('username') }}" required autofocus>
                    </div>
                    @error('username')
                        <span class="invalid-feedback" style="display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock input-icon"></i>
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" required>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" style="display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-gold btn-block" style="margin-top: 1rem;">
                    <span>Sign In to Panel <i class="fa-solid fa-right-to-bracket"></i></span>
                </button>
            </form>
            
            <div class="text-center" style="margin-top: 1.5rem;">
                <a href="{{ route('home') }}" class="text-muted text-sm" style="hover: color: var(--gold-primary);">
                    <i class="fa-solid fa-arrow-left"></i> Back to Main Portal
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
