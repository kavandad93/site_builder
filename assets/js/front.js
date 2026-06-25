// =============================================
// FRONT-END SCRIPT
// =============================================

// ===== بارگذاری پست‌ها =====
function loadPosts() {
    const posts = DB.get(DB.keys.posts);
    const published = posts.filter(p => p.status === 'published').sort((a, b) => 
        new Date(b.created_at) - new Date(a.created_at)
    );
    
    const container = document.getElementById('blogPosts');
    if (!container) return;
    
    if (published.length === 0) {
        container.innerHTML = '<p style="text-align:center; color: var(--gray);">هیچ مطلبی یافت نشد.</p>';
        return;
    }
    
    container.innerHTML = published.map(post => `
        <div class="post-card">
            <div class="post-image" style="background: #e9ecef; display:flex; align-items:center; justify-content:center; font-size:48px; color:var(--gray);">
                ${post.featured_image ? `<img src="${post.featured_image}" style="width:100%; height:100%; object-fit:cover;">` : '🖼️'}
            </div>
            <div class="post-content">
                <div class="post-meta">${new Date(post.created_at).toLocaleDateString('fa-IR')} • ${post.views || 0} بازدید</div>
                <h3 class="post-title"><a href="#">${post.title}</a></h3>
                <p class="post-excerpt">${post.excerpt || post.content.substring(0, 150)}</p>
                <a href="#" class="read-more">ادامه مطلب →</a>
            </div>
        </div>
    `).join('');
}

// ===== اجرا =====
document.addEventListener('DOMContentLoaded', loadPosts);