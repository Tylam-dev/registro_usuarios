<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsuarioRequest;
use App\Service\UsuarioService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller;
use Exception;

class UsuarioController extends Controller
{
    private UsuarioService $usuarioService;

    public function __construct(UsuarioService $usuarioService)
    {
        $this->usuarioService = $usuarioService;
    }

    /**
     * Lista todos los usuarios.
     */
    public function index(): JsonResponse
    {
        try {
            $usuarios = $this->usuarioService->listar();
            return response()->json($usuarios);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error inesperado. Por favor, comuníquese con Programación.'
            ], 500);
        }
    }

    /**
     * Almacena un nuevo usuario.
     */
    public function store(StoreUsuarioRequest $request): JsonResponse
    {
        try {
            $usuario = $this->usuarioService->crear($request->toDomain());
            return response()->json($usuario, Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error inesperado. Por favor, comuníquese con Programación.'
            ], 500);
        }
    }

    /**
     * Muestra un usuario específico.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $usuario = $this->usuarioService->obtenerPorId($id);
            if (! $usuario) {
                return response()->json([
                    'message' => 'Usuario no encontrado.'
                ], Response::HTTP_NOT_FOUND);
            }
            return response()->json($usuario);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error inesperado. Por favor, comuníquese con Programación.'
            ], 500);
        }
    }

    /**
     * Actualiza un usuario existente.
     */
    public function update(StoreUsuarioRequest $request): JsonResponse
    {
        try {
            $request->validate();
            $usuario = $this->usuarioService->actualizar($request->toDomain());
            return response()->json($usuario);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error inesperado. Por favor, comuníquese con Programación.'
            ], 500);
        }
    }

    /**
     * Elimina un usuario.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->usuarioService->eliminar($id);
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error inesperado. Por favor, comuníquese con Programación.'
            ], 500);
        }
    }
}