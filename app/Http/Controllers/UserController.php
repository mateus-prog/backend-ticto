<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Traits\Pagination;
use App\Services\UserService;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    use Pagination;

    protected $userService;

    public function __construct(
        UserService $userService
    )
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try{
            $users = $this->userService->all();

            return response()->json(new UserCollection($users), Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(), 
                'status' => $e->getCode()
            ], $e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): JsonResponse
    {
        try {
            $input = $request->only([
                'first_name', 'last_name', 'email', 'administrator', 
                'cpf', 'position', 'date_of_birth',
                'cep', 'address', 'number', 'complement',
                'district', 'city', 'state'
            ]);
            $user = $this->userService->store($input);

            return response()->json(new UserResource($user), Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(), 
                'status' => $e->getCode()
            ], $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $user = $this->userService->findById($id);  
            return response()->json(new UserResource($user), Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode()
            ], $e->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id): JsonResponse|Response
    {
        try {
            $input = $request->only([
                'first_name', 'last_name', 'email', 'administrator', 
                'cpf', 'position', 'date_of_birth',
                'cep', 'address', 'number', 'complement',
                'district', 'city', 'state'
            ]);
            $this->userService->update($id, $input);

            return response()->noContent();
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(), 
                'status' => $e->getCode()
            ], $e->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function resetPassword(Request $request,string $id): JsonResponse|Response
    {
        try {
            $input = $request->only(['password']);
            $password = $this->userService->resetPassword($id, $input);

            return response()->json([
                'password' => $password,                
                'message' => 'Senha atualizada com sucesso.', 
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(), 
                'status' => $e->getCode()
            ], $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse|Response
    {
        try {
            $this->userService->destroy($id);

            return response()->noContent();
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(), 
                'status' => $e->getCode()
            ], $e->getCode());
        }
    }
}
