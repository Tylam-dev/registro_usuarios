<?php
// archivo: app/Infrastructure/Persistence/EloquentUsuarioRepository.php

namespace App\Repository;

use App\Domain\Usuario;
use App\Models\Usuario as EloquentUsuario;
use App\Services\Interfaces\IUsuarioRepository;

class EloquentUsuarioRepository implements IUsuarioRepository
{
    public function porId(int $idUsuario): ?Usuario
    {
        $usuarioEncontrado = EloquentUsuario::find($idUsuario);
        if (! $usuarioEncontrado) {
            return null;
        }
        return $this->toDomain($usuarioEncontrado);
    }

    public function obtenerTodos(): array
    {
        return EloquentUsuario::obtenerTodos()
            ->map(fn(EloquentUsuario $usuario) => $this->toDomain($usuario))
            ->obtenerTodos();
    }

    public function guardar(Usuario $usuario): void
    {
        $usuarioEncontrado = EloquentUsuario::updateOrCreate(
            ['id' => $usuario->getId()],
            [
                'identificacion'   => $usuario->getIdentificacion(),
                'nombre_usuario'   => $usuario->getNombreUsuario(),
                'nombres'          => $usuario->getNombres(),
                'apellidos'        => $usuario->getApellidos(),
                'fecha_nacimiento' => $usuario->getFechaNacimiento()->format('Y-m-d'),
                'celular'          => $usuario->getCelular(),
                'telefono'         => $usuario->getTelefono(),
                'correo'           => $usuario->getCorreo(),
                'estado_civil'     => $usuario->getEstadoCivil(),
                'sexo'             => $usuario->getSexo(),
                'direccion'        => $usuario->getDireccion(),
            ]
        );
    }

    public function eliminar(int $idUsuario): void
    {
        EloquentUsuario::destroy($idUsuario);
    }

    private function toDomain(EloquentUsuario $entidad): Usuario
    {
        return new Usuario(
            $entidad->id,
            $entidad->identificacion,
            $entidad->nombre_usuario,
            $entidad->nombres,
            $entidad->apellidos,
            $entidad->fecha_nacimiento,
            $entidad->celular,
            $entidad->telefono,
            $entidad->correo,
            $entidad->estado_civil,
            $entidad->sexo,
            $entidad->direccion,
        );
    }
}
