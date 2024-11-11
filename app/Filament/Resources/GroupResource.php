<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GroupResource\Pages;
use App\Models\Group;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
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
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class GroupResource extends Resource
{
    protected static ?string $model = Group::class;

    protected static ?string $slug = 'groups';

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

                DatePicker::make('debut_date'),

                TextInput::make('company')
                    ->required(),

                TextInput::make('spotify_id')
                    ->label('Spotify ID'),


                SpatieMediaLibraryFileUpload::make('cover_image')
                    ->image()
                    ->avatar()
                    ->collection('cover_images')
                    ->required(),


                SpatieMediaLibraryFileUpload::make('photos')
                    ->image()
                    ->collection('photos')
                    ->multiple(),

                MarkdownEditor::make('bio'),

                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?Group $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?Group $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('debut_date')
                    ->date(),

                TextColumn::make('idols_count')
                    ->label('Idols')
                    ->counts('idols'),

                TextColumn::make('company'),
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
            'index' => Pages\ListGroups::route('/'),
            'create' => Pages\CreateGroup::route('/create'),
            'edit' => Pages\EditGroup::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
}
