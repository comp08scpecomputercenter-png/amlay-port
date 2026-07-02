document.addEventListener('DOMContentLoaded', () => {
    const profileView = document.getElementById('profile-view');
    const profileEdit = document.getElementById('profile-edit');
    const editBtn = document.getElementById('edit-profile-btn');
    const cancelBtn = document.getElementById('cancel-edit-btn');
    const editForm = document.getElementById('edit-profile-form');
    const saveBtn = document.getElementById('save-profile-btn');
    const logoutBtn = document.getElementById('logout-btn');

    let currentProfile = null;

    async function loadProfile() {
        try {
            const { data: result } = await App.apiRequest('api/profile.php');

            if (!result.success) {
                if (result.message === 'Unauthorized.') {
                    window.location.href = 'login.php';
                    return;
                }
                App.showAlert(result.message, 'error');
                return;
            }

            currentProfile = result.student;
            renderProfile(currentProfile);
        } catch (err) {
            App.showAlert('Failed to load profile. Please refresh the page.', 'error');
        }
    }

    function renderProfile(student) {
        document.getElementById('view-full-name').textContent = student.full_name;
        document.getElementById('view-contact-number').textContent = student.contact_number;
        document.getElementById('view-email').textContent = student.email;
        document.getElementById('view-created-at').textContent = App.formatDate(student.created_at);
        document.getElementById('avatar-initials').textContent = App.getInitials(student.full_name);

        const headerName = document.getElementById('header-name');
        if (headerName) {
            headerName.textContent = student.full_name.split(' ')[0];
        }
    }

    function populateEditForm(student) {
        document.getElementById('edit_full_name').value = student.full_name;
        document.getElementById('edit_contact_number').value = student.contact_number;
        document.getElementById('edit_email').value = student.email;
        document.getElementById('current_password').value = '';
        document.getElementById('new_password').value = '';
        document.getElementById('confirm_password').value = '';
    }

    editBtn.addEventListener('click', () => {
        if (!currentProfile) return;
        populateEditForm(currentProfile);
        profileView.hidden = true;
        profileEdit.hidden = false;
        App.clearAlert();
    });

    cancelBtn.addEventListener('click', () => {
        profileEdit.hidden = true;
        profileView.hidden = false;
        App.clearAlert();
        const fields = [
            'edit_full_name', 'edit_contact_number', 'edit_email',
            'current_password', 'new_password', 'confirm_password',
        ];
        App.clearFieldErrors(fields);
    });

    editForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        App.clearAlert();

        const fields = [
            'edit_full_name', 'edit_contact_number', 'edit_email',
            'current_password', 'new_password', 'confirm_password',
        ];
        App.clearFieldErrors(fields);

        const data = App.getFormData(editForm);
        let valid = true;

        if (!data.full_name) {
            App.setFieldError('edit_full_name', 'Full name is required.');
            valid = false;
        } else if (!App.validateFullName(data.full_name)) {
            App.setFieldError('edit_full_name', 'Full name contains invalid characters.');
            valid = false;
        }

        if (!data.contact_number) {
            App.setFieldError('edit_contact_number', 'Contact number is required.');
            valid = false;
        } else if (!App.validateContactNumber(data.contact_number)) {
            App.setFieldError('edit_contact_number', 'Please enter a valid Philippine contact number.');
            valid = false;
        }

        if (!data.email) {
            App.setFieldError('edit_email', 'Email is required.');
            valid = false;
        } else if (!App.validateEmail(data.email)) {
            App.setFieldError('edit_email', 'Please enter a valid email address.');
            valid = false;
        }

        const changingPassword = data.new_password || data.confirm_password || data.current_password;

        if (changingPassword) {
            if (!data.current_password) {
                App.setFieldError('current_password', 'Current password is required.');
                valid = false;
            }
            if (!data.new_password) {
                App.setFieldError('new_password', 'New password is required.');
                valid = false;
            } else {
                const pwErrors = App.validatePassword(data.new_password);
                if (pwErrors.length > 0) {
                    App.setFieldError('new_password', 'Must have: ' + pwErrors.join(', ') + '.');
                    valid = false;
                }
            }
            if (!data.confirm_password) {
                App.setFieldError('confirm_password', 'Please confirm your new password.');
                valid = false;
            } else if (data.new_password !== data.confirm_password) {
                App.setFieldError('confirm_password', 'Passwords do not match.');
                valid = false;
            }
        }

        if (!valid) return;

        App.setLoading(saveBtn, true);

        try {
            const { data: result } = await App.apiRequest('api/update-profile.php', {
                method: 'POST',
                body: JSON.stringify(data),
            });

            if (result.success) {
                App.showAlert(result.message, 'success');
                profileEdit.hidden = true;
                profileView.hidden = false;
                await loadProfile();
            } else {
                App.showAlert(result.message, 'error', result.errors || []);
            }
        } catch (err) {
            App.showAlert('An unexpected error occurred. Please try again.', 'error');
        } finally {
            App.setLoading(saveBtn, false);
        }
    });

    logoutBtn.addEventListener('click', async () => {
        const csrfToken = document.getElementById('csrf-token').value;

        try {
            const { data: result } = await App.apiRequest('api/logout.php', {
                method: 'POST',
                body: JSON.stringify({ csrf_token: csrfToken }),
            });

            if (result.success) {
                window.location.href = result.redirect || 'login.php';
            }
        } catch (err) {
            window.location.href = 'login.php';
        }
    });

    loadProfile();
});
