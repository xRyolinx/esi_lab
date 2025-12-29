const slides = document.querySelectorAll("#slider .slide");
let current = 0;

setInterval(() => {
  slides[current].classList.remove("opacity-100");
  slides[current].classList.add("opacity-0");

  current = (current + 1) % slides.length;

  slides[current].classList.remove("opacity-0");
  slides[current].classList.add("opacity-100");
}, 5000); // 5 secondes
