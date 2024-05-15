<div>
    @isset( $selectMethod )
    <button class="btn btn-link" wire:click="{{ $selectMethod }}" wire:loading.attr='disabled'>
        <i class="bx bx-trash-alt text-danger"></i>
    </button>
    @endif
</div>