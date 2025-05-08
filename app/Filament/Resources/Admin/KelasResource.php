<?php

namespace App\Filament\Resources\Admin;

use App\Filament\Resources\Admin\KelasResource\Pages;
use App\Filament\Resources\Admin\KelasResource\RelationManagers;
use App\Models\Kelas;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Manajemen Akademik';

    protected static ?string $modelLabel = 'Kelas';

    protected static ?string $pluralModelLabel = 'Kelas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_kelas')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Kelas'),
                Forms\Components\TextInput::make('tahun_ajaran')
                    ->required()
                    ->maxLength(255)
                    ->label('Tahun Ajaran'),
                Forms\Components\Select::make('wali_kelas_id')
                    ->label('Wali Kelas')
                    ->options(
                        User::where('role', 'guru')->pluck('name', 'id')
                    )
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_kelas')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Kelas'),
                Tables\Columns\TextColumn::make('tahun_ajaran')
                    ->searchable()
                    ->sortable()
                    ->label('Tahun Ajaran'),
                Tables\Columns\TextColumn::make('waliKelas.name')
                    ->searchable()
                    ->sortable()
                    ->label('Wali Kelas'),
                Tables\Columns\TextColumn::make('siswa_count')
                    ->counts('siswa')
                    ->label('Jumlah Siswa'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Dibuat'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Diperbarui'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tahun_ajaran')
                    ->options(fn() => Kelas::distinct()->pluck('tahun_ajaran', 'tahun_ajaran')->toArray())
                    ->label('Tahun Ajaran'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            RelationManagers\SiswaRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelas::route('/'),
            'create' => Pages\CreateKelas::route('/create'),
            'edit' => Pages\EditKelas::route('/{record}/edit'),
        ];
    }
}
