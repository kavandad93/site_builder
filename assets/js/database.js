// =============================================
// DATABASE SYSTEM - با localStorage
// =============================================

const DB = {
    // ===== کلیدهای ذخیره‌سازی =====
    keys: {
        users: 'cms_users',
        posts: 'cms_posts',
        pages: 'cms_pages',
        categories: 'cms_categories',
        media: 'cms_media',
        settings: 'cms_settings'
    },

    // ===== مقداردهی اولیه =====
    init() {
        if (!localStorage.getItem(this.keys.users)) {
            const admin = {
                id: 1,
                username: 'admin',
                email: 'admin@site.com',
                password: btoa('admin123'),
                display_name: 'مدیر سایت',
                role: 'admin',
                avatar: '',
                created_at: new Date().toISOString()
            };
            this.set(this.keys.users, [admin]);
        }

        if (!localStorage.getItem(this.keys.settings)) {
            this.set(this.keys.settings, {
                site_title: 'سایت‌ساز حرفه‌ای',
                site_description: 'سیستم مدیریت محتوای کامل',
                posts_per_page: 6
            });
        }

        if (!localStorage.getItem(this.keys.categories)) {
            this.set(this.keys.categories, [
                { id: 1, name: 'آموزش', slug: 'آموزش', description: 'مطالب آموزشی' },
                { id: 2, name: 'اخبار', slug: 'اخبار', description: 'آخرین اخبار' },
                { id: 3, name: 'مقالات', slug: 'مقالات', description: 'مقالات تخصصی' }
            ]);
        }

        if (!localStorage.getItem(this.keys.posts)) {
            const samplePosts = [
                {
                    id: 1,
                    title: 'آموزش ساخت سایت‌ساز حرفه‌ای',
                    slug: 'آموزش-ساخت-سایت‌ساز-حرفه‌ای',
                    content: '<p>این یک پست نمونه است. شما می‌توانید مطالب خود را در اینجا ایجاد و مدیریت کنید.</p>',
                    excerpt: 'آموزش کامل ساخت یک سیستم مدیریت محتوای حرفه‌ای',
                    featured_image: '',
                    status: 'published',
                    type: 'post',
                    category_id: 1,
                    author_id: 1,
                    views: 120,
                    created_at: new Date().toISOString(),
                    published_at: new Date().toISOString()
                },
                {
                    id: 2,
                    title: 'معرفی بهترین افزونه‌های مدیریت محتوا',
                    slug: 'معرفی-بهترین-افزونه‌ها',
                    content: '<p>در این مطلب بهترین افزونه‌های مدیریت محتوا را معرفی می‌کنیم.</p>',
                    excerpt: '۱۰ افزونه برتر برای مدیریت محتوای سایت',
                    featured_image: '',
                    status: 'published',
                    type: 'post',
                    category_id: 2,
                    author_id: 1,
                    views: 85,
                    created_at: new Date().toISOString(),
                    published_at: new Date().toISOString()
                }
            ];
            this.set(this.keys.posts, samplePosts);
        }

        if (!localStorage.getItem(this.keys.pages)) {
            this.set(this.keys.pages, [
                {
                    id: 1,
                    title: 'درباره ما',
                    slug: 'درباره-ما',
                    content: '<p>این صفحه درباره ما است.</p>',
                    status: 'published',
                    author_id: 1,
                    created_at: new Date().toISOString()
                },
                {
                    id: 2,
                    title: 'تماس با ما',
                    slug: 'تماس-با-ما',
                    content: '<p>فرم تماس با ما</p>',
                    status: 'published',
                    author_id: 1,
                    created_at: new Date().toISOString()
                }
            ]);
        }
    },

    get(key) {
        try {
            return JSON.parse(localStorage.getItem(key)) || [];
        } catch {
            return [];
        }
    },

    set(key, data) {
        localStorage.setItem(key, JSON.stringify(data));
    },

    getById(key, id) {
        const items = this.get(key);
        return items.find(item => item.id === id) || null;
    },

    create(key, data) {
        const items = this.get(key);
        const newItem = {
            id: items.length > 0 ? Math.max(...items.map(i => i.id)) + 1 : 1,
            ...data,
            created_at: new Date().toISOString(),
            updated_at: new Date().toISOString()
        };
        items.push(newItem);
        this.set(key, items);
        return newItem;
    },

    update(key, id, data) {
        const items = this.get(key);
        const index = items.findIndex(item => item.id === id);
        if (index === -1) return null;
        items[index] = { ...items[index], ...data, updated_at: new Date().toISOString() };
        this.set(key, items);
        return items[index];
    },

    delete(key, id) {
        const items = this.get(key);
        const filtered = items.filter(item => item.id !== id);
        this.set(key, filtered);
        return filtered;
    },

    getStats() {
        const posts = this.get(this.keys.posts);
        const pages = this.get(this.keys.pages);
        const users = this.get(this.keys.users);
        const categories = this.get(this.keys.categories);
        
        return {
            total_posts: posts.length,
            published_posts: posts.filter(p => p.status === 'published').length,
            total_pages: pages.length,
            total_users: users.length,
            total_categories: categories.length,
            total_views: posts.reduce((sum, p) => sum + (p.views || 0), 0)
        };
    }
};

DB.init();
window.DB = DB;

console.log('✅ دیتابیس راه‌اندازی شد!');