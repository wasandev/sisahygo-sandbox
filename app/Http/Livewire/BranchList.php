<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Branch;

class BranchList extends Component
{
     use WithPagination;

     

     public $orderColumn = "id";
     public $sortOrder = "asc";
     public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';

     public $searchTerm = "";

     public function updated(){
          $this->resetPage();
     }

     public function sortOrder($columnName=""){
          $caretOrder = "up";
          if($this->sortOrder == 'asc'){
               $this->sortOrder = 'desc';
               $caretOrder = "down";
          }else{
               $this->sortOrder = 'asc';
               $caretOrder = "up";
          } 
          $this->sortLink = '<i class="sorticon fa-solid fa-caret-'.$caretOrder.'"></i>';

          $this->orderColumn = $columnName;

     }

     public function render(){ 
          $branchs = Branch::orderby($this->orderColumn,$this->sortOrder)->select('*');

          if(!empty($this->searchTerm)){
                
               $branchs->where('name','like',"%".$this->searchTerm."%");
               
          }

          $branchs = $branchs->paginate(10);

          return view('livewire.branch', [
               'branchs' => $branchs,
          ]);

     }
}