document.addEventListener('DOMContentLoaded', () => {
  // Cargar el header
fetch('../components/header.html')
    .then(response => response.text())
    .then(data => {
    document.getElementById('header-container').innerHTML = data;
});

    // Cargar el footer
fetch('../components/footer.html')
    .then(response => response.text())
    .then(data => {
    document.getElementById('footer-container').innerHTML = data;
    });
});