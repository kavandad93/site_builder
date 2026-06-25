<?php
/**
 * =============================================
 * سازنده Sitemap.xml با JSON
 * =============================================
 */

function generate_sitemap() {
    $sitemap = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    
    $sitemap .= '
    <url>
        <loc>' . BASE_URL . '/</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>';
    
    $posts = get_posts(['status' => 'published', 'limit' => 1000]);
    foreach ($posts as $post) {
        $sitemap .= '
    <url>
        <loc>' . get_permalink('post', $post['slug']) . '</loc>
        <lastmod>' . date('Y-m-d', strtotime($post['updated_at'] ?? $post['created_at'])) . '</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>';
    }
    
    $pages = get_pages(['status' => 'published']);
    foreach ($pages as $page) {
        $sitemap .= '
    <url>
        <loc>' . get_permalink('page', $page['slug']) . '</loc>
        <lastmod>' . date('Y-m-d', strtotime($page['updated_at'] ?? $page['created_at'])) . '</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>';
    }
    
    $categories = get_categories();
    foreach ($categories as $category) {
        $sitemap .= '
    <url>
        <loc>' . BASE_URL . '/category/' . $category['slug'] . '</loc>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>';
    }
    
    $sitemap .= '
</urlset>';
    
    file_put_contents(BASE_PATH . '/sitemap.xml', $sitemap);
    return true;
}
?>