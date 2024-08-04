<?php

namespace App\Http\Controllers\Authentication;

use App\DataTransferObjects\Login\LoginCredential;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\Response\ErrorResponseResource;
use App\Http\Resources\Response\SuccessResponseResource;
use App\Http\Resources\User\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class Login extends Controller
{
    protected const AUTH_TOKEN = 'authToken';
    protected UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $loginCredentials = $request->only(['email', 'password']);
        try{
            $user = $this->userService->verifyCredentials(new LoginCredential($loginCredentials));
            $authToken = $user->createToken(self::AUTH_TOKEN)->plainTextToken;
        }catch(\Throwable $th) {
            $code = $th->getCode() ? $th->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
            return new JsonResponse(
                new ErrorResponseResource([
                    'message'   => $th->getMessage()
                ]), 
                $code
            );
        }
        return new JsonResponse(
            new SuccessResponseResource([
                'user'  => new UserResource($user),
                'token' => $authToken
            ])
        );
    }
}
