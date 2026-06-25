<?php
/**
 * =============================================
 * سیستم مدیریت محتوای حرفه‌ای با JSON
 * مسیریاب اصلی (Front Controller)
 * =============================================
 */

// ===== تنظیمات خطا =====
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ===== شروع جلسه =====
session_start();

// ===== ثابت‌های اصلی =====
define('BASE_PATH', __DIR__);
define('BASE_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
define('ADMIN_PATH', BASE_PATH . '/admin');
define('TEMPLATE_PATH', BASE_PATH . '/templates/default');
define('DATA_PATH', BASE_PATH . '/data');
define('UPLOAD_PATH', BASE_PATH . '/assets/uploads');
define('CACHE_PATH', BASE_PATH . '/cache');

// ===== بارگذاری فایل‌های اصلی =====
require_once BASE_PATH . '/includes/functions.php';
require_once BASE_PATH . '/includes/auth.php';
require_once BASE_PATH . '/includes/router.php';
require_once BASE_PATH . '/includes/template.php';
require_once BASE_PATH . '/includes/sitemap.php';

// ===== مسیریابی =====
$router = new Router();

// ===== تعریف مسیرها =====
// صفحه اصلی
$router->add('/', function() {
    $posts = get_posts(['status' => 'published', 'limit' => 6]);
    return render_template('index', ['posts' => $posts]);
});

// صفحه مطلب
$router->add('/post/{slug}', function($slug) {
    $post = get_post_by_slug($slug);
    if (!$post) {
        return render_template('404', [], 404);
    }
    increment_post_views($post['id']);
    return render_template('single', ['post' => $post]);
});

// صفحه
$router->add('/page/{slug}', function($slug) {
    $page = get_page_by_slug($slug);
    if (!$page) {
        return render_template('404', [], 404);
    }
    return render_template('page', ['page' => $page]);
});

// آرشیو دسته‌بندی
$router->add('/category/{slug}', function($slug) {
    $category = get_category_by_slug($slug);
    if (!$category) {
        return render_template('404', [], 404);
    }
    $posts = get_posts(['category_id' => $category['id'], 'status' => 'published']);
    return render_template('archive', ['category' => $category, 'posts' => $posts]);
});

// جستجو
$router->add('/search', function() {
    $query = isset($_GET['q']) ? $_GET['q'] : '';
    $posts = search_posts($query);
    return render_template('search', ['query' => $query, 'posts' => $posts]);
});

// Sitemap
$router->add('/sitemap.xml', function() {
    generate_sitemap();
    header('Content-Type: application/xml');
    readfile(BASE_PATH . '/sitemap.xml');
    exit;
});

// پنل مدیریت
$router->add('/admin', function() {
    header('Location: ' . BASE_URL . '/admin/index.php');
    exit;
});

// ===== اجرای مسیریاب =====
$route = isset($_GET['route']) ? '/' . $_GET['route'] : '/';
$router->dispatch($route);
?>