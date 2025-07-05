<?php
// archivo: app/Domain/Usuario/Usuario.php

namespace App\Domain\Usuario;
use App\Domain\EstadoCivilEnum;
use App\Domain\SexoEnum;
use DateTimeImmutable;

final class Usuario
{
    private int $id;
    private string $identificacion;
    private string $nombreUsuario;
    private string $nombres;
    private string $apellidos;
    private DateTimeImmutable $fechaNacimiento;
    private string $celular;
    private ?string $telefono;
    private string $correo;
    private EstadoCivil $estadoCivil;
    private SexoEnum $sexo;
    private string $direccion;

    public function __construct(
        int $id,
        string $identificacion,
        string $nombreUsuario,
        string $nombres,
        string $apellidos,
        DateTimeImmutable $fechaNacimiento,
        string $celular,
        ?string $telefono,
        string $correo,
        EstadoCivilEnum $estadoCivil,
        SexoEnum $sexo,
        string $direccion
    ) {
        $this->id                = $id;
        $this->identificacion    = $identificacion;
        $this->nombreUsuario     = $nombreUsuario;
        $this->nombres           = $nombres;
        $this->apellidos         = $apellidos;
        $this->fechaNacimiento   = $fechaNacimiento;
        $this->celular           = $celular;
        $this->telefono          = $telefono;
        $this->correo            = $correo;
        $this->estadoCivil       = $estadoCivil;
        $this->sexo              = $sexo;
        $this->direccion         = $direccion;
    }
}