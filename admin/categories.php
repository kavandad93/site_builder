<?php
require_once '../includes/functions.php';
require_once '../includes/auth.php';
require_login();

$message = '';

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if ($action === 'delete' && isset($_GET['id'])) {
        delete_category($_GET['id']);
        $message = '✅ دسته‌بندی با موفقیت حذف شد!';
    }
    
    if ($action === 'edit' && isset($_GET['id'])) {
        $edit_category = get_category_by_id($_GET['id']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $data = [
        'name' => $_POST['name'],
        'slug' => create_slug($_POST['name']),
        'description' => $_POST['description'] ?? ''
    ];
    
    if ($id) {
        update_category($id, $data);
        $message = '✅ دسته‌بندی با موفقیت بروزرسانی شد!';
        $edit_category = get_category_by_id($id);
    } else {
        create_category($data);
        $message = '✅ دسته‌بندی با موفقیت ایجاد شد!';
    }
}

$categories = get_categories();
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت دسته‌بندی‌ها - سایت‌ساز حرفه‌ای</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <?php include 'includes/header.php'; ?>
        <div class="admin-body">
            <?php include 'includes/sidebar.php'; ?>
            <main class="admin-main">
                <div class="admin-header-bar">
                    <h1>🏷️ مدیریت دسته‌بندی‌ها</h1>
                    <div class="admin-actions">
                        <a href="?action=new" class="btn btn-primary">➕ دسته‌بندی جدید</a>
                    </div>
                </div>
                
                <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
                <?php endif; ?>
                
                <?php if (isset($_GET['action']) && ($_GET['action'] === 'new' || $_GET['action'] === 'edit')): ?>
                <div class="card">
                    <div class="card-header">
                        <h2><?php echo isset($edit_category) ? '✏️ ویرایش دسته‌بندی' : '➕ دسته‌بندی جدید'; ?></h2>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="id" value="<?php echo $edit_category['id'] ?? ''; ?>">
                        <div class="form-group">
                            <label>نام دسته‌بندی</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $edit_category['name'] ?? ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>توضیحات</label>
                            <textarea name="description" class="form-control" rows="3"><?php echo $edit_category['description'] ?? ''; ?></textarea>
                        </div>
                        <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:20px;">
                            <a href="categories.php" class="btn btn-outline">لغو</a>
                            <button type="submit" class="btn btn-success">💾 ذخیره</button>
                        </div>
                    </form>
                </div>
                <?php else: ?>
                <div class="card">
                    <div class="card-header">
                        <h2>لیست دسته‌بندی‌ها</h2>
                    </div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>نام</th>
                                    <th>توضیحات</th>
                                    <th>تعداد مطالب</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td><strong><?php echo $cat['name']; ?></strong></td>
                                    <td><?php echo $cat['description'] ?? '-'; ?></td>
                                    <td><?php echo $cat['post_count'] ?? 0; ?></td>
                                    <td>
                                        <a href="?action=edit&id=<?php echo $cat['id']; ?>" class="btn btn-sm">✏️</a>
                                        <a href="?action=delete&id=<?php echo $cat['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('آیا از حذف این دسته‌بندی اطمینان دارید؟')">🗑️</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            </main>
        </div>
    </div>
    <script src="../assets/js/admin.js"></script>
</body>
</html>