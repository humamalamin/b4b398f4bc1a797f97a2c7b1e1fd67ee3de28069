<?php

namespace App\Interfaces;

interface AuthInterface
{
    public function login(string $username, string $password);
    public function register(array $data);

    public function profile($username);
}
