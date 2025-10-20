// Função JS: implementa comportamento reutilizável no front-end.
function Strength(password) {
  let i = 0;
  if (password.length > 6) {
    i++;
  }

  if (password.length >= 10) {
    i++;
  }

  if (/[A-Z]/.test(password)) {
    i++;
  }

  if (/[0-9]/.test(password)) {
    i++;
  }

  if (/[A-Za-z0-8]/.test(password)) {
    i++;
  }

  return i;
}

// Manipulação do DOM / eventos — conecta elementos da página a comportamentos JS.
let container = document.querySelector(".containera");

// Manipulação do DOM / eventos — conecta elementos da página a comportamentos JS.
document.addEventListener("keyup", function (e) {

  // Manipulação do DOM / eventos — conecta elementos da página a comportamentos JS.
  let password = document.querySelector("#YourPassword").value;

  let strength = Strength(password);

  if (strength <= 2) {
    container.classList.add("weak");
    container.classList.remove("moderate");
    container.classList.remove("strong");
  } else if (strength >= 2 && strength <= 4) {
    container.classList.remove("weak");
    container.classList.add("moderate");
    container.classList.remove("strong");
  } else {
    container.classList.remove("weak");
    container.classList.remove("moderate");
    container.classList.add("strong");
  }

});

// Seleciona todos os pares input[type=password] com botão .show
// Manipulação do DOM / eventos — conecta elementos da página a comportamentos JS.
document.querySelectorAll(".label-input").forEach((label) => {

  let input = label.querySelector("input[type='password']");
  let toggle = label.querySelector(".show");

  if (input && toggle) {
    // inicializa sempre só com .show
    toggle.classList.remove("hide");

    toggle.onclick = function () {
      if (input.type === "password") {

        input.type = "text";
        toggle.classList.add("hide"); // troca ícone para "olho fechado"

      } else {

        input.type = "password";
        toggle.classList.remove("hide"); // volta para "olho aberto"
        
      }
    };
  }
});