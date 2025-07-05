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
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Exception;

class UsuarioController extends Controller
{
    private UsuarioService $usuarioService;
    private Serializer $serializer;

    public function __construct(UsuarioService $usuarioService)
    {
        $this->usuarioService = $usuarioService;
        $this->serializer = new Serializer([
            new DateTimeNormalizer(['datetime_format' => 'Y-m-d']),
            new ObjectNormalizer()
        ], 
        [new JsonEncoder()]);
    }

    /**
     * Lista todos los usuarios.
     */
    public function index(): Response
    {
        try {
            $usuarios = $this->usuarioService->listar();
            
            $json = $this->serializer->serialize($usuarios, 'json');
            return response($json, 200)
                    ->header('Content-Type','application/json');
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage() 
            ], 500);
        }
    }

    /**
     * Almacena un nuevo usuario.
     */
    public function store(Request $request): Response
    {
        try {
            $usuario = $this->usuarioService->crear($this->toDomain($request));
            $json = $this->serializer->serialize($usuario, 'json');
            return response($json, 200)
                    ->header('Content-Type','application/json');
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
    public function show(Request $request): JsonResponse
    {
        try {
            $mapeo = $this->toDomain($request);
            $usuario = $this->usuarioService->obtenerPorId($mapeo->getId());
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