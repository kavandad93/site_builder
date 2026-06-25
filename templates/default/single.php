<section class="single-post">
    <div class="container">
        <article class="post">
            <header class="post-header">
                <h1 class="post-title"><?php echo $post['title']; ?></h1>
                <div class="post-meta">
                    نوشته شده توسط <?php echo $post['author_name'] ?? 'ناشناس'; ?> • 
                    <?php echo format_date($post['created_at']); ?> • 
                    <?php echo $post['views'] ?? 0; ?> بازدید
                    <?php if ($post['category_name']): ?>
                    • دسته: <?php echo $post['category_name']; ?>
                    <?php endif; ?>
                </div>
            </header>
            <div class="post-content">
                <?php echo $post['content']; ?>
            </div>
        </article>
    </div>
</section>