<div class="join mt-8 flex justify-center">
  @foreach ($paginator->links()->elements[0] ?? [] as $page => $url)
    <button class="join-item btn {{ $page == $paginator->currentPage() ? 'btn-primary' : 'bg-zinc-800/40 backdrop-blur-md border-gray-200/70 border' }}"
      data-page="{{ $url }}">{{ $page }}</button>
  @endforeach
</div>
