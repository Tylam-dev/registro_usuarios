<?php
namespace App\Services;

use App\Domain\Usuario\Usuario;
use App\Repository\UsuarioRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UsuarioService
{
    private UsuarioRepository $repo;

    public function __construct(UsuarioRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Crea un nuevo usuario.
     *
     * @throws ValidationException
     */
    public function crear(
        Usuario $usuario
    ): Usuario {
        // Validaciones de fecha futura
        if ($fechaNacimiento > new \DateTimeImmutable()) {
            throw ValidationException::withMessages([
                'fecha_nacimiento' => 'La fecha de nacimiento no puede ser futura.',
            ]);
        }
        // Validacion de fecha mayor de edad
        $edad = $fechaNacimiento->diff($now)->y;
        if ($edad < 18) {
            throw ValidationException::withMessages([
                'fecha_nacimiento' => 'El usuario debe ser mayor de edad.',
            ]);
        }

        return DB::transaction(function () use (
            $usuario
        ) {
            $this->repo->guardar($usuario);

            return $usuario;
        });
    }

    public function obtenerPorId(int $id): ?Usuario
    {
        return $this->repo->porId($id);
    }

    /**
     * Lista todos los usuarios.
     *
     * @return Usuario[]
     */
    public function listar(): array
    {
        return $this->repo->obtenerTodos();
    }

    /**
     * Actualiza un usuario existente.
     *
     * @throws ValidationException
     */
    public function actualizar(
        int $id,
        array $datos
    ): Usuario {
        $usuario = $this->repo->porId($id);
        if (! $usuario) {
            throw ValidationException::withMessages(['id' => 'Usuario no encontrado.']);
        }
        $this->repo->guardar($usuario);

        return $usuario;
    }

    /**
     * Elimina un usuario.
     */
    public function eliminar(int $id): void
    {
        $this->repo->eliminar($id);
    }
}