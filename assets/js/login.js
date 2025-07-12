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

// Mostrar modales según el parámetro en la URL (?exito=1 o ?error=1)
const params = new URLSearchParams(window.location.search);

if (params.get("error") === "1") {
    const errorModal = new bootstrap.Modal(document.getElementById('loginFallidoModal'));
    errorModal.show();
}