<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Branch_area;
use App\Models\Productservice_price;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ServicePrice extends Component
{
    use WithPagination;
    public string $searchPrice = "";
    public string $searchBrancharea = "";
    public Collection $branchareas;

     public function mount(): void {
        $this->branchareas = Branch_area::whereNotIn('branch_id',[1,6])
        ->orderBy('branch_id')
        ->pluck('district', 'district');
                                        
                                        
    }

    public function render()
    {
        $serviceprices = Productservice_price::with('product')
        ->with('branch_area')
        ->whereHas('product',fn(Builder $query) => $query->whereNotIn('category_id',[6,8]))
        ->when($this->searchPrice !== '', fn(Builder $query) => $query->whereHas('product',fn(Builder $query) => $query->where('name' ,'like', '%'. $this->searchPrice .'%') )) 
        ->when($this->searchBrancharea !== '', fn(Builder $query) => $query->where('district', $this->searchBrancharea)) 
        ->paginate(20);

        return view('livewire.service-price', [
            'serviceprices' => $serviceprices
        ]);
    }
     public function updating($key): void
    {
        if ($key === 'searchPrice' || $key === 'searchBrancharea') {
            $this->resetPage();
        }
    }
}
