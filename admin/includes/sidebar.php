<aside class="admin-sidebar">
    <nav class="admin-nav">
        <a href="index.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
            <span class="nav-icon">📊</span>
            <span class="nav-label">پیشخوان</span>
        </a>
        <a href="posts.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'posts.php' ? 'active' : ''; ?>">
            <span class="nav-icon">📝</span>
            <span class="nav-label">مطالب</span>
        </a>
        <a href="pages.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'pages.php' ? 'active' : ''; ?>">
            <span class="nav-icon">📄</span>
            <span class="nav-label">صفحات</span>
        </a>
        <a href="categories.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>">
            <span class="nav-icon">🏷️</span>
            <span class="nav-label">دسته‌بندی‌ها</span>
        </a>
        <a href="media.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'media.php' ? 'active' : ''; ?>">
            <span class="nav-icon">🖼️</span>
            <span class="nav-label">رسانه</span>
        </a>
        <a href="users.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>">
            <span class="nav-icon">👤</span>
            <span class="nav-label">کاربران</span>
        </a>
        <a href="comments.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'comments.php' ? 'active' : ''; ?>">
            <span class="nav-icon">💬</span>
            <span class="nav-label">نظرات</span>
        </a>
        <a href="builder.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'builder.php' ? 'active' : ''; ?>">
            <span class="nav-icon">🧩</span>
            <span class="nav-label">سازنده صفحه</span>
        </a>
        <a href="seo.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'seo.php' ? 'active' : ''; ?>">
            <span class="nav-icon">🔍</span>
            <span class="nav-label">سئو</span>
        </a>
        <a href="settings.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
            <span class="nav-icon">⚙️</span>
            <span class="nav-label">تنظیمات</span>
        </a>
    </nav>
</aside>