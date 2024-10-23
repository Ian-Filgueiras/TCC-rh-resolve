var cta = document.querySelector(".cta");
var check = 0;

cta.addEventListener('click', function(e){
    var text = document.querySelector('.login-text .text');
    var loginText = document.querySelector('.login-text');

    if (text) {
        text.classList.toggle('show-hide');
    } else {
        console.error("Elemento text não encontrado.");
    }

    if (loginText) {
        loginText.classList.toggle('expand');
    } else {
        console.error("Elemento loginText não encontrado.");
    }

    if(check == 0) {
        cta.innerHTML = "<i class=\"fas fa-chevron-up\"></i>";
        check++;
    } else {
        cta.innerHTML = "<i class=\"fas fa-chevron-down\"></i>";
        check = 0;
    }
});
