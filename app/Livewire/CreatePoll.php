<?php

namespace App\Livewire;

use App\Models\Poll;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreatePoll extends Component
{
    #[Validate] 
    public $title;
    public $options = ['First'];

    protected $rules = [
        'title' => 'required|min:3|max:100',
        'options' => 'required|array|min:1|max:10',
        'options.*' => 'required|min:5',

    ];

    protected $messages = [
        'options.*' => 'The option can\'t be empty.'
    ];

    public function render()
    {
        return view('livewire.create-poll');
    }

    public function addOption()
    {
        $this->options[] = '';
    }

    public function removeOption($index)
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options);
    }


    public function createPoll()
    {
        $this->validate();

        Poll::create([
            'title' => $this->title
        ])
        ->options()->createMany(
            collect($this->options)
                ->map(fn($option) => ['name' => $option])
                ->all()
        );

        $this->reset(['title', 'options']);
        $this->dispatch('pollCreated');
    }

    
    public function mount()
    {

    }
}


