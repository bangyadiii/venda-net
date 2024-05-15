<div>
    @isset( $editLink )
    <a href="{{ $editLink }}" class="d-inline">
        <i class='bx bx-pencil'></i>
    </a>
    @endif

    @isset( $deleteMethod )
    <button class="btn btn-link d-inline" wire:click="{{ $deleteMethod }}" wire:loading.attr='disabled'>
        <i class="bx bx-trash-alt text-danger"></i>
    </button>
    @endif
</div>