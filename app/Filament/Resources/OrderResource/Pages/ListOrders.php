<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OrderResource\Widgets\OrderOverview;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
           OrderOverview::class
        ];
    }

    public function getTabs(): array
    {
        return [
          null =>  Tab::make('ALL'),
            'new' =>Tab::make()->query(fn ($query)=> $query->where('status','new')),
             'processing' =>Tab::make()->query(fn ($query)=> $query->where('status','processing')),
            'shipped' =>Tab::make()->query(fn ($query)=> $query->where('status','shipped')),
            'delivered' =>Tab::make()->query(fn ($query)=> $query->where('status','delivered')),
            'canceled' =>Tab::make()->query(fn ($query)=> $query->where('status','canceled'))
        ];
    }
}
