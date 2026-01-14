let current = 0;
const slides = document.querySelectorAll("#diaporama .slide-diapo");
const prevBtn = document.querySelector("#diaporama .diapo-prev");
const nextBtn = document.querySelector("#diaporama .diapo-next");

function showSlide(idx) {
  slides.forEach((slide, i) => {
    // text
    let txt = slide.querySelector(".diapo-text");
    if (i === idx) {
      slide.classList.add("opacity-100", "z-5");
      slide.classList.remove("opacity-0", "z-0");
      txt.classList.add("flex");
      txt.classList.remove("hidden");
    } else {
      slide.classList.add("opacity-0", "z-0");
      slide.classList.remove("opacity-100", "z-5");
      txt.classList.add("hidden");
      txt.classList.remove("flex");
    }
  });
}
prevBtn.addEventListener("click", function () {
  current = (current - 1 + slides.length) % slides.length;
  showSlide(current);
});
nextBtn.addEventListener("click", function () {
  current = (current + 1) % slides.length;
  showSlide(current);
});

// chaque 5s
setInterval(function () {
  current = (current + 1) % slides.length;
  showSlide(current);
}, 5000);