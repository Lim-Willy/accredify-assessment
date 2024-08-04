<?php

namespace App\Services;

use App\DataTransferObjects\Login\LoginCredential;
use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserService
{
  public UserRepository $repository;

  public function __construct() {
    $this->repository = new UserRepository();
  }


  public function verifyCredentials(LoginCredential $credential) : User
  {
    $user = $this->repository->querySingle([
      'email' => $credential->get('email')
    ], true);
    if(!$user) {
      throw new Exception("No User Found", Response::HTTP_NOT_FOUND);
    }
    if(!Hash::check($credential->get('password'), $user->password)) {
      throw new Exception("Invalid Password", Response::HTTP_UNAUTHORIZED);
    }
    return $user;
  }
}