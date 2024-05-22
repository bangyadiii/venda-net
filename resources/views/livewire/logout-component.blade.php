<form class="navbar-nav flex-row align-items-center ms-auto" wire:submit.prevent='logout'>
    @csrf
    <button type="submit" class="btn text-danger">
        <i class='bx bx-power-off me-2'></i>
        <span class="align-middle">Log Out</span>
    </button>
</form>