<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;
use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Number;


class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Information Order')->schema([
                        Select::make('user_id')
                        ->label('Customer')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                        Select::make('payment_method')
                        ->label('Payment method')
                        ->options([
                            'stripe'=> 'Stripe',
                            'cod' => 'Cash on delivery'
                        ])
                        ->required(),

                        Select::make('payment_status')
                        ->options([
                            'pending'=> 'Pending',
                            'paid' => 'Paid',
                            'failed'=>'Failed'
                        ])
                        ->required(),

                        ToggleButtons::make('status')
                        ->grouped()
                        ->required()
                        ->default('new')
                        ->options([
                            'new' =>'New',
                            'processing' =>'Processing',
                            'shipped' =>'Shipped',
                            'delivered' =>'Delivered',
                            'canceled' =>'Canceled',
                        ])
                        ->colors([
                            'new' =>'info',
                            'processing' =>'warning',
                            'shipped' =>'success',
                            'delivered' =>'success',
                            'canceled' =>'danger',
                        ])
                        ->icons([
                            'new' => 'heroicon-o-sparkles',
                            'processing' => 'heroicon-o-arrow-path',
                            'shipped' => 'heroicon-o-truck',
                            'delivered' =>'heroicon-o-check-badge',
                            'canceled' => 'heroicon-o-x-circle',
                        ]),

                        Select::make('currency')
                        ->options([
                            'usd'=> 'USD',
                            'vn' => 'VND',
                            'eur'=>'EUR'
                        ])
                        ->default('usd')
                        ->required(),

                        Select::make('shipping_method')
                        ->options([
                            'fedex'=> 'FedEx',
                            'ups' => 'UPS',
                            'dhl'=>'DHL',
                            'usps'=>'USPS'
                        ]),

                        Textarea::make('notes')
                        ->columnSpanFull(),

                    ])->columns(2),

                    Section::make('Information Order')->schema([
                        Repeater::make('items')
                        ->relationship()
                        ->schema([
                            Select::make('product_id')
                            ->relationship('product','name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->distinct()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->columnSpan(4)
                            ->reactive()
                            ->afterStateUpdated(fn ($state, Set $set) => $set('unit_amount', Product::find($state)?->price ?? 0 ))
                            ->afterStateUpdated(fn ($state, Set $set) => $set('total_amount', Product::find($state)?->price ?? 0 )),

                            TextInput::make('quantity')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->minValue(1)
                            ->columnSpan(2)
                            ->reactive()
                            ->afterStateUpdated(fn ($state, Set $set , Get $get) => $set('total_amount', $state * $get('unit_amount') )),

                            TextInput::make('unit_amount')
                            ->numeric()
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->columnSpan(3),

                            TextInput::make('total_amount')
                            ->numeric()
                            ->dehydrated()
                            ->columnSpan(3),
                        ])->columns(12),

                       Placeholder::make('grand_total_placeholder')
                        ->label('Grand total')
                        ->content(function (Get $get, Set $set){
                            $total = 0;
                            if(!$repeaters = $get('items')){
                                return $total;
                            }
                            foreach ($repeaters as $key => $repeater){
                                $total += $get("items.{$key}.total_amount");
                            }
                            $set('grand_total',$total);
                            return Number::currency($total, 'USD');
                        }),

                        Hidden::make('grand_total')
                        ->default(0)
                    ])
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('grand_total')
                    ->numeric()
                    ->sortable()
                    ->money('USD'),

                TextColumn::make('payment_method')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('payment_status')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('currency')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('shipping_method')
                    ->searchable()
                    ->sortable(),

                SelectColumn::make('status')
                    ->options([
                        'new' =>'New',
                        'processing' =>'Processing',
                        'shipped' =>'Shipped',
                        'delivered' =>'Delivered',
                        'canceled' =>'Canceled',
                    ])
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                ]),
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
            AddressRelationManager::class
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'success' : 'danger';
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}