<div>
    @isset( $selectMethod )
    <button class="btn btn-link" wire:click="{{ $selectMethod }}"
        wire:confirm="Are you sure you want to delete?" wire:loading.attr='disabled'>
        <i class="bx bx-trash-alt text-danger"></i>
    </button>
    @endif
</div>