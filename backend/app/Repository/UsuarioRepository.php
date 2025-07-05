<?php
// archivo: app/Infrastructure/Persistence/EloquentUsuarioRepository.php

namespace App\Repository;

use App\Domain\Usuario\{Usuario};
use App\Models\Usuario as EloquentUsuario;

interface UsuarioRepository
{
    public function porId(int $idUsuario): ?Usuario;
    public function obtenerTodos(): array;               
    public function guardar(Usuario $usuario): void;
    public function eliminar(int $id): void;
}

class EloquentUsuarioRepository implements UsuarioRepository
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
            ['id' => $usuario->id()],
            [
                'identificacion'   => $usuario->identificacion(),
                'nombre_usuario'   => $usuario->nombreUsuario(),
                'nombres'          => $usuario->nombres(),
                'apellidos'        => $usuario->apellidos(),
                'fecha_nacimiento' => $usuario->fechaNacimiento()->format('Y-m-d'),
                'celular'          => $usuario->celular(),
                'telefono'         => $usuario->telefono(),
                'correo'           => $usuario->correo(),
                'estado_civil'     => $usuario->estadoCivil(),
                'sexo'             => $usuario->sexo(),
                'direccion'        => $usuario->direccion(),
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
