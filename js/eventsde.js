// Manipulação do DOM / eventos — conecta elementos da página a comportamentos JS.
var btnSignin = document.querySelector("#signin");

// Manipulação do DOM / eventos — conecta elementos da página a comportamentos JS.
var btnSignup = document.querySelector("#signup");

// Manipulação do DOM / eventos — conecta elementos da página a comportamentos JS.
var body = document.querySelector("body");

// Manipulação do DOM / eventos — conecta elementos da página a comportamentos JS.
btnSignin.addEventListener("click", function () {
  body.className = "sign-in-js";
});

// Manipulação do DOM / eventos — conecta elementos da página a comportamentos JS.
btnSignup.addEventListener("click", function () {
  body.className = "sign-up-js";
});

// Manipulação do DOM / eventos — conecta elementos da página a comportamentos JS.
senha.addEventListener("focus", () => {
  requirements.style.display = "block";
});