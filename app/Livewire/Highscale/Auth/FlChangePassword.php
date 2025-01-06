<?php

namespace App\Livewire\Highscale\Auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FlChangePassword extends Component
{
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    public $embedded = false;

    public function updatePassword()
    {
        $validatedData = $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($this->current_password, Auth::user()->password)) {
            session()->flash('error', 'رمز فعلی اشتباه است');
            return;
        }

        $user = Auth::user();
        $user->password = Hash::make($this->new_password);
        $user->logged_in = true;
        $user->save();

        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login')->with('success', '!رمز تان موفقانه تجدید شد، با رمز جدید تان دوباره وارد سیستم شوید');
    }
    public function render()
    {
        return view('livewire.highscale.auth.fl-change-password');
    }
}
