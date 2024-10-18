<?php

namespace App\Filament\Helpers;

use App\Models\Gateway;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class FilamentTabsHelper
{
    /**
     * @return array<string,Tab>
     */
    public static function getTabsArray(): array
    {
        $tabs = [];

        if (Gateway::count() <= 1) {
            return $tabs;
        }

        foreach (Gateway::all() as $gateway) {
            $tabs = Arr::add($tabs, $gateway->name,
                Tab::make($gateway->name)
                    ->modifyQueryUsing(function (Builder $query) use ($gateway) {
                        return $query->where('gateway_id', '=', $gateway->id);
                    }));
        }

        return $tabs;
    }
}
