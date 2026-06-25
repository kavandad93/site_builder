<?php
/**
 * =============================================
 * توابع عمومی با ذخیره‌سازی JSON
 * =============================================
 */

// ===== کلاس مدیریت داده =====
class DataManager {
    private static $instance = null;
    private $data_path;
    private $cache = [];
    
    private function __construct() {
        $this->data_path = DATA_PATH;
        $this->init_data_files();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function init_data_files() {
        $files = ['users', 'posts', 'pages', 'categories', 'settings', 'comments'];
        foreach ($files as $file) {
            $path = $this->data_path . '/' . $file . '.json';
            if (!file_exists($path)) {
                file_put_contents($path, json_encode([], JSON_PRETTY_PRINT));
            }
        }
        
        // داده‌های اولیه
        $this->init_default_data();
    }
    
    private function init_default_data() {
        // کاربر ادمین
        $users = $this->get('users');
        if (empty($users)) {
            $admin = [
                'id' => 1,
                'username' => 'admin',
                'email' => 'admin@site.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'display_name' => 'مدیر سایت',
                'first_name' => '',
                'last_name' => '',
                'bio' => '',
                'role' => 'administrator',
                'avatar' => '',
                'user_status' => 'active',
                'registered_at' => date('Y-m-d H:i:s'),
                'last_login' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $this->save('users', [$admin]);
        }
        
        // تنظیمات
        $settings = $this->get('settings');
        if (empty($settings)) {
            $settings_data = [
                'site_title' => 'سایت‌ساز حرفه‌ای',
                'site_description' => 'سیستم مدیریت محتوای کامل و حرفه‌ای',
                'posts_per_page' => 6,
                'timezone' => 'Asia/Tehran',
                'admin_email' => 'admin@site.com'
            ];
            $this->save('settings', $settings_data);
        }
        
        // دسته‌بندی‌ها
        $categories = $this->get('categories');
        if (empty($categories)) {
            $categories_data = [
                ['id' => 1, 'name' => 'آموزش', 'slug' => 'آموزش', 'description' => 'مطالب آموزشی و راهنما', 'parent_id' => null, 'created_at' => date('Y-m-d H:i:s')],
                ['id' => 2, 'name' => 'اخبار', 'slug' => 'اخبار', 'description' => 'آخرین اخبار و رویدادها', 'parent_id' => null, 'created_at' => date('Y-m-d H:i:s')],
                ['id' => 3, 'name' => 'مقالات', 'slug' => 'مقالات', 'description' => 'مقالات تخصصی', 'parent_id' => null, 'created_at' => date('Y-m-d H:i:s')]
            ];
            $this->save('categories', $categories_data);
        }
        
        // مطالب نمونه
        $posts = $this->get('posts');
        if (empty($posts)) {
            $posts_data = [
                [
                    'id' => 1,
                    'title' => 'آموزش ساخت سیستم مدیریت محتوا',
                    'slug' => 'آموزش-ساخت-cms',
                    'content' => '<p>در این مطلب به صورت کامل آموزش ساخت یک سیستم مدیریت محتوای حرفه‌ای با PHP را یاد می‌گیرید.</p>
<h2>مقدمه</h2>
<p>سیستم مدیریت محتوا (CMS) یکی از پرکاربردترین ابزارهای دنیای وب است. در این آموزش قصد داریم یک CMS کامل شبیه وردپرس بسازیم.</p>
<h2>امکانات</h2>
<ul>
<li>مدیریت مطالب و صفحات</li>
<li>مدیریت کاربران با سطوح دسترسی</li>
<li>دسته‌بندی‌ها</li>
<li>سیستم کامنت‌گذاری</li>
<li>ساخت Sitemap خودکار</li>
<li>سازنده صفحه (Page Builder)</li>
</ul>',
                    'excerpt' => 'آموزش کامل ساخت سیستم مدیریت محتوای حرفه‌ای با PHP',
                    'featured_image' => '',
                    'status' => 'published',
                    'type' => 'post',
                    'category_id' => 1,
                    'author_id' => 1,
                    'tags' => 'PHP, CMS, آموزش',
                    'views' => 120,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'published_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 2,
                    'title' => 'بهترین روش‌های سئو در سال ۲۰۲۶',
                    'slug' => 'بهترین-روش‌های-سئو',
                    'content' => '<p>سئو یکی از مهمترین عوامل موفقیت هر سایت است. در این مطلب بهترین روش‌های سئو را معرفی می‌کنیم.</p>
<h2>۱. محتوای با کیفیت</h2>
<p>محتوای با کیفیت و ارزشمند برای کاربران اصلی‌ترین رکن سئو است.</p>
<h2>۲. لینک‌سازی</h2>
<p>لینک‌سازی داخلی و خارجی نقش مهمی در بهبود سئو دارد.</p>',
                    'excerpt' => 'بهترین روش‌های سئو برای رشد و موفقیت سایت شما',
                    'featured_image' => '',
                    'status' => 'published',
                    'type' => 'post',
                    'category_id' => 2,
                    'author_id' => 1,
                    'tags' => 'سئو, SEO, بهینه‌سازی',
                    'views' => 85,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'published_at' => date('Y-m-d H:i:s')
                ]
            ];
            $this->save('posts', $posts_data);
        }
        
        // صفحات نمونه
        $pages = $this->get('pages');
        if (empty($pages)) {
            $pages_data = [
                [
                    'id' => 1,
                    'title' => 'درباره ما',
                    'slug' => 'درباره-ما',
                    'content' => '<p>ما یک تیم حرفه‌ای در زمینه توسعه وب هستیم.</p>
<p>هدف ما ارائه راهکارهای نوین و کارآمد برای مدیریت محتوای وب‌سایت‌ها است.</p>',
                    'status' => 'published',
                    'author_id' => 1,
                    'menu_order' => 1,
                    'show_in_menu' => true,
                    'template' => '',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 2,
                    'title' => 'تماس با ما',
                    'slug' => 'تماس-با-ما',
                    'content' => '<p>برای ارتباط با ما از راه‌های زیر استفاده کنید:</p>
<ul>
<li>ایمیل: info@sitebuilder.com</li>
<li>تلفن: ۰۲۱-۱۲۳۴-۵۶۷۸</li>
<li>آدرس: تهران، ایران</li>
</ul>',
                    'status' => 'published',
                    'author_id' => 1,
                    'menu_order' => 2,
                    'show_in_menu' => true,
                    'template' => '',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            ];
            $this->save('pages', $pages_data);
        }
    }
    
    public function get($table) {
        if (isset($this->cache[$table])) {
            return $this->cache[$table];
        }
        
        $path = $this->data_path . '/' . $table . '.json';
        if (!file_exists($path)) {
            return [];
        }
        
        $content = file_get_contents($path);
        $data = json_decode($content, true);
        $this->cache[$table] = $data ?: [];
        return $this->cache[$table];
    }
    
    public function save($table, $data) {
        $path = $this->data_path . '/' . $table . '.json';
        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $this->cache[$table] = $data;
        return true;
    }
    
    public function insert($table, $data) {
        $items = $this->get($table);
        $data['id'] = $this->get_next_id($table);
        $items[] = $data;
        $this->save($table, $items);
        return $data['id'];
    }
    
    public function update($table, $id, $data) {
        $items = $this->get($table);
        foreach ($items as $key => $item) {
            if ($item['id'] == $id) {
                $items[$key] = array_merge($item, $data);
                $this->save($table, $items);
                return true;
            }
        }
        return false;
    }
    
    public function delete($table, $id) {
        $items = $this->get($table);
        foreach ($items as $key => $item) {
            if ($item['id'] == $id) {
                unset($items[$key]);
                $this->save($table, array_values($items));
                return true;
            }
        }
        return false;
    }
    
    public function get_by_id($table, $id) {
        $items = $this->get($table);
        foreach ($items as $item) {
            if ($item['id'] == $id) {
                return $item;
            }
        }
        return null;
    }
    
    public function get_by_field($table, $field, $value) {
        $items = $this->get($table);
        foreach ($items as $item) {
            if (isset($item[$field]) && $item[$field] == $value) {
                return $item;
            }
        }
        return null;
    }
    
    public function get_next_id($table) {
        $items = $this->get($table);
        if (empty($items)) return 1;
        $ids = array_column($items, 'id');
        return max($ids) + 1;
    }
    
    public function query($table, $filters = [], $order_by = null, $limit = null) {
        $items = $this->get($table);
        
        foreach ($filters as $key => $value) {
            $items = array_filter($items, function($item) use ($key, $value) {
                if (is_array($value)) {
                    return in_array($item[$key] ?? null, $value);
                }
                return ($item[$key] ?? null) == $value;
            });
        }
        
        if ($order_by) {
            usort($items, function($a, $b) use ($order_by) {
                $field = $order_by['field'] ?? 'created_at';
                $direction = $order_by['direction'] ?? 'DESC';
                $val_a = $a[$field] ?? '';
                $val_b = $b[$field] ?? '';
                if ($direction === 'DESC') {
                    return strcmp($val_b, $val_a);
                }
                return strcmp($val_a, $val_b);
            });
        }
        
        if ($limit) {
            $items = array_slice($items, 0, $limit);
        }
        
        return array_values($items);
    }
}

$db = DataManager::getInstance();

function get_posts($filters = []) {
    global $db;
    $posts = $db->get('posts');
    
    if (isset($filters['status'])) {
        $posts = array_filter($posts, function($p) use ($filters) {
            return $p['status'] == $filters['status'];
        });
    }
    
    if (isset($filters['category_id'])) {
        $posts = array_filter($posts, function($p) use ($filters) {
            return ($p['category_id'] ?? null) == $filters['category_id'];
        });
    }
    
    if (isset($filters['author_id'])) {
        $posts = array_filter($posts, function($p) use ($filters) {
            return ($p['author_id'] ?? null) == $filters['author_id'];
        });
    }
    
    if (isset($filters['search'])) {
        $search = strtolower($filters['search']);
        $posts = array_filter($posts, function($p) use ($search) {
            return strpos(strtolower($p['title']), $search) !== false || 
                   strpos(strtolower($p['content'] ?? ''), $search) !== false;
        });
    }
    
    usort($posts, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });
    
    if (isset($filters['limit'])) {
        $posts = array_slice($posts, 0, $filters['limit']);
    }
    
    foreach ($posts as &$post) {
        if (isset($post['author_id'])) {
            $author = $db->get_by_id('users', $post['author_id']);
            $post['author_name'] = $author['display_name'] ?? $author['username'] ?? 'ناشناس';
        }
        if (isset($post['category_id'])) {
            $cat = $db->get_by_id('categories', $post['category_id']);
            $post['category_name'] = $cat['name'] ?? null;
        }
    }
    
    return array_values($posts);
}

function get_post_by_slug($slug) {
    global $db;
    return $db->get_by_field('posts', 'slug', $slug);
}

function get_post_by_id($id) {
    global $db;
    return $db->get_by_id('posts', $id);
}

function create_post($data) {
    global $db;
    $data['created_at'] = date('Y-m-d H:i:s');
    $data['updated_at'] = date('Y-m-d H:i:s');
    if (isset($data['status']) && $data['status'] === 'published') {
        $data['published_at'] = date('Y-m-d H:i:s');
    }
    return $db->insert('posts', $data);
}

function update_post($id, $data) {
    global $db;
    $data['updated_at'] = date('Y-m-d H:i:s');
    if (isset($data['status']) && $data['status'] === 'published') {
        $post = get_post_by_id($id);
        if (!$post || !isset($post['published_at'])) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }
    }
    return $db->update('posts', $id, $data);
}

function delete_post($id) {
    global $db;
    return $db->delete('posts', $id);
}

function increment_post_views($id) {
    global $db;
    $post = get_post_by_id($id);
    if ($post) {
        $post['views'] = ($post['views'] ?? 0) + 1;
        $db->update('posts', $id, ['views' => $post['views']]);
    }
}

function get_pages($filters = []) {
    global $db;
    $pages = $db->get('pages');
    
    if (isset($filters['status'])) {
        $pages = array_filter($pages, function($p) use ($filters) {
            return $p['status'] == $filters['status'];
        });
    }
    
    usort($pages, function($a, $b) {
        return ($a['menu_order'] ?? 0) - ($b['menu_order'] ?? 0);
    });
    
    foreach ($pages as &$page) {
        if (isset($page['author_id'])) {
            $author = $db->get_by_id('users', $page['author_id']);
            $page['author_name'] = $author['display_name'] ?? $author['username'] ?? 'ناشناس';
        }
    }
    
    return array_values($pages);
}

function get_page_by_slug($slug) {
    global $db;
    return $db->get_by_field('pages', 'slug', $slug);
}

function get_page_by_id($id) {
    global $db;
    return $db->get_by_id('pages', $id);
}

function create_page($data) {
    global $db;
    $data['created_at'] = date('Y-m-d H:i:s');
    $data['updated_at'] = date('Y-m-d H:i:s');
    return $db->insert('pages', $data);
}

function update_page($id, $data) {
    global $db;
    $data['updated_at'] = date('Y-m-d H:i:s');
    return $db->update('pages', $id, $data);
}

function delete_page($id) {
    global $db;
    return $db->delete('pages', $id);
}

function get_categories() {
    global $db;
    $categories = $db->get('categories');
    $posts = $db->get('posts');
    
    foreach ($categories as &$cat) {
        $cat['post_count'] = count(array_filter($posts, function($p) use ($cat) {
            return ($p['category_id'] ?? null) == $cat['id'] && $p['status'] == 'published';
        }));
    }
    
    return $categories;
}

function get_category_by_slug($slug) {
    global $db;
    return $db->get_by_field('categories', 'slug', $slug);
}

function get_category_by_id($id) {
    global $db;
    return $db->get_by_id('categories', $id);
}

function create_category($data) {
    global $db;
    $data['created_at'] = date('Y-m-d H:i:s');
    return $db->insert('categories', $data);
}

function update_category($id, $data) {
    global $db;
    return $db->update('categories', $id, $data);
}

function delete_category($id) {
    global $db;
    return $db->delete('categories', $id);
}

function search_posts($query) {
    if (empty($query)) return [];
    return get_posts(['search' => $query, 'status' => 'published']);
}

function create_slug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

function truncate_text($text, $length = 150) {
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . '...';
}

function get_excerpt($content, $length = 150) {
    $text = strip_tags($content);
    return truncate_text($text, $length);
}

function format_date($date, $format = 'Y/m/d') {
    return date($format, strtotime($date));
}

function get_permalink($type, $slug) {
    global $BASE_URL;
    return $BASE_URL . '/' . $type . '/' . $slug;
}

function get_edit_link($type, $id) {
    global $BASE_URL;
    return $BASE_URL . '/admin/' . $type . '.php?action=edit&id=' . $id;
}

function get_meta_tags($type, $data) {
    $tags = [];
    $settings = DataManager::getInstance()->get('settings');
    $site_title = $settings['site_title'] ?? 'سایت‌ساز حرفه‌ای';
    $site_description = $settings['site_description'] ?? '';
    
    if ($type === 'post') {
        $tags['title'] = $data['title'] . ' - ' . $site_title;
        $tags['description'] = get_excerpt($data['content'] ?? '', 160);
    } elseif ($type === 'page') {
        $tags['title'] = $data['title'] . ' - ' . $site_title;
        $tags['description'] = get_excerpt($data['content'] ?? '', 160);
    } else {
        $tags['title'] = $site_title;
        $tags['description'] = $site_description;
    }
    
    return $tags;
}

function get_setting($key) {
    $settings = DataManager::getInstance()->get('settings');
    return $settings[$key] ?? null;
}

function set_setting($key, $value) {
    $settings = DataManager::getInstance()->get('settings');
    $settings[$key] = $value;
    return DataManager::getInstance()->save('settings', $settings);
}

function render_template($template, $data = [], $status = 200) {
    http_response_code($status);
    extract($data);
    
    include TEMPLATE_PATH . '/header.php';
    
    $file = TEMPLATE_PATH . '/' . $template . '.php';
    if (file_exists($file)) {
        include $file;
    } else {
        include TEMPLATE_PATH . '/404.php';
    }
    
    include TEMPLATE_PATH . '/footer.php';
}

function get_nav_menu() {
    return get_pages(['status' => 'published']);
}

function get_recent_posts($limit = 5) {
    return get_posts(['status' => 'published', 'limit' => $limit]);
}

function get_categories_with_count() {
    return get_categories();
}
?>