@foreach ($arsip as $item)
<div class="card card-side bg-zinc-100 shadow-md w-full text-zinc-900">
  <figure class="p-3">
    <img src="{{ $item->thumbnail_path ? url('storage-direct/'.$item->thumbnail_path) : asset('images/empty-thumbnail-2.svg') }}"
         alt="Thumb" class="rounded-xl w-28 h-36 bg-black/50 bg-blend-darken {{ $item->thumbnail_path ? 'object-cover' : 'object-contain p-2' }}" />
  </figure>

  <div class="card-body">
    <h2 class="card-title text-base text-start">{{ $item->judul }}</h2>
    <p class="-mt-1 text-sm text-start">{{ $item->pengarang }}</p>
    <div class="badge badge-secondary">{{ $item->kategori }}</div>

    <div class="card-actions justify-end mt-2">
        <button class="btn btn-warning btn-sm" onclick="editArchive{{ $item->id }}.showModal()"><svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/></svg></button>

        <button type="button" class="btn btn-error btn-sm" onclick="document.getElementById('confirmDelete{{ $item->id }}').showModal()"><svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"></path></svg></button>

    </div>
  </div>
</div>

<!-- Modal Edit Archive -->
    <dialog id="editArchive{{ $item->id }}" class="modal">
        <div class="modal-box max-w-full h-11/12 grid place-items-center backdrop-blur-sm bg-zinc-950/50 rounded-2xl shadow-xl border-gray-200/70 border">
    
          <!-- Tombol Close -->
          <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
          </form>
    
          <!-- Form Edit -->
          <form action="{{ route('arsip.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
    
            <div class="grid grid-cols-4 grid-rows-4 gap-4">
    
              <!-- Judul Arsip -->
              <div class="col-span-2">
                <fieldset class="fieldset bg-zinc-100 w-full h-[100px] flex justify-center items-center drop-shadow-[4px_7px_0_rgba(0,0,0,1)] rounded-box border px-5">
                  <legend class="fieldset-legend badge badge-primary">Judul Arsip</legend>
    
                  <input type="text"
                    name="judul"
                    class="input text-base-200 border-base-300 rounded-2xl bg-transparent border-2 w-full"
                    placeholder="Tulis judul"
                    value="{{ $item->judul }}"
                    required />
                </fieldset>
              </div>
    
              <!-- Thumbnail Arsip -->
              <div class="row-span-3 col-start-4 row-start-1">
                <fieldset class="fieldset bg-zinc-100 h-full flex justify-center items-end drop-shadow-[4px_7px_0_rgba(0,0,0,1)] rounded-box border px-5 pb-5 bg-cover bg-center"
                  style="background-image: url('{{ asset('storage/'.$item->thumbnail_path) }}'); background-color: rgba(0,0,0,0.6); background-blend-mode: darken;">
    
                  <legend class="fieldset-legend badge badge-primary">Thumbnail Arsip</legend>
    
                  <input type="file"
                    name="thumbnail"
                    class="file-input file-input-primary text-gray-900 border-base-300 rounded-2xl bg-blue-50 w-full"
                    accept=".jpg,.jpeg,.png,.webp,.svg" />
                </fieldset>
              </div>
    
              <!-- Abstrak -->
              <div class="col-span-2 row-span-2 col-start-1 row-start-2">
                <fieldset class="fieldset grid-pattern bg-lime-300 h-full flex justify-center items-center drop-shadow-[4px_7px_0_rgba(0,0,0,1)] rounded-box border px-5">
                  <legend class="fieldset-legend badge badge-primary">Abstrak</legend>
    
                  <textarea name="abstrak"
                    class="textarea text-base-200 h-35 border-base-300 rounded-2xl bg-zinc-100 border w-9/12 resize-none drop-shadow-[2px_5px_0_rgba(0,0,0,1)]"
                    required>{{ $item->abstrak }}</textarea>
                </fieldset>
              </div>
    
              <!-- Rak & Baris -->
              <div class="row-span-2 col-start-3 row-start-1">
                <fieldset class="fieldset bg-zinc-100 h-full flex flex-col justify-around items-center drop-shadow-[4px_7px_0_rgba(0,0,0,1)] rounded-box border px-5">
                  <legend class="fieldset-legend badge badge-primary">Rak & Baris</legend>
    
                  <select name="lokasi_rak"
                    class="select select-primary border-2 bg-transparent rounded-2xl text-gray-500"
                    required>
                    <option disabled>Pilih Rak</option>
                    <option value="A" {{ $item->lokasi_rak == 'A' ? 'selected' : '' }}>A</option>
                    <option value="B" {{ $item->lokasi_rak == 'B' ? 'selected' : '' }}>B</option>
                    <option value="C" {{ $item->lokasi_rak == 'C' ? 'selected' : '' }}>C</option>
                  </select>
    
                  <select name="lokasi_baris"
                    class="select select-error border-2 bg-transparent rounded-2xl text-gray-500"
                    required>
                    <option disabled>Pilih Baris</option>
                    <option value="1" {{ $item->lokasi_baris == '1' ? 'selected' : '' }}>1</option>
                    <option value="2" {{ $item->lokasi_baris == '2' ? 'selected' : '' }}>2</option>
                    <option value="3" {{ $item->lokasi_baris == '3' ? 'selected' : '' }}>3</option>
                  </select>
                </fieldset>
              </div>
    
              <!-- Kategori Arsip -->
              <div class="col-start-3 row-start-3">
                <fieldset class="fieldset bg-zinc-900 h-full flex justify-center items-center drop-shadow-[4px_7px_0_rgba(0,0,0,1)] rounded-box border px-5">
                  <legend class="fieldset-legend badge badge-primary">Kategori</legend>
    
                  <select name="kategori" class="select select-primary border-2 bg-zinc-900 rounded-2xl text-zinc-300" required>
                    <option disabled>Pilih Kategori</option>
                    <option value="Teknologi" {{ $item->kategori == 'Teknologi' ? 'selected' : '' }}>Teknologi</option>
                    <option value="Sains" {{ $item->kategori == 'Sains' ? 'selected' : '' }}>Sains</option>
                    <option value="Sejarah" {{ $item->kategori == 'Sejarah' ? 'selected' : '' }}>Sejarah</option>
                    <option value="Hukum & Politik" {{ $item->kategori == 'Hukum & Politik' ? 'selected' : '' }}>Hukum & Politik</option>
                    <option value="Kesehatan" {{ $item->kategori == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                    <option value="Komputer & Informatika" {{ $item->kategori == 'Komputer & Informatika' ? 'selected' : '' }}>Komputer & Informatika</option>
                  </select>
                </fieldset>
              </div>
    
              <!-- Input Dokumen -->
              <div class="row-start-4">
                <fieldset class="fieldset bg-zinc-900 h-10/12 flex justify-center items-center drop-shadow-[4px_7px_0_rgba(0,0,0,1)] rounded-box border px-5">
                  <legend class="fieldset-legend badge badge-primary">Input Dokumen</legend>
    
                  <input type="file"
                    name="file_dokumen"
                    class="file-input file-input-primary bg-zinc-900"
                    accept=".pdf,.docx,.txt" />
                </fieldset>
              </div>
    
              <!-- Nama Pengarang -->
              <div class="col-span-2 row-start-4">
                <fieldset class="fieldset bg-zinc-100 h-10/12 flex justify-center items-center drop-shadow-[4px_7px_0_rgba(0,0,0,1)] rounded-box border px-5">
                  <legend class="fieldset-legend badge badge-primary">Nama Pengarang</legend>
    
                  <input type="text"
                    name="pengarang"
                    class="input text-base-200 border-base-300 rounded-2xl bg-transparent border-2 w-full"
                    value="{{ $item->pengarang }}"
                    required />
                </fieldset>
              </div>
    
              <!-- Tombol Submit -->
              <div class="col-start-4 row-start-4">
                <button type="submit"
                  class="btn btn-secondary mt-3 w-full h-18 text-lg drop-shadow-[4px_7px_0_rgba(0,0,0,1)]">
                  Update Arsip
                </button>
              </div>
    
            </div>
          </form>
    
        </div>
    </dialog>

<!-- Modal Delete Archive -->
    <dialog id="confirmDelete{{ $item->id }}" class="modal">
      <div class="modal-box bg-zinc-100 text-zinc-900">
        <h3 class="font-bold text-lg">Yakin mau hapus arsip ini?</h3>
        <p class="py-2">Aksi ini tidak bisa dibatalkan.</p>

        <div class="modal-action">
          <form method="dialog">
            <button class="btn btn-primary">Batal</button>
          </form>

          <form action="{{ route('arsip.destroy', $item->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button class="btn btn-error">Hapus</button>
          </form>
        </div>
      </div>
    </dialog>
@endforeach
