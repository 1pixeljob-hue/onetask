<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Hub - Đăng nhập</title>
    <link rel="stylesheet" href="/css/auth.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-card">
        <div class="icon-wrapper">
            <!-- Icon: Compass/Divider SVG -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m11 11-7 7a2 2 0 0 0 0 2.8s.2.2.8.8a2 2 0 0 0 2.8 0l7-7"></path>
                <path d="m11 11 7-7a2 2 0 0 1 0-2.8s-.2-.2-.8-.8a2 2 0 0 1-2.8 0l-7 7"></path>
                <circle cx="11" cy="11" r="2"></circle>
            </svg>
        </div>

        <h1>Project Hub</h1>
        <p class="subtitle">Welcome back. Enter your details to continue.</p>

        <?php if (isset($error)): ?>
            <div class="error-msg">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="/authenticate" method="POST">
            <div class="form-group">
                <label for="identity">Tên đăng nhập / Email</label>
                <input type="text" id="identity" name="identity" placeholder="quydev hoặc email@example.com" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex-row">
                <label class="remember-me">
                    <input type="checkbox" name="remember">
                    Remember me
                </label>
                <a href="#" class="forgot-password">Forgot password?</a>
            </div>

            <button type="submit" class="btn-signin">
                Sign In
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"></path>
                    <path d="m12 5 7 7-7 7"></path>
                </svg>
            </button>
        </form>
    </div>

    <script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
        }
    }
    </script>
</body>
</html>
