<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Permission\Models\Permission;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-key';

    protected static string|\UnitEnum|null $navigationGroup = 'Access Control';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Permission Details')
                    ->description('Define a granular permission that can be assigned to roles.')
                    ->icon('heroicon-o-key')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('e.g. view-users, create-posts, delete-comments')
                            ->helperText('Use a clear, descriptive name. Lowercase with hyphens is recommended.')
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('guard_name')
                            ->required()
                            ->default('web')
                            ->maxLength(255)
                            ->helperText('The guard this permission applies to.')
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                Section::make('Assigned to Roles')
                    ->description('Optionally assign this permission directly to existing roles.')
                    ->icon('heroicon-o-shield-check')
                    ->schema([
                        Forms\Components\CheckboxList::make('roles')
                            ->relationship('roles', 'name')
                            ->searchable()
                            ->bulkToggleable()
                            ->columns(3)
                            ->gridDirection('row')
                            ->noSearchResultsMessage('No roles found.'),
                    ])
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->copyable()
                    ->copyMessage('Permission name copied'),

                Tables\Columns\TextColumn::make('guard_name')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('roles_count')
                    ->counts('roles')
                    ->label('Used in Roles')
                    ->badge()
                    ->color(fn (int $state): string => $state === 0 ? 'gray' : 'success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('guard_name')
                    ->options(fn () => Permission::query()->distinct()->pluck('guard_name', 'guard_name')->toArray())
                    ->label('Guard'),

                Tables\Filters\Filter::make('unused')
                    ->label('Unused permissions')
                    ->query(fn ($query) => $query->doesntHave('roles'))
                    ->toggle(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (DeleteAction $action, Permission $record) {
                        if ($record->roles()->count() > 0) {
                            $action->cancel();
                            Notification::make()
                                ->title('Cannot delete permission')
                                ->body("The \"{$record->name}\" permission is used by {$record->roles()->count()} role(s). Remove it from all roles before deleting.")
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name')
            ->emptyStateIcon('heroicon-o-key')
            ->emptyStateHeading('No permissions yet')
            ->emptyStateDescription('Create granular permissions to assign to your roles.')
            ->emptyStateActions([
                CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
