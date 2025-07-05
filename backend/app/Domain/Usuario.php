<?php
// archivo: app/Domain/Usuario/Usuario.php

namespace App\Domain;
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
    private EstadoCivilEnum $estadoCivil;
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
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getIdentificacion(): string
    {
        return $this->identificacion;
    }

    public function setIdentificacion(string $identificacion): void
    {
        $this->identificacion = $identificacion;
    }

    public function getNombreUsuario(): string
    {
        return $this->nombreUsuario;
    }

    public function setNombreUsuario(string $nombreUsuario): void
    {
        $this->nombreUsuario = $nombreUsuario;
    }

    public function getNombres(): string
    {
        return $this->nombres;
    }

    public function setNombres(string $nombres): void
    {
        $this->nombres = $nombres;
    }

    public function getApellidos(): string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    public function getFechaNacimiento(): DateTimeImmutable
    {
        return $this->fechaNacimiento;
    }

    public function setFechaNacimiento(DateTimeImmutable $fechaNacimiento): void
    {
        $this->fechaNacimiento = $fechaNacimiento;
    }

    public function getCelular(): string
    {
        return $this->celular;
    }

    public function setCelular(string $celular): void
    {
        $this->celular = $celular;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): void
    {
        $this->telefono = $telefono;
    }

    public function getCorreo(): string
    {
        return $this->correo;
    }

    public function setCorreo(string $correo): void
    {
        $this->correo = $correo;
    }

    public function getEstadoCivil(): EstadoCivilEnum
    {
        return $this->estadoCivil;
    }

    public function setEstadoCivil(EstadoCivilEnum $estadoCivil): void
    {
        $this->estadoCivil = $estadoCivil;
    }

    public function getSexo(): SexoEnum
    {
        return $this->sexo;
    }

    public function setSexo(SexoEnum $sexo): void
    {
        $this->sexo = $sexo;
    }

    public function getDireccion(): string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): void
    {
        $this->direccion = $direccion;
    }
}