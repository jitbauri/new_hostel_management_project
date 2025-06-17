  <script>
    const slides = document.querySelectorAll('.slide');
let index = 0;

function showSlide(i) {
  slides.forEach((slide, idx) => {
    slide.classList.remove('active');
    if (idx === i) slide.classList.add('active');
  });
}

function startSlider() {
  showSlide(index);
  index = (index + 1) % slides.length;
}

setInterval(startSlider, 3000); // Slide every 3 seconds

  </script>