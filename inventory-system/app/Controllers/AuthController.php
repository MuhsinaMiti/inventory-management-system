<?php

namespace App\Controllers;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

public function doLogin()
{
    $session = session();
    $model   = new UserModel();

    $username = $this->request->getPost('username');
    $password = $this->request->getPost('password');

    $user = $model->where('username', $username)->first();

    if ($user && password_verify($password, $user['password'])) {
        $session->set([
            'logged_in' => true,
            'user'      => $user['username'],
            'role'      => $user['role'],
        ]);

        // 🔄 Redirect based on role
        if ($user['role'] === 'admin') {
            return redirect()->to('/dashboard');
        } else {
            return redirect()->to('/products');
        }
    }

    return redirect()->back()->with('error', 'Invalid credentials');
}





    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }




}
