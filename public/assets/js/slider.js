// assets/js/slider.js
document.addEventListener("DOMContentLoaded", () => {
  const slider = document.querySelector(".slider");
  if (!slider) return;

  const slides = Array.from(slider.querySelectorAll(".slide"));
  const prevBtn = slider.querySelector(".slider-btn.prev");
  const nextBtn = slider.querySelector(".slider-btn.next");
  const dotsWrap = slider.querySelector(".slider-dots");

  if (slides.length === 0) return;

  let current = 0;
  let timer = null;
  const AUTO_MS = 4000;

  function showSlide(i) {
    current = (i + slides.length) % slides.length;

    slides.forEach((slide, idx) => {
      slide.classList.toggle("is-active", idx === current);
    });

    if (dotsWrap) {
      const dots = dotsWrap.querySelectorAll("button");
      dots.forEach((dot, idx) => {
        dot.classList.toggle("is-active", idx === current);
        dot.setAttribute("aria-current", idx === current ? "true" : "false");
      });
    }
  }

  function next() { showSlide(current + 1); }
  function prev() { showSlide(current - 1); }

  function startAuto() {
    if (slides.length <= 1) return;
    stopAuto();
    timer = setInterval(next, AUTO_MS);
  }

  function stopAuto() {
    if (timer) clearInterval(timer);
    timer = null;
  }

 
  if (dotsWrap) {
    dotsWrap.innerHTML = "";
    slides.forEach((_, idx) => {
      const dot = document.createElement("button");
      dot.type = "button";
      dot.className = "slider-dot";
      dot.setAttribute("aria-label", `Go to slide ${idx + 1}`);
      dot.addEventListener("click", () => {
        showSlide(idx);
        startAuto();
      });
      dotsWrap.appendChild(dot);
    });
  }


  if (nextBtn) nextBtn.addEventListener("click", () => { next(); startAuto(); });
  if (prevBtn) prevBtn.addEventListener("click", () => { prev(); startAuto(); });

 
  slider.addEventListener("mouseenter", stopAuto);
  slider.addEventListener("mouseleave", startAuto);


  slider.addEventListener("keydown", (e) => {
    if (e.key === "ArrowRight") { next(); startAuto(); }
    if (e.key === "ArrowLeft") { prev(); startAuto(); }
  });


  showSlide(0);
  startAuto();
});
