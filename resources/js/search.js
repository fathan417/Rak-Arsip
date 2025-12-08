document.addEventListener('DOMContentLoaded', () => {

  // ========================================================================
  // STATE & REFERENSI ELEMEN
  // ========================================================================

  // Menandai apakah tombol Enter sudah pernah ditekan minimal sekali
  let hasPressedEnterOnce = false;

  const searchInput     = document.querySelector('input[type="search"]');
  const filterForm      = document.getElementById('filter-form');
  const suggestionBox   = document.getElementById('suggestions');
  const searchSection   = document.getElementById('search-section');
  const searchBar       = document.getElementById('searchbar');
  const title           = document.getElementById('page-title');

  // Membuat kontainer hasil pencarian yang ditempatkan setelah elemen .form-control
  const resultContainer = document.createElement('div');
  resultContainer.id    = 'search-results';
  document.querySelector('.form-control')
    .insertAdjacentElement('afterend', resultContainer);


  // ========================================================================
  // FUNGSI PEMBENTUK PARAMETER & QUERY
  // ========================================================================

  // Mengambil parameter pencarian yang sedang aktif
  function getCurrentParams() {
    const params = {};
    const q = searchInput.value.trim();

    if (q.length > 0) params.q = q;

    // Mengambil semua kategori checklist yang dicentang
    const kategori = [...filterForm.querySelectorAll('input[name="kategori[]"]:checked')]
      .map(cb => cb.value);

    if (kategori.length > 0) params.kategori = kategori;

    return params;
  }

  // Mengonversi parameter menjadi query string URLSearchParams
  function paramsToQuery(params) {
    const usp = new URLSearchParams();

    Object.keys(params).forEach(key => {
      const value = params[key];

      if (Array.isArray(value)) {
        value.forEach(v => usp.append(key + '[]', v));
      } else {
        usp.set(key, value);
      }
    });

    return usp.toString();
  }


  // ========================================================================
  // FUNGSI FETCH HASIL PENCARIAN
  // ========================================================================

  async function fetchResults(params, pageUrl = null) {
    const queryString = paramsToQuery(params);
    const url = pageUrl || `/search?${queryString}`;

    const response = await fetch(url);
    const data = await response.json();

    // Render hasil + pagination
    resultContainer.innerHTML = data.data + data.pagination;

    // Event listener untuk tombol pagination
    resultContainer.querySelectorAll('.join-item.btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const nextPage = btn.getAttribute('data-page');
        fetchResults(params, nextPage);
      });
    });
  }


  // ========================================================================
  // FUNGSI UI & INTERAKSI TAMPILAN
  // ========================================================================

  // Menyembunyikan suggestion box
  function hideSuggestions() {
    suggestionBox.classList.add('hidden');
  }

  // Reset pencarian ke seluruh data
  function resetSearch() {
    hideSuggestions();
    fetchResults({});
  }

  // Mengubah layout setelah pencarian aktif
  function expandSearchLayout() {
    searchSection.classList.remove('items-center');
    searchSection.classList.add('items-start', 'justify-start');

    resultContainer.classList.add('w-full', 'flex', 'flex-col', 'item-center');

    searchBar.classList.remove('w-full');
    searchBar.classList.add('w-2xl');

    title.classList.add('hidden');
  }

  // Memberikan event click pada setiap item suggestion
  function attachSuggestionEvents() {
    suggestionBox.querySelectorAll('[data-value]').forEach(el => {
      el.addEventListener('click', () => {
        searchInput.value = el.dataset.value;
        hideSuggestions();
        expandSearchLayout();
        fetchResults(getCurrentParams());
        searchInput.focus();
      });
    });
  }


  // ========================================================================
  // HANDLER INPUT SUGGESTION (DEBOUNCE + ABORT REQUEST)
  // ========================================================================

  let debounceTimer;
  let controller = null;

  searchInput.addEventListener('input', () => {
    const query = searchInput.value.trim();
    clearTimeout(debounceTimer);

    // Jika input kosong
    if (query.length === 0) {

      // Jika Enter belum pernah ditekan, sembunyikan suggestion
      if (!hasPressedEnterOnce) {
        hideSuggestions();
        return;
      }

      // Kalau Enter sudah ditekan, tampilkan semua hasil
      resetSearch();
      return;
    }

    // Debounce biar tidak spam request
    debounceTimer = setTimeout(() => {

      // Membatalkan request sebelumnya
      if (controller) controller.abort();
      controller = new AbortController();

      fetch(`/suggest?q=${encodeURIComponent(query)}`, {
        signal: controller.signal
      })
        .then(res => res.json())
        .then(data => {

          // Jika tidak ada data
          if (!Array.isArray(data) || data.length === 0) {
            suggestionBox.innerHTML = '';
            hideSuggestions();
            return;
          }

          // Render daftar suggestion
          suggestionBox.innerHTML = data
            .map(item => `
              <div class="p-3 hover:bg-zinc-200 cursor-pointer border-b border-gray-300"
                   data-value="${item.judul}">
                <span class="font-semibold">${item.judul}</span>
                <br>
                <span class="text-sm text-gray-700">${item.pengarang}</span>
              </div>
            `)
            .join('');

          suggestionBox.classList.remove('hidden');
          attachSuggestionEvents();
        })
        .catch(err => {
          if (err.name === 'AbortError') return;
        });

    }, 50);
  });


  // ========================================================================
  // HANDLER KEY ENTER
  // ========================================================================

  searchInput.addEventListener('keypress', e => {
    if (e.key === 'Enter') {
      e.preventDefault();

      hideSuggestions();
      expandSearchLayout();
      fetchResults(getCurrentParams());

      // Menandai bahwa Enter pertama sudah pernah ditekan
      if (!hasPressedEnterOnce) {
        hasPressedEnterOnce = true;
      }
    }
  });


  // ========================================================================
  // HANDLER FORM FILTER
  // ========================================================================

  filterForm.addEventListener('submit', e => {
    e.preventDefault();

    hideSuggestions();
    expandSearchLayout();
    fetchResults(getCurrentParams());

    if (!hasPressedEnterOnce) {
      hasPressedEnterOnce = true;
    }
  });


  // ========================================================================
  // HIDE SUGGESTION KETIKA KLIK DI LUAR AREA
  // ========================================================================

  document.addEventListener('click', e => {
    const clickedInsideBox = suggestionBox.contains(e.target);
    const clickedInsideSearch = searchBar.contains(e.target);

    if (!clickedInsideBox && !clickedInsideSearch) {
      hideSuggestions();
    }
  });

});