document.addEventListener("DOMContentLoaded", function () {
  const pages = document.querySelectorAll(".org-diagram-page");
  const btns = document.querySelectorAll(".org-page-btn");
  btns.forEach((btn) => {
    btn.addEventListener("click", function () {
      const page = parseInt(this.getAttribute("data-page"));
      pages.forEach((d, idx) => {
        d.style.display = idx === page - 1 ? "" : "none";
      });
      btns.forEach((b) => {
        b.classList.remove("bg-primary-dark", "text-white", "font-bold");
        b.classList.add("bg-gray-200", "text-gray-700");
      });
      this.classList.remove("bg-gray-200", "text-gray-700");
      this.classList.add("bg-primary-dark", "text-white", "font-bold");
    });
  });
});
