
        //    🟦 Tiny JS for toggle & icon morph --------------------------- 
  const btn   = document.getElementById('menu-toggle');
  const panel = document.getElementById('mobile-menu');

  btn.addEventListener('click', () => {
    // Slide panel
    panel.classList.toggle('translate-y-full');

    // Morph icon
    btn.classList.toggle('open');
  });


//  <!-- ─── Count‑up script (only needs to run once) ────────────────── -->
  document.addEventListener('DOMContentLoaded', () => {
    const counters = document.querySelectorAll('.counter');
    const observer = new IntersectionObserver(
      (entries, obs) => {
        entries.forEach(entry => {
          if (!entry.isIntersecting) return;

          const el = entry.target;
          const target = +el.dataset.target;      // number to reach
          const suffix = el.dataset.suffix || ''; // e.g. '+', 'k'
          const duration = 1500;                  // ms
          const frameRate = 60;                   // frames per sec
          const totalFrames = Math.round((duration / 1000) * frameRate);
          let frame = 0;

          const counterAnim = () => {
            frame++;
            const progress = frame / totalFrames;
            const current = Math.round(target * progress);
            el.textContent = current + suffix;

            if (frame < totalFrames) {
              requestAnimationFrame(counterAnim);
            } else {
              el.textContent = target + suffix;   // ensure it ends cleanly
            }
          };

          requestAnimationFrame(counterAnim);
          obs.unobserve(el); // animate only once
        });
      },
      { threshold: 0.4 } // fire when 40 % visible
    );

    counters.forEach(c => observer.observe(c));
  });


//   product card animation JS

// <!-- ── Optional JS: trigger entrance only when in viewport ─────── -->

  document.addEventListener("DOMContentLoaded", () => {
    const cards = document.querySelectorAll(".animate-card");
    const io = new IntersectionObserver(
      (entries, obs) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.remove("opacity-0"); // let the animation play
            obs.unobserve(entry.target);                // only once
          }
        });
      },
      { threshold: 0.3 }
    );
    cards.forEach((card) => io.observe(card));
  });


  const hamBurgerButtom = document.getElementsByTagName("Button")

const hamburgerButton = document.getElementsByTagName("Button"); // adjust the ID if needed

if (hamburgerButton) {
  hamburgerButton.addEventListener('click', () => {
    console.log('clicked');
  });
}

  



  


