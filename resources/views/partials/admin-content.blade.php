@extends('dashboard')
@section('content')
<div id="adminView">
  <div class="grid grid-rows-[45px_1fr] gap-4 text-center static backdrop-blur-sm bg-zinc-950/50 rounded-4xl shadow-xl p-10 w-[98%] h-[95vh] border-gray-200/70 border">
    <!-- Public Mode -->
      <button class="btn absolute top-8 right-8 bg-zinc-50 text-black border-0 rounded-full" onclick="publicMode.showModal()">
          <i class="fa-solid fa-door-open text-lg"></i>
      </button>
  
    <!-- Header -->
      <h1 class="text-4xl font-bold ">Rak Kearsipan | <div class="badge badge-primary text-2xl px-6 py-5 rounded-xl">Admin Mode</div></h1>

    <!-- Main Content -->
      <div class="grid grid-cols-5 grid-rows-5">

        <!-- Profile -->
          <div class="col-span-3 row-span-5 grid place-items-center">
            <div class="avatar">
              <div class="w-48 rounded-full ring-zinc-100 ring-offset-base-100 ring-3 ring-offset-2">
                <img src="https://img.daisyui.com/images/profile/demo/yellingcat@192.webp" />
              </div>
            </div>
            <div class="card-actions items-center -mt-28">
              <h3 class="text-xl font-bold">Administrator</h3>
              <button class="btn btn-primary" onclick="uploadArchive.showModal()">Unggah Arsip</button>
            </div>
          </div>

        <!-- Archive List -->
          <div class="col-span-2 row-span-5 col-start-4 pt-3 h-full grid grid-cols-1 grid-rows-[32px_1fr_1fr_1fr_1fr] gap-4">
            
            <!-- Search -->
              <div>
                <div class="form-control w-11/12">
                  <div class="input-group w-full transition-all duration-500">
                    <label id="admin-searchbar" class="input w-full h-8 rounded-full bg-zinc-50 text-black shadow-lg">
                      <svg class="h-4 opacity-100 text-black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></g></svg>
                      <input type="search" class="input-lg placeholder-black placeholder:text-sm text-sm" placeholder="Cari nama atau pengarang"/>
                    </label>
                  </div>
                </div>
              </div>

            <!-- Container list -->
              <div class="space-y-3 overflow-y-auto relative pr-1 h-96 row-span-4 admin-archive-container">
                @include('partials.sub-partials.admin-archive-list', ['arsip' => $arsip])
              </div>
          </div>
      </div>

    <!-- Alert -->
      @if (session('success'))
        <div role="alert" class="alert alert-success mb-4 absolute bottom-3 left-1/2 -translate-x-1/2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span class="text-md">{{ session('success') }}</span>
        </div>
      @endif
  </div>
</div>

