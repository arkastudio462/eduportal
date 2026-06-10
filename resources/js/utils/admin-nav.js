document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('nav a');
    if (!navLinks.length) return;

    navLinks.forEach(link => {
        link.addEventListener('click', function () {
            navLinks.forEach(l => {
                l.classList.remove('bg-secondary-container', 'text-on-secondary-container', 'scale-95');
                l.classList.add('text-on-primary-fixed-variant');
            });
            this.classList.add('bg-secondary-container', 'text-on-secondary-container', 'scale-95');
            this.classList.remove('text-on-primary-fixed-variant');
        });
    });
});
