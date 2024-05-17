<div>
    @isset( $editLink )
    <a href="{{ $editLink }}" class="p-0">
        <i class='bx bx-pencil'></i>
    </a>
    @endif

    @isset( $deleteMethod )
    <button class="btn btn-link p-0" wire:click="{{ $deleteMethod }}" wire:loading.attr='disabled'>
        <i class="bx bx-trash-alt text-danger"></i>
    </button>
    @endif

    @isset( $printMethod )
    <button class="btn btn-link p-0" wire:click="{{ $deleteMethod }}" wire:loading.attr='disabled'>
        <i class="bx bx-printer text-primary"></i>
    </button>
    @endif

    @isset( $selectMethod )
    <button class="btn btn-link p-0" wire:click="{{ $selectMethod }}" wire:confirm="Are you sure you want to delete?"
        wire:loading.attr='disabled'>
        <i class="bx bx-trash-alt text-danger"></i>
    </button>
    @endif
</div>