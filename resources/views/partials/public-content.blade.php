@extends('dashboard')
@section('content')
<div id="publicView">
  <div id="search-section" class="flex items-center justify-center flex-col static backdrop-blur-sm bg-zinc-950/50 rounded-4xl shadow-xl p-8 w-[98%] h-[95vh] border-gray-200/70 border transition-all duration-500">
    <!-- Filter -->
      <div class="dropdown dropdown-end absolute top-8 right-24 ">
        <div tabindex="0" role="button" class="btn bg-zinc-50 text-black rounded-full "><i class="fa-solid fa-filter text-lg"></i></div>

        <ul tabindex="-1" class="dropdown-content menu bg-zinc-50 text-black rounded-box z-1 w-max p-2 shadow-sm">
          <form id="filter-form" class="grid grid-cols-[1fr_1fr_1fr] grid-rows-5 gap-4 p-2">
            <input class="btn rounded-full" type="checkbox" name="kategori[]" value="Teknologi" aria-label="Teknologi"/>
            <input class="btn rounded-full" type="checkbox" name="kategori[]" value="Sains" aria-label="Sains"/>
            <input class="btn rounded-full" type="checkbox" name="kategori[]" value="Sejarah" aria-label="Sejarah"/>
            <input class="btn rounded-full" type="checkbox" name="kategori[]" value="Hukum & Politik" aria-label="Hukum & Politik"/>
            <input class="btn rounded-full" type="checkbox" name="kategori[]" value="Kesehatan" aria-label="Kesehatan"/>
            <input class="btn rounded-full" type="checkbox" name="kategori[]" value="Komputer & Informatika" aria-label="Komputer & Informatika"/>
            
            <div class="flex gap-2">
              <button type="reset" class="btn btn-square btn-secondary">
                <i class="fa-solid fa-xmark"></i>
              </button>
              <button type="submit" class="btn btn-square btn-success">
                <i class="fa-solid fa-magnifying-glass"></i>
              </button>
            </div>
          </form>
        </ul>
      </div>

    <!-- Administrator Mode -->
      <button class="btn absolute top-8 right-8 bg-zinc-50 text-black border-0 rounded-full" 
              onclick="adminMode.showModal()">
          <i class="fa-solid fa-shield-halved text-lg"></i>
      </button>
  
    <!-- Search Bar -->
      <h1 id="page-title" class="text-6xl font-bold mb-6">Rak Kearsipan</h1>
      <div class="form-control w-4xl h-16">
        <div class="input-group w-full transition-all duration-500">
          <label id="searchbar" class="input w-full h-12 rounded-full bg-zinc-50 text-black shadow-lg">
            <svg class="h-5 opacity-100 text-black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></g></svg>
            <input type="search" class="input-lg placeholder-black" placeholder="Cari nama atau pengarang"/>
          </label>
          <div id="suggestions" class="absolute w-4xl max-h-52 overflow-y-auto bg-zinc-50 text-black mt-2 rounded-lg shadow-lg hidden z-50"></div>
        </div>
      </div>
  </div>
</div>

<!-- Modals Dialog -->
  <dialog id="adminMode" class="modal">
    <div class="modal-box bg-zinc-50 text-black text-center w-4xl h-54">
      <h3 class="text-lg font-bold">Masukkan kode untuk masuk ke mode Administrator</h3>
      <form action="{{ route('dashboard.admin') }}" method="POST" class="mt-10">
        @csrf
        <input type="text" onfocus="this.type='password'" name="code" inputmode="numeric" autocomplete="off" maxlength="6" class="input code-input bg-gray-800 text-white placeholder-white rounded-full text-center" placeholder="_ _ _ _ _ _" oninput="this.value = this.value.replace(/[^0-9]/g, '')"/>
      </form>
    </div>
    <form method="dialog" class="modal-backdrop">
      <button>close</button>
    </form>
  </dialog>
@endsection