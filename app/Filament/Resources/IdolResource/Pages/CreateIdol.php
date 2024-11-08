<?php

namespace App\Filament\Resources\IdolResource\Pages;

use App\Filament\Resources\IdolResource;
use Filament\Resources\Pages\CreateRecord;

class CreateIdol extends CreateRecord
{
    protected static string $resource = IdolResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
