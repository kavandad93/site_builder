<header class="admin-header">
    <div class="admin-header-inner">
        <div class="admin-logo">
            <a href="index.php">🚀 <span>Site</span>Builder</a>
        </div>
        <div class="admin-header-right">
            <span class="admin-user">
                <?php 
                $user = current_user();
                echo $user ? $user['display_name'] ?? $user['username'] : 'کاربر';
                ?>
            </span>
            <span class="user-avatar">
                <?php echo substr($user['display_name'] ?? $user['username'] ?? 'U', 0, 1); ?>
            </span>
            <a href="logout.php" class="logout-link">🚪 خروج</a>
        </div>
    </div>
</header>