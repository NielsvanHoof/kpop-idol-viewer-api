<?php

namespace App\Filament\Resources\IdolResource\Pages;

use App\Filament\Resources\IdolResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIdols extends ListRecords
{
    protected static string $resource = IdolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
