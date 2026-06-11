// ============================================
// CustomAlert - Lightweight SweetAlert Alternative
// ============================================
class CustomAlert {
    constructor() {
        this.createStyles();
        this.createContainer();
    }

    // Create dynamic styles
    createStyles() {
        if (document.getElementById('custom-alert-styles')) return;
        
        const styles = document.createElement('style');
        styles.id = 'custom-alert-styles';
        styles.textContent = `
            /* Overlay */
            .custom-alert-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.4);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 99999;
                animation: alertFadeIn 0.2s ease;
                backdrop-filter: blur(2px);
            }

            /* Container */
            .custom-alert-container {
                background: #fff;
                border-radius: 12px;
                padding: 25px 30px;
                text-align: center;
                width: 90%;
                max-width: 400px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.15);
                animation: alertScaleIn 0.3s ease;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            }

            /* Icon */
            .custom-alert-icon {
                width: 70px;
                height: 70px;
                margin: 0 auto 15px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 35px;
                animation: alertIconPop 0.3s ease 0.1s both;
            }

            .custom-alert-icon.success {
                background: #e8f5e9;
                color: #4caf50;
                border: 3px solid #4caf50;
            }

            .custom-alert-icon.error {
                background: #ffebee;
                color: #f44336;
                border: 3px solid #f44336;
            }

            .custom-alert-icon.warning {
                background: #fff8e1;
                color: #ff9800;
                border: 3px solid #ff9800;
            }

            .custom-alert-icon.info {
                background: #e3f2fd;
                color: #2196f3;
                border: 3px solid #2196f3;
            }

            .custom-alert-icon.question {
                background: #f3e5f5;
                color: #9c27b0;
                border: 3px solid #9c27b0;
            }

            /* Title */
            .custom-alert-title {
                font-size: 20px;
                font-weight: 600;
                color: #333;
                margin: 10px 0;
                line-height: 1.3;
            }

            /* Text */
            .custom-alert-text {
                font-size: 14px;
                color: #666;
                margin: 10px 0 20px;
                line-height: 1.5;
            }

            /* Buttons Container */
            .custom-alert-buttons {
                display: flex;
                gap: 10px;
                justify-content: center;
                flex-wrap: wrap;
            }

            /* Buttons */
            .custom-alert-btn {
                padding: 10px 25px;
                border: none;
                border-radius: 6px;
                font-size: 14px;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.2s ease;
                outline: none;
                min-width: 100px;
            }

            .custom-alert-btn:active {
                transform: scale(0.95);
            }

            .custom-alert-btn.confirm {
                background: #4caf50;
                color: white;
            }

            .custom-alert-btn.confirm:hover {
                background: #45a049;
                box-shadow: 0 3px 10px rgba(76, 175, 80, 0.3);
            }

            .custom-alert-btn.cancel {
                background: #f5f5f5;
                color: #666;
                border: 1px solid #ddd;
            }

            .custom-alert-btn.cancel:hover {
                background: #eee;
            }

            .custom-alert-btn.danger {
                background: #f44336;
                color: white;
            }

            .custom-alert-btn.danger:hover {
                background: #e53935;
                box-shadow: 0 3px 10px rgba(244, 67, 54, 0.3);
            }

            /* Animations */
            @keyframes alertFadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            @keyframes alertScaleIn {
                from { 
                    transform: scale(0.7);
                    opacity: 0;
                }
                to { 
                    transform: scale(1);
                    opacity: 1;
                }
            }

            @keyframes alertIconPop {
                0% { 
                    transform: scale(0);
                    opacity: 0;
                }
                50% { 
                    transform: scale(1.2);
                }
                100% { 
                    transform: scale(1);
                    opacity: 1;
                }
            }

            /* Toast */
            .custom-toast {
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                background: white;
                border-radius: 8px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.15);
                z-index: 99999;
                display: flex;
                align-items: center;
                gap: 10px;
                min-width: 280px;
                max-width: 400px;
                animation: toastSlideIn 0.3s ease;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                font-size: 14px;
            }

            .custom-toast.success {
                border-left: 4px solid #4caf50;
            }

            .custom-toast.error {
                border-left: 4px solid #f44336;
            }

            .custom-toast.warning {
                border-left: 4px solid #ff9800;
            }

            .custom-toast.info {
                border-left: 4px solid #2196f3;
            }

            .custom-toast-icon {
                font-size: 20px;
                flex-shrink: 0;
            }

            .custom-toast-message {
                flex: 1;
                color: #333;
            }

            .custom-toast-close {
                cursor: pointer;
                color: #999;
                font-size: 18px;
                padding: 0 5px;
                background: none;
                border: none;
                flex-shrink: 0;
            }

            .custom-toast-close:hover {
                color: #333;
            }

            @keyframes toastSlideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            @keyframes toastSlideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(styles);
    }

    // Create overlay container
    createContainer() {
        this.overlay = document.createElement('div');
        this.overlay.className = 'custom-alert-overlay';
        this.overlay.style.display = 'none';
        document.body.appendChild(this.overlay);
    }

    // Get icon HTML
    getIcon(type) {
        const icons = {
            success: '✓',
            error: '✕',
            warning: '!',
            info: 'i',
            question: '?'
        };
        return icons[type] || 'i';
    }

    // Show alert
    show({
        title = '',
        text = '',
        icon = '',
        showCancelButton = false,
        confirmButtonText = 'OK',
        cancelButtonText = 'Cancel',
        confirmButtonClass = 'confirm',
        allowOutsideClick = true,
        showCloseButton = false
    } = {}) {
        return new Promise((resolve) => {
            // Build HTML
            let html = '<div class="custom-alert-container">';
            
            // Close button
            if (showCloseButton) {
                html += `
                    <div style="text-align: right; margin-bottom: -10px;">
                        <button class="custom-alert-close-btn" style="
                            background: none;
                            border: none;
                            font-size: 20px;
                            cursor: pointer;
                            color: #999;
                            padding: 0 5px;
                        ">×</button>
                    </div>
                `;
            }
            
            // Icon
            if (icon) {
                html += `
                    <div class="custom-alert-icon ${icon}">
                        ${this.getIcon(icon)}
                    </div>
                `;
            }
            
            // Title
            if (title) {
                html += `<div class="custom-alert-title">${title}</div>`;
            }
            
            // Text
            if (text) {
                html += `<div class="custom-alert-text">${text}</div>`;
            }
            
            // Buttons
            html += '<div class="custom-alert-buttons">';
            
            if (showCancelButton) {
                html += `
                    <button class="custom-alert-btn cancel" id="customAlertCancel">
                        ${cancelButtonText}
                    </button>
                `;
            }
            
            html += `
                <button class="custom-alert-btn ${confirmButtonClass}" id="customAlertConfirm">
                    ${confirmButtonText}
                </button>
            `;
            
            html += '</div></div>';
            
            // Set content
            this.overlay.innerHTML = html;
            this.overlay.style.display = 'flex';
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
            
            // Add event listeners
            const confirmBtn = document.getElementById('customAlertConfirm');
            const cancelBtn = document.getElementById('customAlertCancel');
            const closeBtn = document.querySelector('.custom-alert-close-btn');
            
            const closeAlert = (isConfirmed) => {
                this.overlay.style.display = 'none';
                document.body.style.overflow = '';
                resolve({ isConfirmed });
            };
            
            confirmBtn?.addEventListener('click', () => closeAlert(true));
            cancelBtn?.addEventListener('click', () => closeAlert(false));
            closeBtn?.addEventListener('click', () => closeAlert(false));
            
            // Close on overlay click
            if (allowOutsideClick) {
                this.overlay.addEventListener('click', (e) => {
                    if (e.target === this.overlay) {
                        closeAlert(false);
                    }
                });
            }
            
            // Focus confirm button
            setTimeout(() => confirmBtn?.focus(), 100);
        });
    }

    // Shortcut methods
    success(title, text = '') {
        return this.show({
            title,
            text,
            icon: 'success',
            confirmButtonText: 'OK'
        });
    }

    error(title, text = '') {
        return this.show({
            title,
            text,
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }

    warning(title, text = '', showCancelButton = false) {
        return this.show({
            title,
            text,
            icon: 'warning',
            showCancelButton,
            confirmButtonText: showCancelButton ? 'Ya' : 'OK',
            cancelButtonText: 'Batal',
            confirmButtonClass: showCancelButton ? 'warning' : 'confirm'
        });
    }

    info(title, text = '') {
        return this.show({
            title,
            text,
            icon: 'info',
            confirmButtonText: 'OK'
        });
    }

    question(title, text = '') {
        return this.show({
            title,
            text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        });
    }

    confirm(title, text = '', confirmText = 'Ya', cancelText = 'Batal') {
        return this.show({
            title,
            text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: confirmText,
            cancelButtonText: cancelText,
            confirmButtonClass: 'confirm'
        });
    }

    delete(title, text = '', confirmText = 'Hapus', cancelText = 'Batal') {
        return this.show({
            title,
            text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: confirmText,
            cancelButtonText: cancelText,
            confirmButtonClass: 'danger'
        });
    }

    // Toast notification
    toast(message, type = 'success', duration = 3000) {
        const icons = {
            success: `<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#4caf50" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>`,
            error: `<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f44336" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="15" y1="9" x2="9" y2="15"/>
                    <line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>`,
            warning: `<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#ff9800" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>`,
            info: `<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2196f3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="16" x2="12" y2="12"/>
                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                </svg>`,
            loading: `<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2196f3" stroke-width="2.5" class="toast-spinner">
                        <circle cx="12" cy="12" r="10" stroke-dasharray="31.4 31.4" stroke-linecap="round"/>
                    </svg>`
        };
        
        const toast = document.createElement('div');
        toast.className = `custom-toast ${type}`;
        toast.innerHTML = `
            <span class="custom-toast-icon">${icons[type]}</span>
            <span class="custom-toast-message">${message}</span>
            <button class="custom-toast-close">×</button>
        `;
        
        document.body.appendChild(toast);
        
        // Close button
        toast.querySelector('.custom-toast-close').addEventListener('click', () => {
            toast.style.animation = 'toastSlideOut 0.3s ease forwards';
            setTimeout(() => toast.remove(), 300);
        });
        
        // Auto remove
        if (duration > 0) {
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.style.animation = 'toastSlideOut 0.3s ease forwards';
                    setTimeout(() => toast.remove(), 300);
                }
            }, duration);
        }
        
        return toast;
    }
}
