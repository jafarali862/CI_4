<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    // ---------- REGISTER ----------
    public function register()
    {
        return view('auth/register');
    }

    public function store()
{
    $file       = $this->request->getFile('profile_pic');
    $hasNewFile = $file && $file->getError() !== UPLOAD_ERR_NO_FILE;

    // Image saved from a previous failed submit (basename() blocks path tricks)
    $tempName = basename((string) $this->request->getPost('temp_pic'));
    $tempDir  = FCPATH . 'uploads/temp/';
    $hasTemp  = $tempName !== '' && is_file($tempDir . $tempName);

    $rules = [
        'name'             => 'required|min_length[3]|max_length[100]',
        'email'            => 'required|valid_email|is_unique[users.email]',
        'password'         => 'required|min_length[8]',
        'confirm_password' => 'required|matches[password]',
        'phone'            => 'required|numeric|exact_length[10]',
        'age'              => 'required|integer|greater_than_equal_to[18]|less_than_equal_to[100]',
        'gender'           => 'required|in_list[Male,Female,Other]',
        'dob'              => 'required|valid_date[Y-m-d]',
        'address'          => 'required|min_length[5]|max_length[500]',
    ];

    // Validate the picture only if a new file was chosen, or no temp copy exists
    if ($hasNewFile || ! $hasTemp) {
        $rules['profile_pic'] = 'uploaded[profile_pic]|is_image[profile_pic]|mime_in[profile_pic,image/jpg,image/jpeg,image/png]|max_size[profile_pic,2048]';
    }

    $messages = [
        'name' => [
            'required'   => 'Please enter your name.',
            'min_length' => 'Name must be at least 3 characters.',
            'max_length' => 'Name cannot exceed 100 characters.',
        ],
        'email' => [
            'required'    => 'Email is required.',
            'valid_email' => 'Please enter a valid email address.',
            'is_unique'   => 'This email is already registered.',
        ],
        'password' => [
            'required'   => 'Password is required.',
            'min_length' => 'Password must be at least 8 characters.',
        ],
        'confirm_password' => [
            'required' => 'Please confirm your password.',
            'matches'  => 'Confirm password does not match the password.',
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
        'profile_pic' => [
            'uploaded' => 'Please upload a profile picture.',
            'is_image' => 'The file must be an image.',
            'mime_in'  => 'Only JPG, JPEG and PNG images are allowed.',
            'max_size' => 'Image size must not exceed 2 MB.',
        ],
    ];

    if (! $this->validate($rules, $messages)) {
        $errors = $this->validator->getErrors();

        // Keep a valid new image in temp so the user doesn't have to pick it again
        if ($hasNewFile && ! isset($errors['profile_pic']) && $file->isValid() && ! $file->hasMoved()) {
            if ($hasTemp) {
                unlink($tempDir . $tempName);   // remove older temp copy
            }
            $tempName = $file->getRandomName();
            $file->move($tempDir, $tempName);
            $hasTemp = true;
        }

        return redirect()->back()
            ->withInput()
            ->with('errors', $errors)
            ->with('temp_pic', $hasTemp ? $tempName : '');
    }

    // ---- Validation passed: move image to its final folder ----
    $profileDir = FCPATH . 'uploads/profiles/';

    if ($hasNewFile) {
        $finalName = $file->getRandomName();
        $file->move($profileDir, $finalName);
        if ($hasTemp) {
            unlink($tempDir . $tempName);
        }
    } else {
        $finalName = $tempName;
        rename($tempDir . $tempName, $profileDir . $finalName);
    }

    (new UserModel())->insert([
        'name'        => $this->request->getPost('name'),
        'email'       => $this->request->getPost('email'),
        'password'    => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        'phone'       => $this->request->getPost('phone'),
        'age'         => $this->request->getPost('age'),
        'gender'      => $this->request->getPost('gender'),
        'dob'         => $this->request->getPost('dob'),
        'address'     => $this->request->getPost('address'),
        'profile_pic' => $finalName,
    ]);

    return redirect()->to('/login')->with('success', 'Registration successful. Please login.');
}

    // ---------- LOGIN ----------
    public function login()
    {
        return view('auth/login');
    }

    public function attempt()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];
        $messages = [
            'email' => [
                'required'    => 'Please enter your email.',
                'valid_email' => 'Please enter a valid email address.',
            ],
            'password' => [
                'required' => 'Please enter your password.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = (new UserModel())->where('email', $this->request->getPost('email'))->first();

        if (! $user) {
            return redirect()->back()->withInput()->with('errors', ['email' => 'No account found with this email.']);
        }

        if (! password_verify($this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->withInput()->with('errors', ['password' => 'Incorrect password.']);
        }

        session()->regenerate();   // prevents session fixation
        session()->set([
            'isLoggedIn' => true,
            'user_id'    => $user['id'],
        ]);

        return redirect()->to('/dashboard')->with('success', 'Welcome back, ' . $user['name'] . '!');
    }

    // ---------- LOGOUT ----------
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'You have been logged out.');
    }
}