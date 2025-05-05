
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


// Eror sukses hilang
setTimeout(() => {
    const message = document.querySelector('.error-message, .success-message');
    if (message) {
        message.style.display = 'none';
    }
}, 3000);

//Konfirmasi logout
function confirmLogout() {
    var confirmLogout = confirm("Apakah Anda yakin ingin keluar?");
    if (confirmLogout) {
        window.location.href = "prosesLogout.php"; // Arahkan ke halaman logout jika konfirmasi
    }
}



// navabr
 // Ambil semua link navbar mobile
 const links = document.querySelectorAll('.mobile-navbar a');

 // Ambil path URL saat ini (misalnya: kalkulator.php)
 const currentPage = window.location.pathname.split("/").pop();

 // Loop setiap link dan cek apakah href-nya cocok
 links.forEach(link => {
     const href = link.getAttribute("href").split("/").pop();

     // Hapus class 'active' dari semua link
     link.classList.remove("active");

     // Kalau href cocok dengan halaman sekarang, tambahkan class 'active'
     if (href === currentPage) {
         link.classList.add("active");
     }
 });

   // Scroll ke hasil jika URL mengandung #hasil-kalori
   window.addEventListener('DOMContentLoaded', () => {
    if (window.location.hash === '#hasil-kalori') {
        const target = document.getElementById('hasil-kalori');
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
    }
});