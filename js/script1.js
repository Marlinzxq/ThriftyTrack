let currentIndex = 0;
const slides = document.querySelectorAll('.carousel-item'); // Cambiar a '.carousel-item'
const totalSlides = slides.length;
const indicators = document.querySelectorAll('.carousel-indicators button'); // Cambiar a 'button'

function showNextSlide() {
    currentIndex = (currentIndex + 1) % totalSlides;
    updateSlide();
}

function setSlide(index) {
    currentIndex = index;
    updateSlide();
}

function updateSlide() {
    slides.forEach((slide, index) => {
        slide.classList.remove('active');
        if (index === currentIndex) {
            slide.classList.add('active');
        }
    });
    
    indicators.forEach((indicator, i) => {
        indicator.classList.toggle('active', i === currentIndex);
    });
}

// Cambiar imagen cada 3 segundos
setInterval(showNextSlide, 3000);

// Inicializar la primera diapositiva
updateSlide();
