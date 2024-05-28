<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Title;
use Livewire\Component;

class Forgot extends Component
{
    #[Title('Forgot')]

    public $email;
    public function save(){
        $this->validate([
            'email' => 'required|email|max:255|exists:users,email'
        ]);

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('success', 'Check your box!');
            $this->email = '';
        }
        
    }
    public function render()
    {
        return view('livewire.auth.forgot');
    }
}