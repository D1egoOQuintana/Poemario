const $btnSignIn= document.querySelector('.sign-in-btn'),
      $btnSignUp = document.querySelector('.sign-up-btn'),  
      $signUp = document.querySelector('.sign-up'),
      $signIn  = document.querySelector('.sign-in');

document.addEventListener('click', e => {
    if (e.target === $btnSignIn || e.target === $btnSignUp) {
        $signIn.classList.toggle('active');
        $signUp.classList.toggle('active')
    }
});
// Mensaje Flotante
function mostrarMensaje(texto) {
    const mensajeDiv = document.getElementById('mensaje');
    mensajeDiv.innerHTML = texto;
    mensajeDiv.style.display = 'block';
    setTimeout(() => {
        mensajeDiv.style.opacity = '0';
        setTimeout(() => {
            mensajeDiv.style.display = 'none';
            mensajeDiv.style.opacity = '1';
        }, 500);
    }, 3000);
}
