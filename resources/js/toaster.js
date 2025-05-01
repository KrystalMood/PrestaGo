/**
 * Toast Notification System
 * Manages toast notifications with different styles (success, error, info, warning)
 */

class Toaster {
    constructor() {
        this.toastContainer = null;
        this.toasts = [];
        this.counter = 0;
        this.initContainer();
    }

    /**
     * Initialize the toast container
     */
    initContainer() {
        if (!this.toastContainer) {
            this.toastContainer = document.createElement('div');
            this.toastContainer.id = 'toast-container';
            this.toastContainer.className = 'fixed bottom-4 right-4 z-50 flex flex-col-reverse gap-2';
            document.body.appendChild(this.toastContainer);
        }
    }

    /**
     * Create a new toast
     */
    createToast(type, message, options = {}) {
        const id = `toast-${++this.counter}`;
        const duration = options.duration || 5000;
        
        const styles = {
            success: {
                bg: 'bg-[#E6F7F0]',
                border: 'border-[#065F46]',
                text: 'text-[#065F46]',
                icon: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
            },
            error: {
                bg: 'bg-[#FEE2E2]',
                border: 'border-[#B91C1C]',
                text: 'text-[#B91C1C]',
                icon: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>'
            },
            info: {
                bg: 'bg-[#E0F2FE]',
                border: 'border-[#1E40AF]',
                text: 'text-[#1E40AF]',
                icon: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>'
            },
            warning: {
                bg: 'bg-[#FEF9C3]',
                border: 'border-[#B45309]',
                text: 'text-[#B45309]',
                icon: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>'
            }
        };
        
        const style = styles[type] || styles.info;
        
        const toastEl = document.createElement('div');
        toastEl.id = id;
        toastEl.className = `flex items-center p-3 mb-3 max-w-sm border rounded-md shadow-custom transform translate-y-20 opacity-0 transition-all duration-300 ${style.bg} ${style.text} border-l-4 ${style.border}`;
        
        toastEl.innerHTML = `
            <div class="flex items-center">
                <div class="flex-shrink-0 mr-3">
                    ${style.icon}
                </div>
                <div class="flex-grow text-sm font-medium pr-5">
                    ${message}
                </div>
            </div>
            <button class="absolute top-1.5 right-1.5 text-gray-500 hover:text-gray-700" onclick="document.getElementById('${id}').remove()">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        `;
        
        this.toastContainer.appendChild(toastEl);
        
        setTimeout(() => {
            toastEl.classList.remove('translate-y-20', 'opacity-0');
        }, 10);
        
        const timeoutId = setTimeout(() => {
            this.removeToast(id);
        }, duration);
        
        this.toasts.push({ id, el: toastEl, timeoutId });
        
        return id;
    }
    
    /**
     * Remove a toast by ID
     */
    removeToast(id) {
        const toast = this.toasts.find(t => t.id === id);
        if (toast) {
            const { el, timeoutId } = toast;
            
            el.classList.add('translate-y-2', 'opacity-0');
            
            clearTimeout(timeoutId);
            
            setTimeout(() => {
                el.remove();
                this.toasts = this.toasts.filter(t => t.id !== id);
            }, 300);
        }
    }
    
    /**
     * Success toast
     */
    success(message, options = {}) {
        return this.createToast('success', message, options);
    }
    
    /**
     * Error toast
     */
    error(message, options = {}) {
        return this.createToast('error', message, options);
    }
    
    /**
     * Info toast
     */
    info(message, options = {}) {
        return this.createToast('info', message, options);
    }
    
    /**
     * Warning toast
     */
    warning(message, options = {}) {
        return this.createToast('warning', message, options);
    }
}

window.toast = new Toaster();

export default window.toast;