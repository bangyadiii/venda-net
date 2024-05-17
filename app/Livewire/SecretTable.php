<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Secret;

class SecretTable extends DataTableComponent
{
    protected $model = Secret::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("username", "name")
                ->sortable(),
            Column::make("Profile", "profile")
                ->sortable(),
            Column::make("Service", "Service")
                ->sortable(),
            Column::make('Action')
                ->label(
                    fn ($row, Column $column) => view('components.livewire.datatables.action-column')->with(
                        [
                            'selectMethod' => 'delete(' . $row->id . ')',
                        ]
                    )
                )->html(),
        ];
    }
}