<!-- Modals Dialog -->
  <dialog id="publicMode" class="modal">
    <div class="modal-box bg-zinc-50 text-black text-center w-4xl h-54">
      <i class="fa-solid fa-circle-exclamation text-xl m"></i>
      <h3 class="text-lg font-bold">Ingin keluar dari mode administrator?</h3>
      <div class="mt-10 flex justify-center gap-5">
        <form action="{{ route('dashboard.admin.logout') }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-primary">Ya</button>
        </form>
        <button type="button" class="btn btn-secondary" onclick="adminMode.close()">Tidak</button>
      </div>
    </div>
    <form method="dialog" class="modal-backdrop">
      <button>close</button>
    </form>
  </dialog>

  <dialog id="uploadArchive" class="modal">
    <div class="modal-box max-w-full h-11/12 grid place-items-center backdrop-blur-sm bg-zinc-950/50 rounded-2xl shadow-xl border-gray-200/70 border">
      <form method="dialog">
        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
      </form>

      <form action="{{ route('arsip.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-4 grid-rows-4 gap-4">

        <!-- Judul Arsip -->
          <div class="col-span-2">
            <fieldset class="fieldset bg-zinc-100 w-full h-[100px] flex justify-center items-center drop-shadow-[4px_7px_0_rgba(0,0,0,1)] rounded-box border px-5">
              <legend class="fieldset-legend badge badge-primary">Judul Arsip</legend>
              <input type="text" name="judul" class="input text-base-200 border-base-300 rounded-2xl bg-transparent border-2 w-full" placeholder="Tulis judul" required />
            </fieldset>
          </div>

        <!-- Thumbnail Arsip -->
          <div class="row-span-3 col-start-4 row-start-1">
            <fieldset id="thumbnailPreview" class="fieldset bg-black/40 h-full flex justify-center items-end drop-shadow-[4px_7px_0_rgba(0,0,0,1)] rounded-box border px-5 pb-5 bg-size-[90%_60%] bg-position-[center_top_3rem] bg-no-repeat bg-blend-darken" style="background-image: url('{{ asset('images/empty-thumbnail.svg') }}');">
              <legend class="fieldset-legend badge badge-primary">Thumbnail Arsip</legend>
              <input type="file" id="thumbnailInput" name="thumbnail" class="file-input file-input-primary text-gray-900 border-base-300 rounded-2xl bg-blue-50 w-full"
                accept=".jpg,.jpeg,.png,.webp,.svg" />
            </fieldset>
          </div>

        <!-- Abstrak -->
          <div class="col-span-2 row-span-2 col-start-1 row-start-2">
            <fieldset class="fieldset grid-pattern bg-lime-300 h-full flex justify-center items-center drop-shadow-[4px_7px_0_rgba(0,0,0,1)] rounded-box border px-5">
              <legend class="fieldset-legend badge badge-primary">Abstrak</legend>
              <textarea name="abstrak" class="textarea text-base-200 h-35 border-base-300 rounded-2xl bg-zinc-100 border w-9/12 resize-none drop-shadow-[2px_5px_0_rgba(0,0,0,1)]" placeholder="Tulis abstrak" required></textarea>
            </fieldset>
          </div>

        <!-- Rak & Baris -->
          <div class="row-span-2 col-start-3 row-start-1">
            <fieldset class="fieldset bg-zinc-100 h-full flex flex-col justify-around items-center drop-shadow-[4px_7px_0_rgba(0,0,0,1)] rounded-box border px-5">
              <legend class="fieldset-legend badge badge-primary">Rak & Baris</legend>
              <select name="lokasi_rak" class="select select-primary border-2 bg-transparent rounded-2xl text-gray-500" required>
                <option disabled selected>Pilih Rak</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
              </select>
              <select name="lokasi_baris" class="select select-error border-2 bg-transparent rounded-2xl text-gray-500" required>
                <option disabled selected>Pilih Baris</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
              </select>
            </fieldset>
          </div>

        <!-- Kategori Arsip -->
          <div class="col-start-3 row-start-3">
            <fieldset class="fieldset bg-zinc-900 h-full flex justify-center items-center drop-shadow-[4px_7px_0_rgba(0,0,0,1)] rounded-box border px-5">
              <legend class="fieldset-legend badge badge-primary">Kategori</legend>
              <select name="kategori" class="select select-primary border-2 bg-zinc-900 rounded-2xl text-zinc-300" required>
                <option disabled selected>Pilih Kategori</option>
                <option value="Teknologi">Teknologi</option>
                <option value="Sains">Sains</option>
                <option value="Sejarah">Sejarah</option>
                <option value="Hukum & Politik">Hukum & Politik</option>
                <option value="Kesehatan">Kesehatan</option>
                <option value="Komputer & Informatika">Komputer & Informatika</option>
              </select>
            </fieldset>
          </div>

        <!-- Input Dokumen -->
          <div class="row-start-4">
            <fieldset class="fieldset bg-zinc-900 h-10/12 flex justify-center items-center drop-shadow-[4px_7px_0_rgba(0,0,0,1)] rounded-box border px-5">
              <legend class="fieldset-legend badge badge-primary">Input Dokumen</legend>
              <input type="file" name="file_dokumen" class="file-input file-input-primary bg-zinc-900" accept=".pdf,.docx,.txt" required />
            </fieldset>
          </div>

        <!-- Nama Pengarang -->
          <div class="col-span-2 row-start-4">
            <fieldset class="fieldset bg-zinc-100 h-10/12 flex justify-center items-center drop-shadow-[4px_7px_0_rgba(0,0,0,1)] rounded-box border px-5">
              <legend class="fieldset-legend badge badge-primary">Nama Pengarang</legend>
              <input type="text" name="pengarang" class="input text-base-200 border-base-300 rounded-2xl bg-transparent border-2 w-full" placeholder="Tulis nama pengarang" required />
            </fieldset>
          </div>

        <!-- Tombol Submit -->
          <div class="col-start-4 row-start-4">
            <button type="submit" class="btn btn-secondary mt-3 w-full h-18 text-lg drop-shadow-[4px_7px_0_rgba(0,0,0,1)]">
              Unggah Arsip
            </button>
          </div>
        </div>
      </form>
    </div>
  </dialog>

@endsection