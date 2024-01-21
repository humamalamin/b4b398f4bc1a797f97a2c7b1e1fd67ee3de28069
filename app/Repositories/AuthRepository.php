<?php

namespace App\Repositories;
use App\Interfaces\AuthInterface;
use App\Models\User;

class AuthRepository implements AuthInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }
    public function login(string $username, string $password)
    {
        return $this->model->where('email', $username)->first();
    }
    public function register($data)
    {
        return $this->model->create($data);
    }

    public function profile($username)
    {
        $authField = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        return $this->model->where($authField, 'like', '%' . $username . '%')->first();
    }
}
