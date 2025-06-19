const bidangData = {
    pismp: [
        "PISMP Bahasa Melayu",
        "PISMP Bahasa Inggeris",
        "PISMP Matematik",
        "PISMP Sains",
        "PISMP Pendidikan Islam",
        "PISMP Sejarah",
        "PISMP Reka Bentuk & Teknologi",
        "PISMP Pendidikan Jasmani",
        "PISMP Pendidikan Muzik",
        "PISMP Prasekolah",
        "PISMP Pendidikan Khas",
        "Lain-lain"
    ],
    bed: [
        "B.Ed Bahasa Melayu",
        "B.Ed Bahasa Inggeris (TESL)",
        "B.Ed Pendidikan Islam",
        "B.Ed Sejarah",
        "B.Ed Sains / Biologi / Kimia / Fizik",
        "B.Ed Matematik",
        "B.Ed Pendidikan Seni Visual",
        "B.Ed Pendidikan Jasmani",
        "B.Ed Pendidikan Awal Kanak-kanak",
        "B.Ed Pendidikan Khas",
        "Lain-lain"
    ],
    dpli: [
        "Sains + Diploma Pendidikan",
        "Matematik + Diploma Pendidikan",
        "Kejuruteraan + DPLI (Teknik & Vokasional)",
        "Sastera / Sejarah + Diploma Pendidikan",
        "Ekonomi / Perniagaan + Diploma Pendidikan",
        "Lain-lain"
    ],
    luar: [
        "TESL / TEFL / CELTA",
        "Ijazah Psikologi Pendidikan",
        "Ijazah Pendidikan Awal Kanak-kanak",
        "Ijazah Teknologi Pendidikan",
        "Ijazah Bahasa Asing (Mandarin, Arab, Jepun)",
        "Lain-lain"
    ],
    tvet: [
        "TVET (Teknikal)",
        "Kejuruteraan Elektrik + Pendidikan",
        "Kejuruteraan Mekanikal + Pendidikan",
        "Lain-lain"
    ],
    lain: [] // handled separately
};

function tunjukBidang() {
    const laluanSelect = document.getElementById('laluanPendidikan');
    const selectedValue = laluanSelect.value;

    const laluanLainInput = document.getElementById('laluanLainInput');
    const bidangContainer = document.getElementById('bidangContainer');
    const bidangLainInput = document.getElementById('bidangLainInput');
    const bidangSelect = document.getElementById('bidangPengajian');

    if (selectedValue === 'lain') {
        // User selected "Lain-lain" for education path
        laluanLainInput.style.display = 'block';
        bidangLainInput.style.display = 'block';
        bidangContainer.style.display = 'none';
    } else {
        laluanLainInput.style.display = 'none';
        bidangContainer.style.display = 'block';

        // Populate bidang dropdown dynamically
        const bidangOptions = bidangData[selectedValue] || [];
        bidangSelect.innerHTML = ""; // clear options

        if (bidangOptions.length > 0) {
            bidangOptions.forEach(opt => {
                const optionElem = document.createElement('option');
                optionElem.value = opt;
                optionElem.textContent = opt;
                bidangSelect.appendChild(optionElem);
            });

            // Show/hide custom bidang input if "Lain-lain" is pre-selected
            semakLain();
        }

        // If no options found, hide bidangContainer
        if (bidangOptions.length === 0) {
            bidangContainer.style.display = 'none';
        }

        bidangLainInput.style.display = 'none'; // hide custom bidang unless user selects "Lain-lain"
    }
}

function semakLain() {
    const selected = document.getElementById("bidangPengajian").value;
    const bidangLainInput = document.getElementById("bidangLainInput");

    if (selected.toLowerCase().includes("lain")) {
        bidangLainInput.style.display = "block";
    } else {
        bidangLainInput.style.display = "none";
    }
}
