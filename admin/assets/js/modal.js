document.addEventListener('DOMContentLoaded', () => {
    const loginLink = document.getElementById('login-link');

    if (loginLink) {
        loginLink.addEventListener('click', function (event) {

            event.preventDefault();
            bootstrap.Modal.getOrCreateInstance('#loginModal').show();
        });
    }
});