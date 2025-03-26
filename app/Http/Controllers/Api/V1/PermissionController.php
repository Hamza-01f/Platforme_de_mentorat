<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use App\Interfaces\PermissionRepositoryInterface;
use App\Repositories\PermissionRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class PermissionController extends Controller
{
    protected PermissionRepositoryInterface $permissionRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Interfaces\PermissionRepositoryInterface  $permissionRepository
     * @return void
     */
    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Display a listing of permissions.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $permissions = $this->permissionRepository->getAllPermissions();

            if ($permissions->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No permissions found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $permissions
            ]);
        } catch (Exception $e) {
            Log::error("Error fetching permissions: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Error fetching permissions",
            ], 500);
        }
    }

    /**
     * Store a newly created permission.
     *
     * @param  \App\Http\Requests\PermissionRequest  $request
     * @return JsonResponse
     */
    public function store(PermissionRequest $request): JsonResponse
    {
        try {
            $permission = $this->permissionRepository->createPermission($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Permission created successfully',
                'data' => $permission
            ], 201);
        } catch (Exception $e) {
            Log::error("Error creating permission: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified permission.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $permission = $this->permissionRepository->getPermissionById($id);

            if (!$permission) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permission not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $permission
            ]);
        } catch (Exception $e) {
            Log::error("Error fetching permission with ID {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Error fetching permission",
            ], 500);
        }
    }

    /**
     * Update the specified permission.
     *
     * @param  \App\Http\Requests\PermissionRequest  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(PermissionRequest $request, int $id): JsonResponse
    {
        try {
            $permission = $this->permissionRepository->updatePermission($id, $request->validated());

            if (!$permission) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permission not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Permission updated successfully',
                'data' => $permission
            ]);
        } catch (Exception $e) {
            Log::error("Error updating permission with ID {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Error updating permission",
            ], 500);
        }
    }

    /**
     * Remove the specified permission.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->permissionRepository->deletePermission($id);

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permission not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Permission deleted successfully'
            ]);
        } catch (Exception $e) {
            Log::error("Error deleting permission with ID {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Error deleting permission",
            ], 500);
        }
    }
}