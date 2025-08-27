/**
 * Toast Notification System
 * Provides beautiful toast notifications for user feedback
 */

class ToastNotification {
    constructor() {
        this.container = this.createContainer();
        this.toastCount = 0;
    }

    createContainer() {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed top-4 right-4 z-50 space-y-2';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
        }
        return container;
    }

    show(options = {}) {
        const {
            type = 'info',
            title = '',
            message = '',
            duration = 5000,
            showProgress = true
        } = options;

        const toast = this.createToast(type, title, message, duration, showProgress);
        this.container.appendChild(toast);
        this.toastCount++;

        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');
        }, 100);

        // Auto remove
        if (duration > 0) {
            setTimeout(() => {
                this.remove(toast);
            }, duration);
        }

        return toast;
    }

    createToast(type, title, message, duration, showProgress) {
        const toast = document.createElement('div');
        toast.className = `
            transform transition-all duration-300 ease-in-out
            translate-x-full opacity-0
            max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto
            ring-1 ring-black ring-opacity-5 overflow-hidden
        `;

        const colors = {
            success: {
                icon: '✅',
                iconBg: 'bg-green-100',
                iconColor: 'text-green-600',
                progressBg: 'bg-green-500'
            },
            error: {
                icon: '❌',
                iconBg: 'bg-red-100',
                iconColor: 'text-red-600',
                progressBg: 'bg-red-500'
            },
            warning: {
                icon: '⚠️',
                iconBg: 'bg-yellow-100',
                iconColor: 'text-yellow-600',
                progressBg: 'bg-yellow-500'
            },
            info: {
                icon: 'ℹ️',
                iconBg: 'bg-blue-100',
                iconColor: 'text-blue-600',
                progressBg: 'bg-blue-500'
            }
        };

        const color = colors[type] || colors.info;

        toast.innerHTML = `
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="${color.iconBg} rounded-full p-2">
                            <span class="text-lg">${color.icon}</span>
                        </div>
                    </div>
                    <div class="ml-3 w-0 flex-1">
                        ${title ? `<p class="text-sm font-medium text-gray-900">${title}</p>` : ''}
                        <p class="text-sm text-gray-500 ${title ? 'mt-1' : ''}">${message}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button class="toast-close bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            ${showProgress && duration > 0 ? `
                <div class="h-1 bg-gray-200">
                    <div class="h-full ${color.progressBg} progress-bar" style="animation: progress ${duration}ms linear;"></div>
                </div>
            ` : ''}
        `;

        // Add close functionality
        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', () => {
            this.remove(toast);
        });

        return toast;
    }

    remove(toast) {
        toast.classList.remove('translate-x-0', 'opacity-100');
        toast.classList.add('translate-x-full', 'opacity-0');
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
            this.toastCount--;
        }, 300);
    }

    success(title, message, duration = 5000) {
        return this.show({
            type: 'success',
            title,
            message,
            duration
        });
    }

    error(title, message, duration = 7000) {
        return this.show({
            type: 'error',
            title,
            message,
            duration
        });
    }

    warning(title, message, duration = 6000) {
        return this.show({
            type: 'warning',
            title,
            message,
            duration
        });
    }

    info(title, message, duration = 5000) {
        return this.show({
            type: 'info',
            title,
            message,
            duration
        });
    }

    clear() {
        const toasts = this.container.querySelectorAll('[class*="transform"]');
        toasts.forEach(toast => this.remove(toast));
    }
}

// Create global instance
window.Toast = new ToastNotification();

// Add CSS for progress animation
const style = document.createElement('style');
style.textContent = `
    @keyframes progress {
        from { width: 100%; }
        to { width: 0%; }
    }
    
    .progress-bar {
        transition: width 0.1s ease-out;
    }
`;
document.head.appendChild(style);

// Auto-show toast from session data
document.addEventListener('DOMContentLoaded', function() {
    // Check for Laravel session flash data
    const toastData = window.toastData;
    if (toastData) {
        Toast.show(toastData);
    }
});
