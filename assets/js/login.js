document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('login-form');
    const submitBtn = document.getElementById('submit-btn');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        App.clearAlert();
        App.clearFieldErrors(['email', 'password']);

        const data = App.getFormData(form);
        let valid = true;

        if (!data.email) {
            App.setFieldError('email', 'Email is required.');
            valid = false;
        } else if (!App.validateEmail(data.email)) {
            App.setFieldError('email', 'Please enter a valid email address.');
            valid = false;
        }

        if (!data.password) {
            App.setFieldError('password', 'Password is required.');
            valid = false;
        }

        if (!valid) return;

        App.setLoading(submitBtn, true);

        try {
            const { data: result } = await App.apiRequest('api/login.php', {
                method: 'POST',
                body: JSON.stringify(data),
            });

            if (result.success) {
                App.showAlert(result.message, 'success');
                setTimeout(() => {
                    window.location.href = result.redirect || 'dashboard.php';
                }, 800);
            } else {
                App.showAlert(result.message, 'error', result.errors || []);
            }
        } catch (err) {
            App.showAlert('An unexpected error occurred. Please try again.', 'error');
        } finally {
            App.setLoading(submitBtn, false);
        }
    });
});
