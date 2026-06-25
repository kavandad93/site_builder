<?php
require_once '../includes/functions.php';
require_once '../includes/auth.php';
require_login();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = [
        'site_title' => $_POST['site_title'],
        'site_description' => $_POST['site_description'],
        'posts_per_page' => (int)$_POST['posts_per_page'],
        'admin_email' => $_POST['admin_email']
    ];
    
    DataManager::getInstance()->save('settings', $settings);
    $message = '✅ تنظیمات با موفقیت ذخیره شد!';
}

$settings = DataManager::getInstance()->get('settings');
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تنظیمات - سایت‌ساز حرفه‌ای</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <?php include 'includes/header.php'; ?>
        <div class="admin-body">
            <?php include 'includes/sidebar.php'; ?>
            <main class="admin-main">
                <div class="admin-header-bar">
                    <h1>⚙️ تنظیمات</h1>
                </div>
                
                <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-header">
                        <h2>تنظیمات کلی سایت</h2>
                    </div>
                    <form method="POST">
                        <div class="form-group">
                            <label>عنوان سایت</label>
                            <input type="text" name="site_title" class="form-control" value="<?php echo $settings['site_title'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>توضیحات سایت</label>
                            <textarea name="site_description" class="form-control" rows="3"><?php echo $settings['site_description'] ?? ''; ?></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>تعداد مطالب در صفحه</label>
                                <input type="number" name="posts_per_page" class="form-control" value="<?php echo $settings['posts_per_page'] ?? 6; ?>" min="1" max="50">
                            </div>
                            <div class="form-group">
                                <label>ایمیل مدیر</label>
                                <input type="email" name="admin_email" class="form-control" value="<?php echo $settings['admin_email'] ?? ''; ?>">
                            </div>
                        </div>
                        <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:20px;">
                            <button type="submit" class="btn btn-success">💾 ذخیره تنظیمات</button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
    <script src="../assets/js/admin.js"></script>
</body>
</html>