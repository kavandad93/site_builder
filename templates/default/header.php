<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body>
    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="<?php echo BASE_URL; ?>/">
                        🚀 <span>Site</span>Builder
                    </a>
                </div>
                <nav class="main-nav">
                    <ul>
                        <li><a href="<?php echo BASE_URL; ?>/" <?php echo is_home() ? 'class="active"' : ''; ?>>خانه</a></li>
                        <?php 
                        $pages = get_nav_menu();
                        foreach ($pages as $page): 
                        ?>
                        <li><a href="<?php echo get_permalink('page', $page['slug']); ?>">
                            <?php echo $page['title']; ?>
                        </a></li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>