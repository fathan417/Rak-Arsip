import './bootstrap';
import './search';
import './admin-search';
import './page';

// ========================================================================
// FADE OUT ALERT SUCCESS OTOMATIS
// ========================================================================

document.addEventListener('DOMContentLoaded', function () {
  const alert = document.querySelector('.alert.alert-success');
  if (!alert) return;

  // Mulai fade-out setelah 3 detik
  setTimeout(() => {
    alert.style.transition = 'opacity 400ms';
    alert.style.opacity = '0';

    // Hapus elemen setelah animasi selesai
    setTimeout(() => alert.remove(), 400);
  }, 3000);
});


// ========================================================================
// PREVIEW THUMBNAIL OTOMATIS SAAT FILE DIUPLOAD
// ========================================================================

document.getElementById('thumbnailInput').addEventListener('change', function(event) {

  const file = event.target.files[0];
  const previewBox = document.getElementById('thumbnailPreview');

  // Kelas default sebelum ada gambar
  const defaultClasses = ['bg-size-[90%_60%]', 'bg-position-[center_top_3rem]'];

  // Kelas setelah gambar berhasil diupload
  const uploadedClasses = ['bg-cover', 'bg-center'];

  // Jika ada file yang dipilih
  if (file) {
    const reader = new FileReader();

    reader.onload = function(e) {

      // Mengubah background jadi gambar yang dipilih user
      previewBox.style.backgroundImage = `url('${e.target.result}')`;

      // Hapus kelas default
      defaultClasses.forEach(c => previewBox.classList.remove(c));

      // Tambahkan kelas tampilan setelah upload
      uploadedClasses.forEach(c => previewBox.classList.add(c));
    };

    reader.readAsDataURL(file);

  } else {

    // Jika input dikosongkan, kembalikan ke thumbnail default
    previewBox.style.backgroundImage = `url('{{ asset('images/empty-thumbnail.svg') }}')`;

    uploadedClasses.forEach(c => previewBox.classList.remove(c));
    defaultClasses.forEach(c => previewBox.classList.add(c));
  }
});
