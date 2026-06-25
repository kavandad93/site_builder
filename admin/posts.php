<?php
require_once '../includes/functions.php';
require_once '../includes/auth.php';
require_login();

$message = '';

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if ($action === 'delete' && isset($_GET['id'])) {
        delete_post($_GET['id']);
        $message = '✅ مطلب با موفقیت حذف شد!';
    }
    
    if ($action === 'edit' && isset($_GET['id'])) {
        $edit_post = get_post_by_id($_GET['id']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $data = [
        'title' => $_POST['title'],
        'slug' => create_slug($_POST['title']),
        'content' => $_POST['content'],
        'excerpt' => $_POST['excerpt'] ?? '',
        'status' => $_POST['status'],
        'category_id' => $_POST['category_id'] ?: null,
        'author_id' => current_user()['id']
    ];
    
    if ($id) {
        update_post($id, $data);
        $message = '✅ مطلب با موفقیت بروزرسانی شد!';
        $edit_post = get_post_by_id($id);
    } else {
        create_post($data);
        $message = '✅ مطلب با موفقیت ایجاد شد!';
    }
}

$posts = get_posts();
$categories = get_categories();
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت مطالب - سایت‌ساز حرفه‌ای</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <?php include 'includes/header.php'; ?>
        <div class="admin-body">
            <?php include 'includes/sidebar.php'; ?>
            <main class="admin-main">
                <div class="admin-header-bar">
                    <h1>📝 مدیریت مطالب</h1>
                    <div class="admin-actions">
                        <a href="?action=new" class="btn btn-primary">➕ مطلب جدید</a>
                    </div>
                </div>
                
                <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
                <?php endif; ?>
                
                <?php if (isset($_GET['action']) && ($_GET['action'] === 'new' || $_GET['action'] === 'edit')): ?>
                <div class="card">
                    <div class="card-header">
                        <h2><?php echo isset($edit_post) ? '✏️ ویرایش مطلب' : '➕ مطلب جدید'; ?></h2>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="id" value="<?php echo $edit_post['id'] ?? ''; ?>">
                        <div class="form-group">
                            <label>عنوان</label>
                            <input type="text" name="title" class="form-control" value="<?php echo $edit_post['title'] ?? ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>محتوا</label>
                            <textarea name="content" class="form-control" rows="10"><?php echo $edit_post['content'] ?? ''; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>خلاصه</label>
                            <textarea name="excerpt" class="form-control" rows="3"><?php echo $edit_post['excerpt'] ?? ''; ?></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>دسته‌بندی</label>
                                <select name="category_id" class="form-control">
                                    <option value="">بدون دسته‌بندی</option>
                                    <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>" <?php echo (isset($edit_post) && $edit_post['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                        <?php echo $cat['name']; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>وضعیت</label>
                                <select name="status" class="form-control">
                                    <option value="draft" <?php echo (isset($edit_post) && $edit_post['status'] == 'draft') ? 'selected' : ''; ?>>پیش‌نویس</option>
                                    <option value="published" <?php echo (isset($edit_post) && $edit_post['status'] == 'published') ? 'selected' : ''; ?>>منتشر شده</option>
                                    <option value="pending" <?php echo (isset($edit_post) && $edit_post['status'] == 'pending') ? 'selected' : ''; ?>>در انتظار</option>
                                </select>
                            </div>
                        </div>
                        <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:20px;">
                            <a href="posts.php" class="btn btn-outline">لغو</a>
                            <button type="submit" class="btn btn-success">💾 ذخیره</button>
                        </div>
                    </form>
                </div>
                <?php else: ?>
                <div class="card">
                    <div class="card-header">
                        <h2>لیست مطالب</h2>
                    </div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>عنوان</th>
                                    <th>نویسنده</th>
                                    <th>دسته‌بندی</th>
                                    <th>وضعیت</th>
                                    <th>تاریخ</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($posts as $post): ?>
                                <tr>
                                    <td><strong><?php echo $post['title']; ?></strong></td>
                                    <td><?php echo $post['author_name'] ?? 'ناشناس'; ?></td>
                                    <td><?php echo $post['category_name'] ?? '-'; ?></td>
                                    <td><span class="status <?php echo $post['status']; ?>"><?php echo $post['status']; ?></span></td>
                                    <td><?php echo format_date($post['created_at']); ?></td>
                                    <td>
                                        <a href="?action=edit&id=<?php echo $post['id']; ?>" class="btn btn-sm">✏️</a>
                                        <a href="?action=delete&id=<?php echo $post['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('آیا از حذف این مطلب اطمینان دارید؟')">🗑️</a>
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