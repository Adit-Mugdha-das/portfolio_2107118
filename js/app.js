document.addEventListener('DOMContentLoaded', () => {
  // =====================
  // Mobile Navbar Toggle
  // =====================
  const btn = document.getElementById('hamburger');
  const nav = document.getElementById('nav');
  if (btn && nav) btn.addEventListener('click', () => nav.classList.toggle('open'));

  // =====================
  // Scroll-to-Top Button
  // =====================
  const topBtn = document.createElement("button");
  topBtn.innerText = "↑";
  topBtn.id = "topBtn";
  document.body.appendChild(topBtn);

  window.addEventListener("scroll", () => {
    topBtn.style.display = window.scrollY > 300 ? "block" : "none";
  });
  topBtn.addEventListener("click", () => window.scrollTo({top:0, behavior:"smooth"}));

  // =====================
  // Typing Animation (Hero tagline)
  // =====================
  const tagline = document.getElementById("tagline");
  if(tagline){
    const text = tagline.dataset.text || tagline.innerText;
    tagline.innerText = "";
    let i = 0;
    function typeEffect(){
      if(i < text.length){
        tagline.innerText += text.charAt(i);
        i++;
        setTimeout(typeEffect, 80);
      }
    }
    typeEffect();
  }

  // =====================
  // Reveal on Scroll
  // =====================
  // =====================
// Reveal on Scroll
// =====================
const revealElements = document.querySelectorAll(".reveal");
function checkReveal() {
  revealElements.forEach((el, index) => {
    const rect = el.getBoundingClientRect();
    if (rect.top < window.innerHeight - 100) {
      // stagger delay: index * 0.2s
      el.style.transitionDelay = (index * 0.2) + "s";
      el.classList.add("visible");
    }
  });
}
window.addEventListener("scroll", checkReveal);
checkReveal(); // run on load
 // run on load too
});
