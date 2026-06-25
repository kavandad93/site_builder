document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('آیا از حذف این آیتم اطمینان دارید؟')) {
                window.location.href = this.href;
            }
        });
    });
});

function deleteItem(table, id) {
    if (confirm('آیا از حذف این آیتم اطمینان دارید؟')) {
        window.location.href = '?action=delete&id=' + id;
    }
}

function showMessage(message, type = 'success') {
    const container = document.getElementById('message-container');
    if (!container) return;
    
    const alert = document.createElement('div');
    alert.className = 'alert alert-' + type;
    alert.textContent = message;
    container.appendChild(alert);
    
    setTimeout(() => {
        alert.style.display = 'none';
    }, 5000);
}

function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}