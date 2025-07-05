<?php

namespace App\Http\Controllers;

use App\Service\UsuarioService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Enum;
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
use TypeError; 
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
            $this->ValidarCrear($request);
            $usuario = $this->usuarioService->crear($this->toDomain($request));
            $json = $this->serializer->serialize($usuario, 'json');
            return response($json, 200)
                    ->header('Content-Type','application/json');
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }catch (TypeError $e) {
        return response()->json([
            'message' => 'Parametros inválidos: ' . $e->getMessage(),
        ], 400);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Muestra un usuario específico.
     */
    public function show(int $id): Response
    {
        try {
            $usuario = $this->usuarioService->obtenerPorId($id);
            if (! $usuario) {
                return response()->json([
                    'message' => 'Usuario no encontrado.'
                ], Response::HTTP_NOT_FOUND);
            }
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
     * Actualiza un usuario existente.
     */
    public function update(Request $request): Response
    {
        try {
            $this->ValidarActualizar($request);
            $usuario = $this->usuarioService->actualizar($this->toDomain($request));
            return response()->json($usuario);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }catch (TypeError $e) {
        return response()->json([
            'message' => 'Parametros invalidos: ' . $e->getMessage(),
        ], 400);
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
            $request['id']?? 0,
            $request['identificacion'],
            $request['nombreUsuario'],
            $request['nombres'],
            $request['apellidos'],
            new DateTimeImmutable($request['fechaNacimiento']),
            $request['celular'],
            $request['telefono'] ?? null,
            $request['correo'],
            EstadoCivilEnum::from($request['estadoCivil']),
            SexoEnum::from($request['sexo']),
            $request['direccion']
        );
    }
    private function ValidarCrear(Request $request): void
    {
        $request->validate(
            [
                'id'               => 'in:0',
                'identificacion'   => 'required|string|min:10',
                'nombreUsuario'   => 'required|string',
                'nombres'          => 'required|string|max:100',
                'apellidos'        => 'required|string|max:100',
                'fechaNacimiento' => 'required|date|before_or_equal:today',
                'celular'          => 'required|regex:/^[0-9]{10,}$/',
                'telefono'         => 'nullable|regex:/^[0-9]{7,}$/',
                'correo'           => 'required|email',
                'estadoCivil'     => ['required', new Enum(EstadoCivilEnum::class)],
                'sexo'             => ['required', new Enum(SexoEnum::class)],
                'direccion'        => 'required|string',
            ],
            [
                'id.in'                     => 'No esta permitido enviar el campo id > 0 en post.',
                'identificacion.required'   => 'La identificación es obligatoria.',
                'identificacion.min'        => 'La identificación debe tener al menos 10 caracteres.',
                'nombreUsuario.required'   => 'El nombre de usuario es obligatorio.',
                'nombres.required'          => 'Los nombres son obligatorios.',
                'nombres.max'               => 'Los nombres no pueden exceder 100 caracteres.',
                'apellidos.required'        => 'Los apellidos son obligatorios.',
                'apellidos.max'             => 'Los apellidos no pueden exceder 100 caracteres.',
                'fechaNacimiento.required' => 'La fecha de nacimiento es obligatoria.',
                'fechaNacimiento.date'     => 'La fecha de nacimiento debe ser una fecha válida.',
                'fechaNacimiento.before_or_equal' => 'La fecha de nacimiento no puede ser futura.',
                'celular.required'          => 'El número de celular es obligatorio.',
                'celular.regex'             => 'El celular debe ser numérico y tener al menos 10 dígitos.',
                'telefono.regex'            => 'El teléfono debe ser numérico y tener al menos 7 dígitos.',
                'correo.required'           => 'El correo electrónico es obligatorio.',
                'correo.email'              => 'El correo debe ser una dirección válida.',
                'estadoCivil.required'     => 'El estado civil es obligatorio.',
                'estadoCivil.enum'         => 'El estado civil seleccionado no es válido.',
                'sexo.required'             => 'El sexo es obligatorio.',
                'sexo.enum'                 => 'El sexo seleccionado no es válido.',
                'direccion.required'        => 'La dirección es obligatoria.',
            ]
        );
    }
    private function ValidarActualizar(Request $request): void
    {
        $request->validate(
            [
                'identificacion'   => 'required|string|min:10',
                'nombreUsuario'   => 'required|string',
                'nombres'          => 'required|string|max:100',
                'apellidos'        => 'required|string|max:100',
                'fechaNacimiento' => 'required|date|before_or_equal:today',
                'celular'          => 'required|regex:/^[0-9]{10,}$/',
                'telefono'         => 'nullable|regex:/^[0-9]{7,}$/',
                'correo'           => 'required|email',
                'estadoCivil'     => ['required', new Enum(EstadoCivilEnum::class)],
                'sexo'             => ['required', new Enum(SexoEnum::class)],
                'direccion'        => 'required|string',
            ],
            [
                'identificacion.required'   => 'La identificación es obligatoria.',
                'identificacion.min'        => 'La identificación debe tener al menos 10 caracteres.',
                'nombreUsuario.required'   => 'El nombre de usuario es obligatorio.',
                'nombres.required'          => 'Los nombres son obligatorios.',
                'nombres.max'               => 'Los nombres no pueden exceder 100 caracteres.',
                'apellidos.required'        => 'Los apellidos son obligatorios.',
                'apellidos.max'             => 'Los apellidos no pueden exceder 100 caracteres.',
                'fechaNacimiento.required' => 'La fecha de nacimiento es obligatoria.',
                'fechaNacimiento.date'     => 'La fecha de nacimiento debe ser una fecha válida.',
                'fechaNacimiento.before_or_equal' => 'La fecha de nacimiento no puede ser futura.',
                'celular.required'          => 'El número de celular es obligatorio.',
                'celular.regex'             => 'El celular debe ser numérico y tener al menos 10 dígitos.',
                'telefono.regex'            => 'El teléfono debe ser numérico y tener al menos 7 dígitos.',
                'correo.required'           => 'El correo electrónico es obligatorio.',
                'correo.email'              => 'El correo debe ser una dirección válida.',
                'estadoCivil.required'     => 'El estado civil es obligatorio.',
                'estadoCivil.enum'         => 'El estado civil seleccionado no es válido.',
                'sexo.required'             => 'El sexo es obligatorio.',
                'sexo.enum'                 => 'El sexo seleccionado no es válido.',
                'direccion.required'        => 'La dirección es obligatoria.',
            ]
        );
    }
}


