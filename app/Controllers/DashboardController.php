<?php

namespace App\Controllers;

use App\Models\UserModel;

class DashboardController extends BaseController
{
    private UserModel $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    private function currentUser(): array
    {
        $user = $this->users->find(session()->get('user_id'));

        if (! $user) {
            session()->destroy();
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User not found');
        }
        return $user;
    }

    // ---------- DASHBOARD ----------
    public function index()
    {
        return view('dashboard/index', ['user' => $this->currentUser()]);
    }

    // ---------- EDIT FORM ----------
    public function edit()
    {
        return view('dashboard/edit', ['user' => $this->currentUser(),
        'isEdit' => true,
        ]);
    }

    // ---------- UPDATE ----------
    public function update()
    {
        $user = $this->currentUser();
        $id   = $user['id'];

        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'password' => 'permit_empty|min_length[8]',
            'confirm_password' => 'permit_empty|matches[password]',
            'phone' => 'required|numeric|exact_length[10]',
            'age' => 'required|integer|greater_than_equal_to[18]|less_than_equal_to[100]',
            'gender' => 'required|in_list[Male,Female,Other]',
            'dob' => 'required|valid_date[Y-m-d]',
            'address' => 'required|min_length[5]|max_length[500]',
        ];

        $messages = [
            'name' => [
                'required'   => 'Please enter your name.',
                'min_length' => 'Name must be at least 3 characters.',
                'max_length' => 'Name cannot exceed 100 characters.',
            ],
            'email' => [
                'required'    => 'Email is required.',
                'valid_email' => 'Please enter a valid email address.',
                'is_unique'   => 'This email is already used by another account.',
            ],
            'password' => [
                'min_length' => 'New password must be at least 8 characters.',
            ],
            'confirm_password' => [
                'matches' => 'Confirm password does not match the new password.',
            ],
            'phone' => [
                'required'     => 'Phone number is required.',
                'numeric'      => 'Phone number must contain digits only.',
                'exact_length' => 'Phone number must be exactly 10 digits.',
            ],
            'age' => [
                'required'              => 'Age is required.',
                'integer'               => 'Age must be a whole number.',
                'greater_than_equal_to' => 'You must be at least 18 years old.',
                'less_than_equal_to'    => 'Please enter a valid age (max 100).',
            ],
            'gender' => [
                'required' => 'Please select your gender.',
                'in_list'  => 'Please choose a valid gender option.',
            ],
            'dob' => [
                'required'   => 'Date of birth is required.',
                'valid_date' => 'Please enter a valid date of birth.',
            ],
            'address' => [
                'required'   => 'Address is required.',
                'min_length' => 'Address must be at least 5 characters.',
                'max_length' => 'Address cannot exceed 500 characters.',
            ],
        ];

        // Profile picture is optional on edit: validate only if a file was sent
        $file     = $this->request->getFile('profile_pic');
        $hasImage = $file && $file->getError() !== UPLOAD_ERR_NO_FILE;

        if ($hasImage) {
            $rules['profile_pic'] = 'is_image[profile_pic]|mime_in[profile_pic,image/jpg,image/jpeg,image/png]|max_size[profile_pic,2048]';
            $messages['profile_pic'] = [
                'is_image' => 'The file must be an image.',
                'mime_in'  => 'Only JPG, JPEG and PNG images are allowed.',
                'max_size' => 'Image size must not exceed 2 MB.',
            ];
        }

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'    => $this->request->getPost('name'),
            'email'   => $this->request->getPost('email'),
            'phone'   => $this->request->getPost('phone'),
            'age'     => $this->request->getPost('age'),
            'gender'  => $this->request->getPost('gender'),
            'dob'     => $this->request->getPost('dob'),
            'address' => $this->request->getPost('address'),
        ];

        // Change password only if a new one was entered
        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        // Replace profile picture
        if ($hasImage) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/profiles', $newName);

            $old = FCPATH . 'uploads/profiles/' . $user['profile_pic'];
            if ($user['profile_pic'] && is_file($old)) {
                unlink($old);
            }
            $data['profile_pic'] = $newName;
        }

        $this->users->update($id, $data);

        return redirect()->to('/dashboard')->with('success', 'Profile updated successfully.');
    }

    // ---------- DELETE ACCOUNT ----------
    public function delete()
    {
       $user = $this->currentUser();

    $this->users->delete($user['id']);   // sets deleted_at, row stays
    session()->destroy();

    return redirect()->to('/register')->with('success', 'Your account has been deleted.');

    }
}