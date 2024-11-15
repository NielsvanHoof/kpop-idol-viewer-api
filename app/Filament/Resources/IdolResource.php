<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IdolResource\Pages;
use App\Models\Idol;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
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
use Str;

class IdolResource extends Resource
{
    protected static ?string $model = Idol::class;

    protected static ?string $slug = 'idols';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(function (Set $set, Get $get, string $state) {
                        if (!$get('is_slug_changed_manually') && filled($state)) {
                            $set('slug', Str::slug($state));
                        }

                        $set('slug', Str::slug($state));
                    }),

                TextInput::make('slug')
                    ->afterStateUpdated(function (Set $set) {
                        $set('is_slug_changed_manually', true);
                    })
                    ->required(),


                Hidden::make('is_slug_changed_manually')
                    ->default(false)
                    ->dehydrated(false),


                TextInput::make('spotify_id')
                    ->placeholder('Spotify ID'),


                SpatieMediaLibraryFileUpload::make('profile_photo')
                    ->image()
                    ->collection('profile_photo')
                    ->avatar(),

                SpatieMediaLibraryFileUpload::make('photos')
                    ->image()
                    ->collection('photos')
                    ->multiple(),

                TextInput::make('stage_name'),

                DatePicker::make('birthdate'),

                TextInput::make('nationality')
                    ->required(),

                DatePicker::make('debute_date')->required(),

                TextInput::make('position')
                    ->required(),

                MarkdownEditor::make('bio'),

                Select::make('group_id')
                    ->relationship('group', 'name')
                    ->searchable(),

                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?Idol $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?Idol $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('stage_name'),

                TextColumn::make('nationality'),

                TextColumn::make('position'),

                TextColumn::make('group.name')
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ListIdols::route('/'),
            'create' => Pages\CreateIdol::route('/create'),
            'edit' => Pages\EditIdol::route('/{record}/edit'),
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
        return parent::getGlobalSearchEloquentQuery()->with(['group']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'group.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->group) {
            $details['Group'] = $record->group->name;
        }

        return $details;
    }
}
