<div class="grid grid-cols-4 grid-rows-1 gap-3 mt-8">
  @foreach($arsip as $item)
  <div class="card w-60 h-80 bg-zinc-800/40 backdrop-blur-md border-gray-200/70 border shadow-sm">
    <figure class="px-5 pt-5 h-3/4">
      <img src="{{ $item->thumbnail_path ? asset('storage/'.$item->thumbnail_path) : asset('images/empty-thumbnail-2.svg') }}" 
           alt="{{ $item->judul }}" 
           class="rounded-xl size-full {{ $item->thumbnail_path ? 'object-cover' : 'object-contain p-2' }}" />
    </figure>
    <div class="card-body items-center text-center">
      
      <div class="tooltip w-full tooltip-primary -mb-2" data-tip="{{ $item->judul }}">
        <p class="card-title w-full truncate text-ellipsis overflow-hidden whitespace-nowrap text-center">
          {{ $item->judul }}
        </p>
      </div>

      <p>{{ $item->pengarang }}</p>
      <div class="badge badge-secondary px-px w-full">{{ $item->kategori ?? 'Tidak ada kategori' }}</div>

      <div class="card-actions w-full">
        <!-- Tombol buka modal -->
        <button class="btn btn-primary w-full h-8" onclick="document.getElementById('modal_{{ $item->id }}').showModal()">
          Lihat Detail
        </button>
      </div>
    </div>
  </div>

  <!-- Modal Detail -->
    <dialog id="modal_{{ $item->id }}" class="modal">
      <div class="modal-box max-w-dvw h-[95dvh] bg-zinc-950/60 backdrop-blur-md border-gray-200/70 border shadow-lg rounded-4xl text-zinc-100 grid grid-cols-6 grid-rows-5 gap-4">
        <div class="col-span-2 row-span-2">
          <h1 class="text-xl font-bold mb-2 mt-4">{{ $item->judul }}</h1>
          <p class="text-md text-gray-400 mb-2">{{ $item->pengarang }}</p>
          <p class="mb-4">
            <span class="badge badge-secondary">{{ $item->kategori ?? 'Tidak ada kategori' }}</span>
          </p>
        </div>
  
        <div class="col-span-2 row-span-2 col-start-3 flex gap-2">
          <svg class="w-6 h-6 text-gray-800 dark:text-white mb-2 mt-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15a6 6 0 1 0 0-12 6 6 0 0 0 0 12Zm0 0v6M9.5 9A2.5 2.5 0 0 1 12 6.5"/>
          </svg>
          <p class="text-lg mb-2 mt-4">Lokasi: Rak {{ $item->lokasi_rak }}, Baris {{ $item->lokasi_baris }}</p>
        </div>
  
        <div class="col-start-6 absolute top-2 right-2">
          <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
          </form>
        </div>
  
        <div class="col-span-2 row-span-5 col-start-5 row-start-1 p-6">
          <figure class="w-full h-full">
            <img src="{{ $item->thumbnail_path ? asset('storage/'.$item->thumbnail_path) : asset('images/empty-thumbnail-2.svg') }}" 
                 alt="{{ $item->judul }}" 
                 class="rounded-xl size-full {{ $item->thumbnail_path ? 'object-cover' : 'object-contain p-2' }}" />
          </figure>
        </div>
        <div class="col-span-4 row-span-3 row-start-3 bg-zinc-700/40 backdrop-blur-md border-gray-200/40 border rounded-3xl shadow-md p-4 mb-6">
          <h2 class="text-lg font-bold mb-2 mt-4">Abstraksi</h2>
          <p class="text-md leading-relaxed">
            {{ $item->abstrak ?? 'Tidak ada deskripsi atau abstrak yang tersedia untuk arsip ini.' }}
          </p>
        </div>
  
      </div>
    </dialog>
  @endforeach
</div>
