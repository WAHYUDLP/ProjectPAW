
//Slider start
let index = 0;
const slides = document.querySelector(".slides");
const slideItems = document.querySelectorAll(".slide");
const dots = document.querySelectorAll(".dot");

function moveSlide(n) {
    index = n;
    slides.style.transition = "transform 0.8s ease-in-out"; // Efek geser halus
    slides.style.transform = `translateX(${-100 * index}%)`;
    updateDots();
}

function updateDots() {
    dots.forEach(dot => dot.classList.remove("active"));
    dots[index].classList.add("active");
}

function autoSlide() {
    index = (index + 1) % slideItems.length; // Loop kembali ke slide pertama
    moveSlide(index);
}

setInterval(autoSlide, 4000); // Geser setiap 4 detik
let currentIndex = 0;
function moveSlide(index) {
    const slides = document.querySelector('.slides');
    const lines = document.querySelectorAll('.line');
    slides.style.transform = `translateX(-${index * 100}%)`;
    lines.forEach((line, i) => {
        line.classList.toggle('active', i === index);
    });
    currentIndex = index;
}

// Slider end


//Eye
document.getElementById('toggle-password').addEventListener('click', function() {
    const passwordField = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');
    
    // Toggle the input type between 'password' and 'text'
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
});
