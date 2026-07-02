document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('register-form');
    const submitBtn = document.getElementById('submit-btn');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        App.clearAlert();

        const fields = ['full_name', 'contact_number', 'email', 'password', 'confirm_password'];
        App.clearFieldErrors(fields);

        const data = App.getFormData(form);
        let valid = true;

        if (!data.full_name) {
            App.setFieldError('full_name', 'Full name is required.');
            valid = false;
        } else if (!App.validateFullName(data.full_name)) {
            App.setFieldError('full_name', 'Full name contains invalid characters.');
            valid = false;
        }

        if (!data.contact_number) {
            App.setFieldError('contact_number', 'Contact number is required.');
            valid = false;
        } else if (!App.validateContactNumber(data.contact_number)) {
            App.setFieldError('contact_number', 'Please enter a valid Philippine contact number.');
            valid = false;
        }

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
        } else {
            const pwErrors = App.validatePassword(data.password);
            if (pwErrors.length > 0) {
                App.setFieldError('password', 'Must have: ' + pwErrors.join(', ') + '.');
                valid = false;
            }
        }

        if (!data.confirm_password) {
            App.setFieldError('confirm_password', 'Please confirm your password.');
            valid = false;
        } else if (data.password !== data.confirm_password) {
            App.setFieldError('confirm_password', 'Passwords do not match.');
            valid = false;
        }

        if (!valid) return;

        App.setLoading(submitBtn, true);

        try {
            const { data: result } = await App.apiRequest('api/register.php', {
                method: 'POST',
                body: JSON.stringify(data),
            });

            if (result.success) {
                App.showAlert(result.message, 'success');
                form.reset();
                setTimeout(() => {
                    window.location.href = 'login.php';
                }, 1500);
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
