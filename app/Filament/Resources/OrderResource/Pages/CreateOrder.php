<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function getFormSchema(): array
    {
        return [
            Select::make('user_id')
                ->label('Customer')
                ->relationship('user', 'id')
                ->searchable()
                ->required(),
            TextInput::make('invoice_number')
                    ->required()
                    ->label('Invoice Number'),
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
                ->label('Order Status')
                ->options([
                    'SUCCESS' => 'Success',
                    'FAILED' => 'Failed',
                    'PENDING' => 'Pending',
                    'IN_CART' => 'In Cart',
                ])
                ->required()
                ->reactive()
                ->afterStateUpdated(fn ($state) => [
                    'status_color' => match ($state) {
                        'SUCCESS' => 'green',
                        'FAILED' => 'red',
                        'PENDING' => 'yellow',
                        'IN_CART' => 'blue',
                        default => 'gray',
                    }
                ]),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        Notification::make()
            ->title('Order updated successfully')
            ->success()
            ->send();

        return $record;
    }
}

