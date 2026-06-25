<!-- ===== HERO ===== -->
<section class="hero">
    <div class="container">
        <h1><?php echo get_setting('site_title'); ?></h1>
        <p><?php echo get_setting('site_description'); ?></p>
        <a href="<?php echo BASE_URL; ?>/admin/login.php" class="btn-hero">شروع کنید</a>
    </div>
</section>

<!-- ===== BLOG ===== -->
<section class="blog-section">
    <div class="container">
        <h2 class="section-title">آخرین مطالب</h2>
        <div class="blog-grid">
            <?php foreach ($posts as $post): ?>
            <div class="post-card">
                <div class="post-image" style="background: #e9ecef; display:flex; align-items:center; justify-content:center; font-size:48px; color:var(--gray);">
                    <?php echo $post['featured_image'] ? '<img src="' . $post['featured_image'] . '" style="width:100%; height:100%; object-fit:cover;">' : '🖼️'; ?>
                </div>
                <div class="post-content">
                    <div class="post-meta">
                        <?php echo format_date($post['created_at']); ?> • 
                        <?php echo $post['views'] ?? 0; ?> بازدید
                    </div>
                    <h3 class="post-title">
                        <a href="<?php echo get_permalink('post', $post['slug']); ?>">
                            <?php echo $post['title']; ?>
                        </a>
                    </h3>
                    <p class="post-excerpt"><?php echo get_excerpt($post['content'], 150); ?></p>
                    <a href="<?php echo get_permalink('post', $post['slug']); ?>" class="read-more">ادامه مطلب →</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>