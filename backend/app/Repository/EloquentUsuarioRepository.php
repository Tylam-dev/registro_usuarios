<?php

namespace App\Repository;

use App\Domain\Usuario;
use App\Models\Usuario as EloquentUsuario;
use App\Service\Interfaces\IUsuarioRepository;
use App\Domain\EstadoCivilEnum;
use App\Domain\SexoEnum;

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
        return EloquentUsuario::all()
            ->map(fn(EloquentUsuario $usuario) => $this->toDomain($usuario))
            ->all();
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
            $entidad->fecha_nacimiento->toDateTimeImmutable(),
            $entidad->celular,
            $entidad->telefono,
            $entidad->correo,
            EstadoCivilEnum::from($entidad->estado_civil),
            SexoEnum::from($entidad->sexo),
            $entidad->direccion,
        );
    }
    public function obenerPorIdentificacion(string $identificacion): ?Usuario
    {
        $usuarioEncontrado = EloquentUsuario::where('identificacion', $identificacion)->first();
        if (! $usuarioEncontrado) {
            return null;
        }
        return $this->toDomain($usuarioEncontrado);
    }
}
