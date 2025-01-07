const carouselContainers = [...document.querySelectorAll('.carousel-container')];
const nxtBtn = [...document.querySelectorAll('.nxt-btn')];
const preBtn = [...document.querySelectorAll('.pre-btn')];

carouselContainers.forEach((item, i) => {
    let containerDimensions = item.getBoundingClientRect();
    let containerWidth = containerDimensions.width;

    nxtBtn[i].addEventListener('click', () => {
        item.scrollLeft += containerWidth;
    })

    preBtn[i].addEventListener('click', () => {
        item.scrollLeft -= containerWidth;
    })
})

// Admin-panel //
document.getElementById("eventLink").addEventListener("click", function () {
    document.getElementById("eventContent").style.display = "block";
    document.getElementById("pemilihForm").style.display = "none";
});

document.getElementById("pemilihLink").addEventListener("click", function () {
    document.getElementById("pemilihForm").style.display = "block";
    document.getElementById("eventContent").style.display = "none";
});

document.getElementById("createEvent").addEventListener("click", function() {
    const eventForm = document.getElementById("eventForm");
    const eventContent = document.getElementById("eventContent");

    if (eventContent) {
        eventContent.style.display = "none";
    }

    if (eventForm.style.display === "none" || eventForm.style.display === "") {
        eventForm.style.display = "block";
    } else {
        eventForm.style.display = "none";
    }
});

function updateProdi() {
    const jurusanSelect = document.getElementById("jurusan");
    const prodiSelect = document.getElementById("prodi");

    // Clear existing options in Prodi
    prodiSelect.innerHTML = '<option value="" disabled selected>Pilih prodi</option>';

    // Define Prodi options based on selected Jurusan
    const prodiOptions = {
        informatika: ["TRM", "TRPL", "Animasi", "Geomatika", "Game", "Informatika"],
        elektro: ["Robotika", "Instrumen"]
    };

    // Get selected Jurusan
    const selectedJurusan = jurusanSelect.value;

    // Add Prodi options based on selected Jurusan
    if (prodiOptions[selectedJurusan]) {
        prodiOptions[selectedJurusan].forEach(prodi => {
            const option = document.createElement("option");
            option.value = prodi.toLowerCase();
            option.textContent = prodi;
            prodiSelect.appendChild(option);
        });
    }
}

document.getElementById("uploadCandidateBtn").addEventListener("click", function() {
    console.log("Tombol di-klik");
    document.getElementById("popup").style.display = "flex";
});

document.getElementById("closePopup").addEventListener("click", function() {
    console.log("Close di-klik");
    document.getElementById("popup").style.display = "none";
});

//Laman Vote
// Ambil elemen
const btnPilih = document.getElementById('btnPilih');
const overlay = document.getElementById('popup');
const btnBatal = document.getElementById('btnBatal');
const btnYa = document.getElementById('btnYa');

// Tampilkan overlay saat tombol Pilih diklik
btnPilih.addEventListener('click', () => {
    overlay.style.display = 'flex';
});

// Sembunyikan overlay saat tombol Batal diklik
btnBatal.addEventListener('click', () => {
    overlay.style.display = 'none';
});

// Contoh aksi untuk tombol Ya
btnYa.addEventListener('click', () => {
    alert('Anda telah memilih ini!');
    overlay.style.display = 'none';
});
  // Ambil semua tombol PILIH
  const pilihButtons = document.querySelectorAll('.lecturer-button');
  let selectedLecturer = null;

  // Ketika tombol PILIH diklik
  pilihButtons.forEach((button, index) => {
    button.addEventListener('click', () => {
      selectedLecturer = button.closest('.lecturer').querySelector('.lecturer-name').innerText;
      document.getElementById('popup').style.display = 'block';
    });
  });

document.getElementById('btnYa').addEventListener('click', () => {
    if (selectedLecturer) {
      // Kirim data ke PHP untuk diproses
      fetch('submit_vote.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'submit-vote.php',
        },
        body: `lecturer=${encodeURIComponent(selectedLecturer)}`
      })
      .then(response => response.text())
      .then(data => {
        alert(data);  // Tampilkan pesan dari PHP
        document.getElementById('popup').style.display = 'none';  // Sembunyikan popup
      })
      .catch(error => {
        alert('Terjadi kesalahan: ' + error);
      });
    }
  });

  // Ketika tombol 'Tidak' pada popup diklik
  document.getElementById('btnBatal').addEventListener('click', () => {
    document.getElementById('popup').style.display = 'none';
  });