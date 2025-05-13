<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Modal\Actions\ButtonAction;
use Filament\Forms\Components\Repeater;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $items = $data['items'];
        unset($data['items']);

        $this->record = Order::create($data);

        foreach ($items as $item) {
            $this->record->items()->create($item);
        }

        return $data;
    }

    protected function getFormSchema(): array
    {
        return [
            Wizard::make([
                Step::make('Order Details')->schema([
                    TextInput::make('invoice_number')
                        ->default('OR-' . rand(100000, 999999))
                        ->disabled()
                        ->label('Number'),

                    Select::make('customer_id')
                        ->relationship('customer', 'name')
                        ->searchable()
                        ->label('Customer')
                        ->required(),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'NEW' => 'New',
                            'PROCESSING' => 'Processing',
                            'SHIPPED' => 'Shipped',
                            'DELIVERED' => 'Delivered',
                            'CANCELLED' => 'Cancelled',
                        ])
                        ->required()
                        ->default('NEW'),

                    Select::make('currency')
                        ->label('Currency')
                        ->options([
                            'USD' => 'USD',
                            'VND' => 'VND',
                            'EUR' => 'EUR',
                        ])
                        ->required(),

                    Select::make('country')
                        ->label('Country')
                        ->options([
                            'US' => 'United States',
                            'VN' => 'Vietnam',
                            'UK' => 'United Kingdom',
                        ])
                        ->searchable(),

                    Grid::make(3)->schema([
                        TextInput::make('street_address')
                            ->label('Street address'),

                        TextInput::make('city')
                            ->label('City'),

                        TextInput::make('state')
                            ->label('State / Province'),

                        TextInput::make('zip')
                            ->label('Zip / Postal code'),
                    ]),

                    Textarea::make('notes')
                        ->label('Notes')
                        ->rows(4),
                ]),

                Step::make('Order Items')->schema([
                    Repeater::make('items')
                        ->label('Order Items')
                        ->schema([
//                            Select::make('product_id')
//                                ->label('Product')
//                                ->relationship('product', 'name')
//                                ->required()
//                                ->searchable(),

                            TextInput::make('quantity')
                                ->label('Quantity')
                                ->numeric()
                                ->default(1)
                                ->required(),

                            TextInput::make('unit_price')
                                ->label('Unit Price')
                                ->numeric()
                                ->required(),
                        ])
                        ->label('Order Items')
                        ->defaultItems(1)
                        ->createItemButtonLabel('Add to items')
                        ->columns(3),
                ])
            ])->submitAction(
                ButtonAction::make('Create')
                    ->label('Create')
                    ->button()
                    ->color('warning')
            )
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Bạn có thể thêm xử lý tại đây nếu cần
        return $data;
    }

    protected function afterCreate(): void
    {
        Notification::make()
            ->title('Order created successfully')
            ->success()
            ->send();
    }
}
