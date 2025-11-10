<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PadreResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;

class PadreResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Gestion de usuarios';
    protected static ?string $navigationLabel = 'Padres';
    protected static ?string $modelLabel = 'Padre';
    protected static ?string $pluralModelLabel = 'Padres';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->role('padre');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nombre'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->label('Correo Electrónico'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->label('Contraseña'),
                Select::make('psicologo_id')
                    ->relationship('psicologo', 'name', fn (Builder $query) => $query->role('psicologo'))
                    ->label('Psicólogo Asignado')
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->searchable(),
                Tables\Columns\TextColumn::make('psicologo.name')
                    ->label('Psicólogo Asignado')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPadres::route('/'),
            'create' => Pages\CreatePadre::route('/create'),
            'edit' => Pages\EditPadre::route('/{record}/edit'),
        ];
    }
}