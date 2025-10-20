// Manipulação do DOM / eventos — conecta elementos da página a comportamentos JS.
const track = document.querySelector(".carousel-track");
const slides = Array.from(track.children);

// Manipulação do DOM / eventos — conecta elementos da página a comportamentos JS.
const prevBtn = document.querySelector(".prev");

// Manipulação do DOM / eventos — conecta elementos da página a comportamentos JS.
const nextBtn = document.querySelector(".next");

// Manipulação do DOM / eventos — conecta elementos da página a comportamentos JS.
const dotsContainer = document.querySelector(".carousel-dots");

let index = 0;

// Cria bolinhas
slides.forEach((_, i) => {

  const dot = document.createElement("button");
  if (i === 0) dot.classList.add("active");
  dotsContainer.appendChild(dot);

// Manipulação do DOM / eventos — conecta elementos da página a comportamentos JS.
  dot.addEventListener("click", () => {
    index = i;
    updateCarousel();
  });

});

const dots = Array.from(dotsContainer.children);

// Função JS: implementa comportamento reutilizável no front-end.
function updateCarousel() {

  const width = slides[0].offsetWidth + 20; // gap 20px
  track.style.transform = `translateX(-${index * width}px)`;

  // Efeito de zoom no slide central
  slides.forEach((slide, i) => {
    slide.style.transform = i === index ? "scale(1.05)" : "scale(0.95)";
    slide.style.transition = "transform 0.5s";
  });

  // Atualiza bolinhas
  dots.forEach((dot) => dot.classList.remove("active"));
  dots[index].classList.add("active");

}

// Avançar
// Manipulação do DOM / eventos — conecta elementos da página a comportamentos JS.
nextBtn.addEventListener("click", () => {
  index++;
  if (index >= slides.length) index = 0;
  updateCarousel();
});

// Voltar
// Manipulação do DOM / eventos — conecta elementos da página a comportamentos JS.
prevBtn.addEventListener("click", () => {
  index--;
  if (index < 0) index = slides.length - 1;
  updateCarousel();
});

// Auto-play
setInterval(() => {
  index++;
  if (index >= slides.length) index = 0;
  updateCarousel();
}, 4000);

// Inicializa o carrossel
updateCarousel();