<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css"/>
  <style>
    body { background: #f5f7fb; }
    .login-card { max-width: 420px; margin: 8vh auto; }
  </style>
</head>
<body>
<div class="card shadow login-card">
  <div class="card-body">
    <h4 class="mb-3 text-center">Masuk</h4>

    @if (session('resp_msg'))
      <div class="alert alert-success">{{ session('resp_msg') }}</div>
    @endif

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}" novalidate>
      @csrf
      <div class="form-group">
        <label for="email">Email</label>
        <input
          type="email"
          class="form-control @error('email') is-invalid @enderror"
          id="email" name="email" value="{{ old('email') }}" required autofocus
          placeholder="you@example.com">
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input
          type="password"
          class="form-control @error('password') is-invalid @enderror"
          id="password" name="password" required placeholder="••••••••">
      </div>

      <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
        <label class="form-check-label" for="remember">Ingat saya</label>
      </div>

      <button type="submit" class="btn btn-primary btn-block">Masuk</button>
    </form>

    <hr>
    <small class="text-muted d-block text-center">© {{ date('Y') }} — App</small>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
