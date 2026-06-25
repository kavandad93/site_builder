<section class="blog-section">
    <div class="container">
        <h1 class="section-title">نتایج جستجو برای: <?php echo $query; ?></h1>
        
        <form class="search-form" action="<?php echo BASE_URL; ?>/search" method="GET">
            <input type="search" name="q" value="<?php echo $query; ?>" placeholder="جستجو...">
            <button type="submit">جستجو</button>
        </form>
        
        <?php if (empty($posts)): ?>
        <p style="text-align:center; color:var(--gray);">هیچ مطلبی یافت نشد.</p>
        <?php else: ?>
        <div class="blog-grid">
            <?php foreach ($posts as $post): ?>
            <div class="post-card">
                <div class="post-content">
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
        <?php endif; ?>
    </div>
</section>