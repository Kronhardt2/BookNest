// Manipulação do DOM / eventos — conecta elementos da página a comportamentos JS.
const sections = document.querySelectorAll(".sobre-section");

// Função JS: implementa comportamento reutilizável no front-end.
function updateSections() {

  const middle = window.innerHeight / 2;

  sections.forEach((sec) => {
    const rect = sec.getBoundingClientRect();

    if (rect.top < middle && rect.bottom > middle) {
      sec.classList.add("active");
    } else {
      sec.classList.remove("active");
    }

  });
}

// Manipulação do DOM / eventos — conecta elementos da página a comportamentos JS.
window.addEventListener("scroll", updateSections);

// Manipulação do DOM / eventos — conecta elementos da página a comportamentos JS.
window.addEventListener("load", updateSections);