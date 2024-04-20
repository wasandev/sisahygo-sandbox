<?php

namespace App\Http\Livewire;
 
use App\Models\Branch_area;
use Livewire\Component;
 
class ServiceArea extends Component
{
    public $query;
    public $branchareas;
    public $highlightIndex;
 
    public function mount()
    {
        $this->resetData();
    }
 
    public function resetData()
    {
        $this->query = '';
        $this->branchareas = [];
        $this->highlightIndex = 0;
    }
 
    public function incrementHighlight()
    {
        if ($this->highlightIndex === count($this->branchareas) - 1) {
            $this->highlightIndex = 0;
            return;
        }
        $this->highlightIndex++;
    }
 
    public function decrementHighlight()
    {
        if ($this->highlightIndex === 0) {
            $this->highlightIndex = count($this->branchareas) - 1;
            return;
        }
        $this->highlightIndex--;
    }
 
    public function selectBrancharea()
    {
        $brancharea = $this->branchareas[$this->highlightIndex] ?? null;
        if ($brancharea) {
            $this->redirect(route('service_area', $contact['id']));
        }
    }
 
    public function updatedQuery()
    {
        $this->branchareas = Branch_area::where('district', 'like', '%' . $this->query . '%')
            ->where('branch_id' ,'<>' ,1)
            ->get()
            ->toArray();
    }
 
    public function render()
    {
        return view('livewire.service-area');
    }
}
