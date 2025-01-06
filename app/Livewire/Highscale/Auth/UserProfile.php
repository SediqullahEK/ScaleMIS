<?php

namespace App\Livewire\Highscale\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class UserProfile extends Component
{
    use WithFileUploads;

    public $changePassword = false;
    public $full_name = '';
    public $profile;
    public $user_name = '';
    public $email = '';
    public $position;

    public $isOpen = false;


    public $profile_image;

    public function updatedProfileImage()
    {
        // Wait until the file is fully uploaded before validating and processing
        if ($this->profile_image) {
            $this->validate(
                [
                    'profile_image' => 'mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/webp|max:1024',
                ],
                [
                    'profile_image.mimetypes' => 'نوع درست عکس را انتخاب کنید',
                    'profile_image.max' => 'سایز عکس نباید از 1 mb زیادتر باشد',
                ]
            );

            $this->updateProfilePhoto();
        }
    }

    public function updateProfilePhoto()
    {
        $user = User::find(auth()->user()->id);

        if ($this->profile_image) {
            // Delete the previous photo if it exists and isn't the default one
            if ($user->profile_photo_path && $user->profile_photo_path !== 'user_profiles/profileIcon.png') {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            try {
                $imageName = $this->user_name . time() . '.' . $this->profile_image->getClientOriginalExtension();
                $imagePath = $this->profile_image->storeAs('user_profiles', $imageName, 'public');
                $user->profile_photo_path = $imagePath;
                $user->save();
                $this->dispatch('profile-photo-updated', [
                    'src' => url('storage/' . $user->profile_photo_path)
                ]);
                session()->flash('message', 'عکس پروفایل کاربر موفقانه تجدید گردید');
            } catch (\Exception $e) {
                session()->flash('error', 'مشکلی در تجدید عکس پروفایل رخ داد');
            } finally {
                // Reset the file input
                $this->reset('profile_image');
            }
        }
    }


    public function toggleChangePassword($p)
    {
        $this->changePassword = $p;
    }

    public function updateProfile()
    {

        $user = User::find(auth()->user()->id);

        $validationRules = [];

        if ($this->full_name !== $user->full_name) {
            $validationRules['full_name'] = 'required|string|max:255';
        }

        if ($this->user_name !== $user->user_name) {
            $validationRules['user_name'] = 'required|string|unique:users,user_name|max:45';
        }

        if ($this->email !== $user->email) {
            $validationRules['email'] = 'required|email|unique:users,email';
        }

        // Only perform validation if there are rules
        if (!empty($validationRules)) {
            $validatedData = $this->validate($validationRules);
        } else {
            // No fields changed, nothing to validate or update
            return;
        }

        if (isset($validatedData['full_name'])) {
            $user->full_name = $validatedData['full_name'];
        }

        if (isset($validatedData['user_name'])) {
            $user->user_name = $validatedData['user_name'];
        }

        if (isset($validatedData['email'])) {
            $user->email = $validatedData['email'];
        }

        $done = $user->save();
        session()->flash('message', 'معلومات کاربر موفقانه تجدید گردید');

        $this->dispatch('nameUpdated', [
            'full_name' => $user->full_name
        ]);
    }
    public function deleteProfileImage()
    {
        $user = User::find(auth()->user()->id);

        if ($user->profile_photo_path && $user->profile_photo_path !== 'user_profiles/profileIcon.png') {
            if (Storage::disk('public')->exists($user->profile_photo_path)) {
                if (Storage::disk('public')->delete($user->profile_photo_path)) {
                    $user->profile_photo_path = 'user_profiles/profileIcon.png';
                    $user->save();
                    $this->dispatch('profile-photo-updated', [
                        'src' => url('storage/user_profiles/profileIcon.png')
                    ]);
                }
            }
        }
    }

    public function closeAlert()
    {
        session()->forget('message');
    }

    public function render()
    {
        $user = User::find(@auth()->user()->id);
        $this->full_name = $user->full_name;
        $this->user_name = $user->user_name;
        $this->email = $user->email;
        $this->position = $user->position;
        $this->profile = $user->profile_photo_path;

        return view('livewire.highscale.auth.user-profile')->with([
            'positions' => DB::connection('scale_system')->table('position')->get(),

        ]);
    }
}
