<?php
namespace App\Service;

use App\Service\Interfaces\IUsuarioRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Domain\Usuario;


class UsuarioService
{
    private IUsuarioRepository $repo;

    public function __construct(IUsuarioRepository $repo)
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
        $this->ValidarUnicidad($usuario);
        // Validacion de fecha mayor de edad
        $edad = $usuario->getFechaNacimiento()->diff(now())->y;
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
        Usuario $usuario
    ): Usuario {
        $this->ValidarUnicidad($usuario);
        $usuarioExistente = $this->repo->porId($usuario->getId());
        if (! $usuarioExistente) {
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

    private function ValidarUnicidad(
        Usuario $usuario
    ): void {
        $usuarioExistente = null;
        $usuarioExistente = $this->repo->obenerPorIdentificacion($usuario->getIdentificacion());
        if ($usuarioExistente !== null && $usuarioExistente->getId() !== $usuario->getId()) {
            throw ValidationException::withMessages([
                'identificacion' => 'Ya existe un usuario con esta identificacion.',
            ]);
        }
        $usuarioExistente = $this->repo->obtenerPorCorreo($usuario->getCorreo());
        if ($usuarioExistente !== null && $usuarioExistente->getId() !== $usuario->getId()) {
            throw ValidationException::withMessages([
                'correo' => 'Ya existe un usuario con este correo electronico.',
            ]);
        }
        $usuarioExistente = $this->repo->obtenerPorNombreUsuario($usuario->getNombreUsuario());
        if ($usuarioExistente !== null && $usuarioExistente->getId() !== $usuario->getId()) {
            throw ValidationException::withMessages([
                'nombre_usuario' => 'Ya existe un usuario con este nombre de usuario.',
            ]);
        }
    }
}