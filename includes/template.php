<?php
/**
 * =============================================
 * سیستم قالب‌ها (Templates)
 * =============================================
 */

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

function get_header() {
    include TEMPLATE_PATH . '/header.php';
}

function get_footer() {
    include TEMPLATE_PATH . '/footer.php';
}

function get_sidebar() {
    include TEMPLATE_PATH . '/sidebar.php';
}

function the_title() {
    global $post, $page;
    if (isset($post)) {
        echo $post['title'];
    } elseif (isset($page)) {
        echo $page['title'];
    }
}

function the_content() {
    global $post, $page;
    if (isset($post)) {
        echo $post['content'];
    } elseif (isset($page)) {
        echo $page['content'];
    }
}

function the_excerpt($length = 150) {
    global $post;
    if (isset($post)) {
        if (!empty($post['excerpt'])) {
            echo $post['excerpt'];
        } else {
            echo get_excerpt($post['content'], $length);
        }
    }
}

function the_permalink() {
    global $post, $page;
    if (isset($post)) {
        echo get_permalink('post', $post['slug']);
    } elseif (isset($page)) {
        echo get_permalink('page', $page['slug']);
    }
}

function the_author() {
    global $post;
    if (isset($post)) {
        echo $post['author_name'] ?? 'ناشناس';
    }
}

function the_date($format = 'Y/m/d') {
    global $post;
    if (isset($post)) {
        echo format_date($post['created_at'], $format);
    }
}

function the_category() {
    global $post;
    if (isset($post) && isset($post['category_name'])) {
        echo $post['category_name'];
    }
}

function the_tags() {
    global $post;
    if (isset($post) && !empty($post['tags'])) {
        $tags = explode(',', $post['tags']);
        foreach ($tags as $tag) {
            echo '<span class="tag">' . trim($tag) . '</span> ';
        }
    }
}

function wp_head() {
    global $post, $page;
    $meta_tags = ['title' => '', 'description' => ''];
    
    if (isset($post)) {
        $meta_tags = get_meta_tags('post', $post);
    } elseif (isset($page)) {
        $meta_tags = get_meta_tags('page', $page);
    } else {
        $meta_tags['title'] = get_setting('site_title');
        $meta_tags['description'] = get_setting('site_description');
    }
    
    echo '<title>' . $meta_tags['title'] . '</title>';
    echo '<meta name="description" content="' . $meta_tags['description'] . '">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<link rel="stylesheet" href="' . BASE_URL . '/assets/css/front.css">';
}

function wp_footer() {
    echo '<script src="' . BASE_URL . '/assets/js/front.js"></script>';
}

function get_bloginfo($key) {
    return get_setting($key);
}

function is_home() {
    global $route;
    return $route === '/';
}

function is_single() {
    global $route;
    return strpos($route, '/post/') === 0;
}

function is_page() {
    global $route;
    return strpos($route, '/page/') === 0;
}

function is_category() {
    global $route;
    return strpos($route, '/category/') === 0;
}

function is_search() {
    global $route;
    return $route === '/search';
}

function is_404() {
    global $route;
    return $route === '/404';
}
?>