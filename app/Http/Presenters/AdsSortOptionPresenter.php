<?php

namespace App\Http\Presenters;

use App\Repositories\Ads\AdsRepository;
use Laracasts\Presenter\Presenter;

class AdsSortOptionPresenter extends Presenter
{
    public function sortOptions()
    {
        $ads = app(AdsRepository::class)->find($this->id);

        return $ads->allSortOptions->map(function ($option) {
            $name = $option->sortOptionValues->sortOption->name;
            $value = $option->sortOptionValues->value;

            return [$name, $value];
        });
    }
}
