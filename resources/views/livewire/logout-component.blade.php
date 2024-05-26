<form class="navbar-nav flex-row align-items-center ms-auto" wire:submit.prevent='logout' wire:loading.attr='disabled'>
    @csrf
    <button type="submit" class="btn text-danger">
        <output class="spinner-border  spinner-border-sm me-2" wire:loading>
            <span class="visually-hidden">Loading...</span>
        </output>
        <i class='bx bx-power-off me-2' wire:loading.attr='hidden'></i>
        <span class="align-middle">Log Out</span>
    </button>
</form>