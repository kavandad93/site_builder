<?php
require_once '../includes/functions.php';
require_once '../includes/auth.php';
require_login();

$db = DataManager::getInstance();
$user = current_user();

$posts = $db->get('posts');
$pages = $db->get('pages');
$users = $db->get('users');
$categories = $db->get('categories');

$stats = [
    'total_posts' => count($posts),
    'published_posts' => count(array_filter($posts, function($p) { return $p['status'] == 'published'; })),
    'total_pages' => count($pages),
    'total_users' => count($users),
    'total_categories' => count($categories)
];

$recent_posts = get_posts(['status' => 'published', 'limit' => 5]);
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پیشخوان - سایت‌ساز حرفه‌ای</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <?php include 'includes/header.php'; ?>
        
        <div class="admin-body">
            <?php include 'includes/sidebar.php'; ?>
            
            <main class="admin-main">
                <div class="admin-header-bar">
                    <h1>📊 پیشخوان</h1>
                    <div class="admin-actions">
                        <a href="posts.php?action=new" class="btn btn-primary">➕ مطلب جدید</a>
                        <a href="pages.php?action=new" class="btn btn-outline">📄 صفحه جدید</a>
                    </div>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">📝</div>
                        <div class="stat-number"><?php echo $stats['published_posts']; ?></div>
                        <div class="stat-label">مطالب منتشر شده</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">📄</div>
                        <div class="stat-number"><?php echo $stats['total_pages']; ?></div>
                        <div class="stat-label">صفحات</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">👤</div>
                        <div class="stat-number"><?php echo $stats['total_users']; ?></div>
                        <div class="stat-label">کاربران</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">🏷️</div>
                        <div class="stat-number"><?php echo $stats['total_categories']; ?></div>
                        <div class="stat-label">دسته‌بندی‌ها</div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h2>📋 آخرین مطالب</h2>
                        <a href="posts.php" class="card-link">مشاهده همه →</a>
                    </div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>عنوان</th>
                                    <th>نویسنده</th>
                                    <th>دسته‌بندی</th>
                                    <th>تاریخ</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_posts as $post): ?>
                                <tr>
                                    <td><strong><?php echo $post['title']; ?></strong></td>
                                    <td><?php echo $post['author_name'] ?? 'ناشناس'; ?></td>
                                    <td><?php echo $post['category_name'] ?? 'دسته‌بندی نشده'; ?></td>
                                    <td><?php echo format_date($post['created_at']); ?></td>
                                    <td>
                                        <a href="posts.php?action=edit&id=<?php echo $post['id']; ?>" class="btn btn-sm">✏️</a>
                                        <a href="#" onclick="deleteItem('posts', <?php echo $post['id']; ?>)" class="btn btn-sm btn-danger">🗑️</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>