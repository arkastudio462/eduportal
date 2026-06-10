window.togglePassword = function () {
    const input = document.getElementById('password');
    const icon = document.getElementById('password-icon');
    if (!input || !icon) return;

    if (input.type === 'password') {
        input.type = 'text';
        icon.innerText = 'visibility_off';
    } else {
        input.type = 'password';
        icon.innerText = 'visibility';
    }
};
