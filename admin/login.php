<?php
require_once '../includes/functions.php';
require_once '../includes/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (!empty($username) && !empty($password)) {
        $result = auth()->login($username, $password);
        if ($result['success']) {
            header('Location: index.php');
            exit;
        } else {
            $error = $result['message'];
        }
    } else {
        $error = 'لطفاً نام کاربری و رمز عبور را وارد کنید!';
    }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود به پنل مدیریت</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            direction: rtl;
        }
        .login-container {
            background: #fff;
            border-radius: 12px;
            padding: 50px 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .logo {
            text-align: center;
            font-size: 32px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 30px;
        }
        .logo span { color: #2271b1; }
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            font-size: 14px;
        }
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #dcdcde;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #2271b1;
            box-shadow: 0 0 0 3px rgba(34,113,177,0.15);
        }
        .btn-login {
            width: 100%;
            padding: 14px;
            background: #2271b1;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-login:hover {
            background: #135e96;
            transform: translateY(-2px);
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        .info-text {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #a0a5aa;
        }
        .info-text strong { color: #2271b1; }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #2271b1;
            text-decoration: none;
            font-size: 14px;
        }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">🚀 <span>Site</span>Builder</div>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>نام کاربری یا ایمیل</label>
                <input type="text" name="username" required autofocus>
            </div>
            <div class="form-group">
                <label>رمز عبور</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-login">ورود به پنل مدیریت</button>
        </form>
        
        <div class="info-text">
            🔑 پیش‌فرض: <strong>admin</strong> / <strong>admin123</strong>
        </div>
        <a href="../index.php" class="back-link">← بازگشت به سایت</a>
    </div>
</body>
</html>