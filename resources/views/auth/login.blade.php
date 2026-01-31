@extends('layouts.app')

@section('title', 'Login - Helpdesk System')

@section('content')
<div style="min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #0078D4 0%, #005A9E 100%);">
    <div class="card" style="width: 100%; max-width: 400px; margin: 20px;">
        <div class="card-header" style="border: none; justify-content: center;">
            <h2 style="color: var(--primary);">
                <i class="fas fa-headset"></i> Helpdesk System
            </h2>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger" style="margin-bottom: 20px;">
                    <i class="fas fa-exclamation-circle"></i>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger" style="margin-bottom: 20px;">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div style="color: var(--danger); font-size: 12px; margin-top: 5px;">
                            <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye" id="password-icon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div style="color: var(--danger); font-size: 12px; margin-top: 5px;">
                            <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group" style="display: flex; justify-content: space-between; align-items: center;">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" name="remember" style="margin-right: 8px;">
                        <span>Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
        </div>
    </div>

    <!-- Copyright -->
    <div class="text-center mt-8">
        <p class="text-white text-sm" style="color: white; font-size: 0.875rem; text-align: center; margin-top: 2rem;">
            © {{ date('Y') }} - EXACO Network Solution. All rights reserved.
            <br>
            <span style="display: flex; align-items: center; justify-content: center; gap: 0.25rem; margin-top: 0.25rem;">
                Developed by 
                <a href="https://github.com/fahreziisnaen" target="_blank" style="display: inline-flex; align-items: center;">
                    <img src="{{ asset('images/frz_sign.png') }}"
                         alt="FRZ Sign"
                         style="height: 1rem;">
                </a>
            </span>
        </p>
    </div>
</div>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '-icon');
    
    if (input && icon) {
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
}
</script>
@endsection
