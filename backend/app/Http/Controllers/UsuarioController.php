<?php

namespace App\Http\Controllers;

use App\Service\UsuarioService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use DateTimeImmutable;
use App\Domain\EstadoCivilEnum;
use App\Domain\SexoEnum;
use App\Domain\Usuario;
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
    public function store(Request $request): JsonResponse
    {
        try {
            $usuario = $this->usuarioService->crear($this->toDomain($request));
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
    public function update(Request $request): JsonResponse
    {
        try {
            $usuario = $this->usuarioService->actualizar($this->toDomain($request));
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

    private function toDomain(Request $request): Usuario
    {
        return new Usuario(
            0,
            $request['identificacion'],
            $request['nombre_usuario'],
            $request['nombres'],
            $request['apellidos'],
            new DateTimeImmutable($request['fecha_nacimiento']),
            $request['celular'],
            $request['telefono'] ?? null,
            $request['correo'],
            EstadoCivilEnum::from($request['estado_civil']),
            SexoEnum::from($request['sexo']),
            $request['direccion']
        );
    }
}