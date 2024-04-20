<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Branch_area;

class ServiceArea extends Component
{
     use WithPagination;

     

     public $orderColumn = "branch_id";
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
          $branchareas = Branch_area::where('branch_id','<>',1)
                        ->orderby($this->orderColumn,$this->sortOrder)->select('*');

          if(!empty($this->searchTerm)){
                
               $branchareas->where('district','like',"%".$this->searchTerm."%");
               $branchareas->orWhere('province','like',"%".$this->searchTerm."%");
               
          }

          $branchareas = $branchareas->paginate(20);

          return view('livewire.service-area', [
               'branchareas' => $branchareas,
          ]);

     }
}