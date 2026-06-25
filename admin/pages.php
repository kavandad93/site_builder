<?php
require_once '../includes/functions.php';
require_once '../includes/auth.php';
require_login();

$message = '';

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if ($action === 'delete' && isset($_GET['id'])) {
        delete_page($_GET['id']);
        $message = '✅ صفحه با موفقیت حذف شد!';
    }
    
    if ($action === 'edit' && isset($_GET['id'])) {
        $edit_page = get_page_by_id($_GET['id']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $data = [
        'title' => $_POST['title'],
        'slug' => create_slug($_POST['title']),
        'content' => $_POST['content'],
        'status' => $_POST['status'],
        'menu_order' => $_POST['menu_order'] ?? 0,
        'author_id' => current_user()['id']
    ];
    
    if ($id) {
        update_page($id, $data);
        $message = '✅ صفحه با موفقیت بروزرسانی شد!';
        $edit_page = get_page_by_id($id);
    } else {
        create_page($data);
        $message = '✅ صفحه با موفقیت ایجاد شد!';
    }
}

$pages = get_pages();
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت صفحات - سایت‌ساز حرفه‌ای</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <?php include 'includes/header.php'; ?>
        <div class="admin-body">
            <?php include 'includes/sidebar.php'; ?>
            <main class="admin-main">
                <div class="admin-header-bar">
                    <h1>📄 مدیریت صفحات</h1>
                    <div class="admin-actions">
                        <a href="?action=new" class="btn btn-primary">➕ صفحه جدید</a>
                    </div>
                </div>
                
                <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
                <?php endif; ?>
                
                <?php if (isset($_GET['action']) && ($_GET['action'] === 'new' || $_GET['action'] === 'edit')): ?>
                <div class="card">
                    <div class="card-header">
                        <h2><?php echo isset($edit_page) ? '✏️ ویرایش صفحه' : '➕ صفحه جدید'; ?></h2>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="id" value="<?php echo $edit_page['id'] ?? ''; ?>">
                        <div class="form-group">
                            <label>عنوان</label>
                            <input type="text" name="title" class="form-control" value="<?php echo $edit_page['title'] ?? ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>محتوا</label>
                            <textarea name="content" class="form-control" rows="10"><?php echo $edit_page['content'] ?? ''; ?></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>ترتیب منو</label>
                                <input type="number" name="menu_order" class="form-control" value="<?php echo $edit_page['menu_order'] ?? 0; ?>">
                            </div>
                            <div class="form-group">
                                <label>وضعیت</label>
                                <select name="status" class="form-control">
                                    <option value="draft" <?php echo (isset($edit_page) && $edit_page['status'] == 'draft') ? 'selected' : ''; ?>>پیش‌نویس</option>
                                    <option value="published" <?php echo (isset($edit_page) && $edit_page['status'] == 'published') ? 'selected' : ''; ?>>منتشر شده</option>
                                </select>
                            </div>
                        </div>
                        <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:20px;">
                            <a href="pages.php" class="btn btn-outline">لغو</a>
                            <button type="submit" class="btn btn-success">💾 ذخیره</button>
                        </div>
                    </form>
                </div>
                <?php else: ?>
                <div class="card">
                    <div class="card-header">
                        <h2>لیست صفحات</h2>
                    </div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>عنوان</th>
                                    <th>نویسنده</th>
                                    <th>وضعیت</th>
                                    <th>ترتیب</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pages as $page): ?>
                                <tr>
                                    <td><strong><?php echo $page['title']; ?></strong></td>
                                    <td><?php echo $page['author_name'] ?? 'ناشناس'; ?></td>
                                    <td><span class="status <?php echo $page['status']; ?>"><?php echo $page['status']; ?></span></td>
                                    <td><?php echo $page['menu_order']; ?></td>
                                    <td>
                                        <a href="?action=edit&id=<?php echo $page['id']; ?>" class="btn btn-sm">✏️</a>
                                        <a href="?action=delete&id=<?php echo $page['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('آیا از حذف این صفحه اطمینان دارید؟')">🗑️</a>
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