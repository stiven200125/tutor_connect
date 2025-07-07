// Mostrar/Ocultar contraseñas
document.querySelectorAll('.toggle-password').forEach(btn => {
    btn.addEventListener('click', () => {
        const targetId = btn.getAttribute('data-target');
        const input = document.getElementById(targetId);
        const icon = btn.querySelector('i');

        if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye-slash-fill');
        icon.classList.add('bi-eye-fill');
        } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-fill');
        icon.classList.add('bi-eye-slash-fill');
        }
    });
});