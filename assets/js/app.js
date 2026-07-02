/**
 * Shared utilities for SSU Student Portal
 */

const App = {
    showAlert(message, type = 'error', errors = []) {
        const container = document.getElementById('alert-container');
        if (!container) return;

        let content = message;

        if (errors.length > 0) {
            content += '<ul>' + errors.map(e => `<li>${this.escapeHtml(e)}</li>`).join('') + '</ul>';
        }

        container.innerHTML = `<div class="alert alert-${type}">${content}</div>`;

        container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    },

    clearAlert() {
        const container = document.getElementById('alert-container');
        if (container) container.innerHTML = '';
    },

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    },

    setFieldError(fieldId, message) {
        const input = document.getElementById(fieldId);
        const errorEl = document.getElementById(`${fieldId}-error`);

        if (input) {
            input.classList.toggle('error', !!message);
        }
        if (errorEl) {
            errorEl.textContent = message || '';
        }
    },

    clearFieldErrors(fieldIds) {
        fieldIds.forEach(id => this.setFieldError(id, ''));
    },

    setLoading(button, loading) {
        if (!button) return;

        const text = button.querySelector('.btn-text');
        const loader = button.querySelector('.btn-loader');

        button.disabled = loading;
        if (text) text.hidden = loading;
        if (loader) loader.hidden = !loading;
    },

    async apiRequest(url, options = {}) {
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        };

        const response = await fetch(url, { ...defaultOptions, ...options });
        const data = await response.json();
        return { response, data };
    },

    validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    },

    validateContactNumber(contact) {
        const cleaned = contact.replace(/[\s\-\(\)]/g, '');
        return /^(\+63|0)?9\d{9}$|^(\+63|0)?\d{7,11}$/.test(cleaned);
    },

    validateFullName(name) {
        return /^[a-zA-Z\s\.\-\']{2,150}$/.test(name);
    },

    validatePassword(password) {
        const errors = [];
        if (password.length < 8) errors.push('At least 8 characters');
        if (!/[A-Z]/.test(password)) errors.push('One uppercase letter');
        if (!/[a-z]/.test(password)) errors.push('One lowercase letter');
        if (!/[0-9]/.test(password)) errors.push('One number');
        return errors;
    },

    getFormData(form) {
        const formData = new FormData(form);
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });
        return data;
    },

    initPasswordToggles() {
        document.querySelectorAll('.toggle-password').forEach(btn => {
            btn.addEventListener('click', () => {
                const targetId = btn.dataset.target;
                const input = document.getElementById(targetId);
                if (!input) return;

                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                btn.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
            });
        });
    },

    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-PH', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        });
    },

    getInitials(name) {
        return name
            .split(' ')
            .filter(Boolean)
            .slice(0, 2)
            .map(word => word[0])
            .join('')
            .toUpperCase();
    },
};

document.addEventListener('DOMContentLoaded', () => {
    App.initPasswordToggles();
});
