<?php

namespace App\Http\Livewire;

use WithPagination;
use App\Models\Products;
use App\Models\Productservice_price;
use Livewire\Component;

class ServicePrice extends Component
{
    public $search = "";
    public $paginate = 10;
    public $selectedGrade = null;
    public $selectedProduct = null;
    public $selectedCategory = null;
    public $selectedTheme = null;
    public $selectedDiscipline = null;

    public function render()
    {

        return view('livewire.', [
        'prices' => Productservice_price::with('product', 'district', 'province', 'grade', 'courses', 'category', 'specialties')
        ->when($this->selectedGrade, function($query){
            $query->where('grade_id', $this->selectedGrade);
        })
        ->when($this->selectedProduct, function($query){
            $query->where('product_id', $this->selectedProduct);
        })
        ->when($this->selectedCategory, function($query){
            $query->where('category_id', $this->selectedCategory);
        })
        ->when($this->selectedTheme, function($query){
            $query->where('theme_id', $this->selectedTheme);
        })
        ->when($this->selectedDiscipline, function($query){
            $query->where('discipline_id', $this->selectedDiscipline);
        })
        ->search(trim($this->search))
        ->paginate($this->paginate),
        'grades' => Grade::all(),
        'products' => Product::all(),
        'categories' => Category::all(),
        'themes' => Theme::all(),
        'disciplines' => Discipline::all()
    ]);
    }
}
