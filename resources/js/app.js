import './bootstrap';

// Import Alpine JS jika Anda menggunakannya (sering ada di Breeze)
// import Alpine from 'alpinejs';
// window.Alpine = Alpine;
// Alpine.start();

/**
 * Fungsi untuk memperbarui teks nama file pada input file kustom
 */
function updateFileName(inputElement, textElementId) {
    const fileChosenText = document.getElementById(textElementId);
    if (!fileChosenText || !inputElement) return;

    if (inputElement.files && inputElement.files.length > 0) {
        fileChosenText.textContent = inputElement.files[0].name;
    } else {
        fileChosenText.textContent = 'Tidak ada file yang dipilih';
    }
}

/**
 * Fungsi untuk menampilkan loading state pada tombol submit
 */
function showLoadingButton(buttonElement, loadingText = 'Memproses...') {
     if (!buttonElement) return;
     buttonElement.disabled = true;
     // Simpan teks asli jika belum ada
     if (!buttonElement.dataset.originalText) {
         buttonElement.dataset.originalText = buttonElement.innerHTML;
     }
     buttonElement.innerHTML = `
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        ${loadingText}
     `; // Anda mungkin perlu menambahkan CSS untuk .spinner-border jika tidak pakai Bootstrap
}

/**
 * Fungsi untuk mengembalikan tombol submit ke state normal
 */
function hideLoadingButton(buttonElement) {
     if (!buttonElement) return;
     buttonElement.disabled = false;
     if (buttonElement.dataset.originalText) {
        buttonElement.innerHTML = buttonElement.dataset.originalText;
     }
}


// --- Event Listener untuk Halaman Konverter ---
document.addEventListener('DOMContentLoaded', () => {

    // File Input Handler
    const fileInput = document.getElementById('file');
    if (fileInput) {
        fileInput.addEventListener('change', () => {
            updateFileName(fileInput, 'file-chosen-text');
        });
    }

    // Form Submission Handler (Menampilkan Loading)
    const converterForm = document.getElementById('converter-form');
    const convertButton = document.getElementById('convert-button'); // Pastikan tombol punya id ini

    if (converterForm && convertButton) {
        converterForm.addEventListener('submit', (event) => {
            // Validasi sederhana sisi klien (contoh: pastikan format dipilih)
            const outputFormat = document.getElementById('output_format');
            if (outputFormat && outputFormat.value === "") {
                 alert('Silakan pilih format output terlebih dahulu.');
                 event.preventDefault(); // Hentikan submit jika tidak valid
                 return;
            }
            // Cek file dipilih (meskipun required di HTML lebih baik)
            if (fileInput && fileInput.files.length === 0) {
                 alert('Silakan pilih file yang akan dikonversi.');
                 event.preventDefault();
                 return;
            }

            // Jika valid, tampilkan loading
            showLoadingButton(convertButton, 'Mengonversi...');

            // Biarkan form submit secara normal
            // Jika pakai AJAX, Anda akan `event.preventDefault()` di sini
            // dan mengirim data pakai `axios` atau `fetch`.
        });
    }


    // --- Event Listener untuk Halaman Admin ---

    // Konfirmasi Hapus Riwayat
    const deleteForms = document.querySelectorAll('form.delete-history-form'); // Form hapus harus punya class ini
    deleteForms.forEach(form => {
        form.addEventListener('submit', function (event) {
            const confirmation = confirm('Apakah Anda yakin ingin menghapus riwayat ini? Tindakan ini tidak dapat diurungkan.');
            if (!confirmation) {
                event.preventDefault(); // Batalkan submit jika user klik "Cancel"
            } else {
                // Opsional: Tampilkan loading pada tombol delete
                const deleteButton = form.querySelector('button[type="submit"]');
                if(deleteButton) {
                    deleteButton.disabled = true;
                    deleteButton.textContent = 'Menghapus...';
                }
            }
        });
    });

    // File Input Handler untuk Logo di Halaman Settings Admin
    const logoInput = document.getElementById('app_logo'); // Input file logo harus punya id ini
    if (logoInput) {
        const logoChosenText = document.getElementById('logo-chosen-text'); // Tambahkan elemen span ini di view setting
        if (logoChosenText) {
             logoInput.addEventListener('change', () => {
                if (logoInput.files && logoInput.files.length > 0) {
                    logoChosenText.textContent = `File dipilih: ${logoInput.files[0].name}`;
                } else {
                    logoChosenText.textContent = ''; // Kosongkan jika tidak ada file
                }
             });
        }
    }

}); // Akhir DOMContentLoaded

// expose fungsi ke global scope jika dipanggil dari inline HTML (kurang ideal)
// window.updateFileName = updateFileName;

