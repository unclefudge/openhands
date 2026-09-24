@extends('admin.layout')

@section('title', 'Admin login')

@section('body')
    <main class="login-shell">
        <section class="panel login-card">
            <p class="muted" style="margin:0 0 6px;text-transform:uppercase;letter-spacing:.12em;font-size:12px;font-weight:800;">Open Hands</p>
            <h1 style="font-size:32px;">Enquiry review</h1>
            <p class="muted">Sign in to review delivered, quarantined and blocked enquiries.</p>

            @if ($errors->any())
                <div class="flash error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login.store') }}">
                @csrf
                <label>Email address<input type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus></label>
                <label style="margin-top:15px;">Password<input type="password" name="password" autocomplete="current-password" required></label>
                <label style="display:flex;align-items:center;gap:8px;margin-top:15px;font-weight:500;"><input type="checkbox" name="remember" value="1" style="width:auto;margin:0;"> Keep me signed in</label>
                <button class="button" type="submit">Sign in</button>
            </form>
        </section>
    </main>
@endsection
