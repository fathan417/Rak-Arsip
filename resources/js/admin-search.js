document.addEventListener("DOMContentLoaded", () => {

    // Referensi elemen input pencarian dan kontainer hasil
    const input = document.querySelector('#admin-searchbar input[type="search"]');
    const container = document.querySelector('.admin-archive-container');

    let timer; // digunakan untuk debounce

    // ========================================================================
    // PENCARIAN ADMIN
    // ========================================================================
    input.addEventListener('input', function () {
        clearTimeout(timer);

        timer = setTimeout(() => {

            // Ambil nilai pencarian, hapus spasi berlebih
            let query = this.value.trim();

            // Tentukan URL: kosong maka ambil semua, tidak kosong maka cari
            let url = query === "" 
                ? `/dashboard/admin/search`
                : `/dashboard/admin/search?q=${encodeURIComponent(query)}`;

            // Fetch HTML hasil pencarian
            fetch(url)
                .then(res => res.text())
                .then(html => container.innerHTML = html)
                .catch(err => console.error(err));

        }, 200);
    });
});
