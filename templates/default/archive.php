<section class="blog-section">
    <div class="container">
        <h1 class="section-title"><?php echo $category['name']; ?></h1>
        <?php if (!empty($category['description'])): ?>
        <p style="text-align:center; color:var(--gray); margin-bottom:30px;">
            <?php echo $category['description']; ?>
        </p>
        <?php endif; ?>
        
        <div class="blog-grid">
            <?php foreach ($posts as $post): ?>
            <div class="post-card">
                <div class="post-content">
                    <div class="post-meta">
                        <?php echo format_date($post['created_at']); ?>
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