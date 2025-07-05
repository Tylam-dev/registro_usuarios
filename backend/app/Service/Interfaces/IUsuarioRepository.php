<?php
namespace App\Service\Interfaces;

use App\Domain\Usuario;

interface IUsuarioRepository
{
    public function porId(int $idUsuario): ?Usuario;
    public function obtenerTodos(): array;               
    public function guardar(Usuario $usuario): void;
    public function eliminar(int $id): void;
    public function obenerPorIdentificacion(string $identificacion): ?Usuario;
}
