// Admin JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Date picker default to today
    const dateInput = document.getElementById('date');
    if (dateInput && !dateInput.value) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.value = today;
        dateInput.min = today; // Optional: prevent selecting past dates
    }
    
    // Time picker default to current time + 1 hour
    const timeInput = document.getElementById('time');
    if (timeInput && !timeInput.value) {
        const now = new Date();
        now.setHours(now.getHours() + 1);
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        timeInput.value = `${hours}:${minutes}`;
    }
    
    // Confirm before deleting
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                e.preventDefault();
            }
        });
    });
    
    // Auto-hide messages after 5 seconds
    const messages = document.querySelectorAll('.message');
    messages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            message.style.transition = 'opacity 0.5s ease';
            setTimeout(() => {
                if (message.parentNode) {
                    message.parentNode.removeChild(message);
                }
            }, 500);
        }, 5000);
    });
    
    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#dc3545';
                    
                    // Add error message if not exists
                    if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('error-message')) {
                        const errorMsg = document.createElement('div');
                        errorMsg.className = 'error-message';
                        errorMsg.textContent = 'Field ini wajib diisi';
                        errorMsg.style.color = '#dc3545';
                        errorMsg.style.fontSize = '0.85rem';
                        errorMsg.style.marginTop = '5px';
                        field.parentNode.appendChild(errorMsg);
                    }
                } else {
                    field.style.borderColor = '#ddd';
                    
                    // Remove error message if exists
                    if (field.nextElementSibling && field.nextElementSibling.classList.contains('error-message')) {
                        field.parentNode.removeChild(field.nextElementSibling);
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Harap lengkapi semua field yang wajib diisi!');
            }
        });
    });
    
    // Price formatting
    const priceInputs = document.querySelectorAll('input[name="price"]');
    priceInputs.forEach(input => {
        input.addEventListener('input', function() {
            let value = this.value.replace(/[^\d]/g, '');
            if (value) {
                this.value = parseInt(value).toLocaleString('id-ID');
            }
        });
        
        // Format on blur
        input.addEventListener('blur', function() {
            let value = this.value.replace(/[^\d]/g, '');
            if (value) {
                this.value = parseInt(value);
            }
        });
    });
    
    // Search functionality for tables
    const searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.placeholder = 'Cari...';
    searchInput.style.marginBottom = '15px';
    searchInput.style.padding = '10px';
    searchInput.style.width = '300px';
    searchInput.style.border = '1px solid #ddd';
    searchInput.style.borderRadius = '5px';
    
    const tables = document.querySelectorAll('table');
    tables.forEach(table => {
        const container = table.parentNode;
        if (container.classList.contains('table-container')) {
            container.insertBefore(searchInput.cloneNode(true), table);
            
            const search = container.querySelector('input[type="text"]');
            search.addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                const rows = table.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                });
            });
        }
    });
});