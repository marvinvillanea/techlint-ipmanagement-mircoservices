<?php

namespace App\Actions;

use App\DTOs\RegisterUserDTO;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\RefreshTokenRepository;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\ValidationException;

class RegisterUser
{
    public function __construct(protected UserRepositoryInterface $users)
    {}

    public function execute(RegisterUserDTO $dto): array
    {

        $user = $this->users->create([
            'name'=>$dto->name,
            'email'=>$dto->email,
            'password'=>Hash::make($dto->password),
            'permission'=>$dto->permission,
        ]);

        $token = JWTAuth::fromUser($user);

        

        return [
            'user' => $user,
            'token' => $token,
            'refresh_token' =>  RefreshTokenRepository::create($user->id)
        ];
    }
}
