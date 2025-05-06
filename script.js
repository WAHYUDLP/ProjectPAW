
//Slider start
let currentSlide = 0;

function moveSlide(index) {
    currentSlide = index;
    const slideWidth = $('.slide').outerWidth();
    $('.slides').css('transform', 'translateX(' + (-slideWidth * index) + 'px)');

    $('.line').removeClass('active');
    $('.line').eq(index).addClass('active');
}

$(document).ready(function () {
    let slideCount = $('.slide').length;

    setInterval(function () {
        currentSlide = (currentSlide + 1) % slideCount;
        moveSlide(currentSlide);
    }, 5000); // 5 detik
});
// Slider end

// Eror sukses hilang
setTimeout(() => {
    const message = document.querySelector('.error-message, .success-message');
    if (message) {
        console.log(message); // Untuk debugging
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



