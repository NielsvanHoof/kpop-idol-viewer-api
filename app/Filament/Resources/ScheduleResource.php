<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Models\Schedule;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $slug = 'schedules';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required(),

                MarkdownEditor::make('description')
                    ->required(),

                DatePicker::make('date'),

                TextInput::make('type')
                    ->required(),

                TextInput::make('location')
                    ->required(),

                Select::make('idol_id')
                    ->relationship('idol', 'name')
                    ->searchable(),

                Select::make('group_id')
                    ->relationship('group', 'name')
                    ->searchable(),

                Select::make('created_by')
                    ->relationship('createdBy', 'name')
                    ->searchable()
                    ->required(),

                Checkbox::make('reminder'),

                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?Schedule $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?Schedule $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('date')
                    ->date(),

                TextColumn::make('location'),

                TextColumn::make('type'),

                TextColumn::make('idol.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('group.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('createdBy.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('reminder'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['createdBy', 'group', 'idol']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'createdBy.name', 'group.name', 'idol.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->createdBy) {
            $details['CreatedBy'] = $record->createdBy->name;
        }

        if ($record->group) {
            $details['Group'] = $record->group->name;
        }

        if ($record->idol) {
            $details['Idol'] = $record->idol->name;
        }

        return $details;
    }
}
