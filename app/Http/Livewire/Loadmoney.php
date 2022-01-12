<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Validator;

class Loadmoney extends Component
{
    public $currentStep = 1;
    public $amount;
    public $successMsg = '';

    public function render()
    {
        return view('livewire.loadmoney');
    }
    public function firstStepSubmit()
    {
       
        $validatedData = $this->validate([            
            'amount' => 'required',            
        ]);
 
        $this->currentStep = 2;
    }
    public function submitForm()
    {
        // Team::create([
        //     'name' => $this->name,
        //     'price' => $this->price,
        //     'detail' => $this->detail,
        //     'status' => $this->status,
        // ]);
  
        // $this->successMsg = 'Team successfully created.';
  
        // $this->clearForm()
        $this->successMsg = 'Team successfully created.';
           // echo 'ok';
        $this->currentStep = 1;
    }
    public function clearForm()
    {
        // $this->name = '';
        // $this->price = '';
        // $this->detail = '';
        // $this->status = 1;
    }
    public function back($step)
    {
        $this->currentStep = $step;    
    }
}
