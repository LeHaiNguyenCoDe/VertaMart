<?php

namespace App\Filament\Resources;

use App\Models\Order;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\OrderResource\Pages;
use Filament\Tables\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    protected static ?string $navigationGroup = 'Admin Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('coupons')
                ->required()
                ->label('Coupons'),
            TextInput::make('courier_services')
                ->required()
                ->label('Courier Services'),
            TextInput::make('total_price')
                ->numeric()
                ->label('Total Price'),

            Select::make('status')
                ->options([
                    'SUCCESS' => 'Success',
                    'FAILED' => 'Failed',
                    'PENDING' => 'Pending',
                    'IN_CART' => 'In Cart',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Customer Name')
                    ->searchable(),
                TextColumn::make('invoice_number')
                    ->searchable(),
                TextColumn::make('total_price')
                    ->formatStateUsing(fn (int $state): string => currencyFormat($state)),
                TextColumn::make('coupons')
                    ->label('Coupons'),

                TextColumn::make('courier_services')
                    ->label('Courier Services'),
                BadgeColumn::make('status')
                    ->enum([
                        'SUCCESS' => 'Success',
                        'FAILED' => 'Failed',
                        'PENDING' => 'Pending',
                        'IN_CART' => 'In Cart'
                    ])
                    ->colors([
                        'success' => 'SUCCESS',
                        'danger' => 'FAILED',
                        'primary' => 'PENDING',
                        'secondary' => 'IN_CART'
                    ]),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('Y-m-d H:i'),

                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime('Y-m-d H:i'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'SUCCESS' => 'Success',
                        'FAILED' => 'Failed',
                        'PENDING' => 'Pending',
                        'IN_CART' => 'In Cart'
                    ])
            ])
            ->actions([
                EditAction::make(),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'create' => Pages\CreateOrder::route('/create'),
        ];
    }
}
