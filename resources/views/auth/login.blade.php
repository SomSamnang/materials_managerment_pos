<!DOCTYPE html>
<html lang="km">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ចូលប្រព័ន្ធ</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<style>
/* Body & background */
body {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #6a11cb, #2575fc);
}

/* Login Card */
.login-card {
    width: 100%;
    max-width: 400px;
    background: rgba(255,255,255,0.95);
    border-radius: 20px;
    padding: 3rem 2rem 2rem;
    box-shadow: 0 15px 40px rgba(0,0,0,0.35);
    position: relative;
}

/* Icon on top */
.login-icon {
    position: absolute;
    top: -50px;
    left: 50%;
    transform: translateX(-50%);
    width: 90px;
    height: 90px;
    background: linear-gradient(45deg, #6a11cb, #2575fc);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    color: white;
}

/* Input + icon */
.form-group {
    position: relative;
    margin-bottom: 1.5rem;
}
.form-group input {
    width: 100%;
    padding: 12px 40px 12px 40px;
    border-radius: 12px;
    border: 1px solid #ccc;
}
.form-group i.input-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #888;
}

/* Password toggle */
.password-toggle {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #888;
}

/* Button */
.btn-primary {
    width: 100%;
    padding: 12px;
    border-radius: 12px;
    font-weight: bold;
    background: linear-gradient(90deg, #6a11cb, #2575fc);
    border: none;
}
.btn-primary:hover {
    transform: scale(1.02);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

/* Error */
.alert {
    border-radius: 12px;
    margin-bottom: 1rem;
    text-align: center;
}
</style>
</head>
<body>

<div class="login-card">
    <div class="login-icon">
        <i class="fa-solid fa-user"></i>
    </div>

    {{-- Display validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Display session error (e.g., login failed) --}}
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
        <h2 class="text-center">ចូលប្រព័ន្ធ</h2>
          <div class="login-icon">
        <i class="fa-solid fa-user"></i>
    </div>

        @csrf
        <!-- Username -->
        <div class="form-group">
            <i class="fa-solid fa-user input-icon"></i>
            <input type="text" name="name" placeholder="ឈ្មោះអ្នកប្រើប្រាស់" value="{{ old('name') }}" required>
        </div>

        <!-- Password -->
        <div class="form-group">
            <i class="fa-solid fa-lock input-icon"></i>
            <input type="password" name="password" id="password" placeholder="លេខសំងាត់" required>
            <i class="fa-solid fa-eye password-toggle" id="togglePassword"></i>
        </div>

        <button type="submit" class="btn btn-primary">ចូល</button>
    </form>
</div>

<script>
// Password toggle
const togglePassword = document.querySelector('#togglePassword');
const password = document.querySelector('#password');

togglePassword.addEventListener('click', () => {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    togglePassword.classList.toggle('fa-eye-slash');
});
</script>

</body>
</html>
