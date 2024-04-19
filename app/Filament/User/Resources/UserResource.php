<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'email';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Basic Information')
                    ->schema([
                        TextEntry::make('name')
                            ->columnSpanFull(),
                        TextEntry::make('email'),
                        TextEntry::make('email_verified_at')
                            ->dateTime('d/m/Y H:i'),
                    ])->columns(2),
                Section::make('Access Information')
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime('d/m/Y H:i'),
                        TextEntry::make('updated_at')
                            ->dateTime('d/m/Y H:i'),
                    ])->columns(2),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->email(),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->label('Password')
                    ->confirmed()
                    ->rule(Password::default())
                    ->dehydrated(fn($state) => filled($state))
                    ->dehydrateStateUsing(fn($state) => Hash::make($state)),
                Forms\Components\TextInput::make('password_confirmation')
                    ->password()
                    ->dehydrated(false),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->description(function (User $user) {
                        $ret = '';
                        foreach ($user->roles as $role) {
                            $ret .= $role->name.' ';
                        }

                        return $ret;
                    }),
                Tables\Columns\TextColumn::make('email'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
}
