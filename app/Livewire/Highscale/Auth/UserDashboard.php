<?php

namespace App\Livewire\Highscale\Auth;

use App\Models\Position;
use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class UserDashboard extends Component
{
    use WithPagination;

    use WithFileUploads;

    public $full_name, $user_name, $userId = 0, $existing_image_path = '', $email, $password, $password_confirmation, $isEditing = false, $passwordUpdate = false, $isOpen = false, $confirm = false, $profile_image, $position = 0, $province = 0;


    public function resetForm()
    {

        $this->full_name = '';
        $this->user_name = '';
        $this->email = '';
        $this->password = null;
        $this->password_confirmation = '';
        $this->profile_image = '';
        $this->existing_image_path = '';
        $this->position = 0;
    }
    public function openForm($fs)
    {
        if ($fs) {
            $this->isEditing = false;
            $this->isOpen = true;
        } else {
            $this->isEditing = true;
            $this->isOpen = true;
        }
    }
    public function addUser()
    {
        // Validate form data
        $validatedData = $this->validate([
            'full_name' => 'required|string|max:255',
            'user_name' => 'required|string|unique:users,user_name|max:45',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'position' => 'required',
            'province' => 'required',
            'profile_image' => 'nullable|image|max:1024',
        ]);

        // Hash the password
        $validatedData['password'] = bcrypt($validatedData['password']);

        $imagePath = '';
        if ($this->profile_image != '') {
            try {
                $imageName = $this->user_name . time() . '.' . $this->profile_image->getClientOriginalExtension();
                $imagePath = $this->profile_image->storeAs('user_profiles', $imageName, 'public');
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            $imagePath = 'user_profiles/profileIcon.png';
        }
        User::create([
            'full_name' => $this->full_name,
            'user_name' => $this->user_name,
            'profile_photo_path' =>  $imagePath ?? null,
            'password' => $validatedData['password'],
            'position' => $this->position,
            'province_id' => $this->province,
            'email' => $this->email,
            'created_by' => auth()->user()->id
        ]);

        // Flash a success message and reset the form
        session()->flash('message', 'User successfully registered.');
        $this->reset(['full_name', 'user_name', 'email', 'password', 'password_confirmation', 'profile_image', 'province']);
        $this->position = 0;
        $this->isOpen = false;
    }

    public function editUser($id)
    {
        $this->isEditing = true;
        $this->resetForm();

        $user = User::find($id);

        $this->full_name = $user->full_name;
        $this->user_name = $user->user_name;
        $this->email = $user->email;
        $this->existing_image_path = $user->profile_photo_path;
        $this->position = $user->position;
        $this->province = $user->province_id;
        $this->userId = $user->id;
    }

    public function updateUser()
    {
        // Find the user by ID
        $user = User::find($this->userId);

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

        if ($this->province !== $user->province_id) {
            $validationRules['province'] = 'required';
        }

        if ($this->position !== $user->position) {
            $validationRules['position'] = 'required';
        }


        if ($this->profile_image) {
            $validationRules['profile_image'] = 'nullable|image|max:1024';
        }

        if ($this->passwordUpdate && $this->password) {
            $validationRules['password'] = 'required|string|min:8|confirmed';
        }

        // Only perform validation if there are rules
        if (!empty($validationRules)) {
            $validatedData = $this->validate($validationRules);
        } else {
            // No fields changed, nothing to validate or update
            return;
        }

        // Handle profile image if changed
        if ($this->profile_image) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            try {
                $imageName = $this->user_name . time() . '.' . $this->profile_image->getClientOriginalExtension();
                $imagePath = $this->profile_image->storeAs('user_profiles', $imageName, 'public');
                $user->profile_photo_path = $imagePath;
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }

        // Update only changed attributes
        if (isset($validatedData['full_name'])) {
            $user->full_name = $validatedData['full_name'];
        }

        if (isset($validatedData['user_name'])) {
            $user->user_name = $validatedData['user_name'];
        }

        if (isset($validatedData['email'])) {
            $user->email = $validatedData['email'];
        }

        if (isset($validatedData['province'])) {
            $user->province_id = $validatedData['province'];
        }

        if (isset($validatedData['position'])) {
            $user->position = $validatedData['position'];
        }

        if ($this->passwordUpdate && $this->password) {
            $user->password = Hash::make($this->password);
        }

        // Save the user and reset the form
        $user->update_by = auth()->user()->id;
        $done = $user->save();

        if ($done) {
            $this->resetForm();
            $this->dispatch('profile-photo-updated', [
                'src' => url('storage/' . auth()->user()->profile_photo_path)
            ]);
            $this->dispatch('recordUpdate');
        }
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }
        if ($user) {
            $user->delete();
        }
    }

    public function render()
    {
        return view('livewire.highscale.auth.user-dashboard', [
            'table_data' => DB::connection('scale_system')->table('users')
                ->select(
                    'id',
                    'full_name',
                    'user_name',
                    'email',
                    'position',
                    'province_id'
                )
                ->paginate(5),

            'positions' => DB::connection('scale_system')->table('position')
                ->select(
                    'id',
                    'name',
                )
                ->get(),
            'provinces' => DB::connection('scale_system')->table('provinces')->get(),

        ]);
    }
}
