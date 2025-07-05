<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Domain\EstadoCivilEnum;
use App\Domain\SexoEnum;

class StoreUsuarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'identificacion'   => 'required|string|min:10|unique:usuarios,identificacion',
            'nombre_usuario'   => 'required|string|unique:usuarios,nombre_usuario',
            'nombres'          => 'required|string|max:100',
            'apellidos'        => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'celular'          => 'required|regex:/^[0-9]{10,}$/',
            'telefono'         => 'nullable|regex:/^[0-9]{7,}$/',
            'correo'           => 'required|email|unique:usuarios,correo',
            'estado_civil'     => ['required', new Enum(EstadoCivil::class)],
            'sexo'             => ['required', new Enum(SexoEnum::class)],
            'direccion'        => 'required|string',
        ];
    }
    public function messages(): array
    {
        return [
            'identificacion.required'    => 'La identificación es obligatoria.',
            'identificacion.unique'      => 'La identificación ya está registrada.',
            'identificacion.min'         => 'La identificación debe tener al menos 10 caracteres.',
            'nombre_usuario.required'    => 'El nombre de usuario es obligatorio.',
            'nombre_usuario.unique'      => 'El nombre de usuario ya está en uso.',
            'nombres.required'           => 'Los nombres son obligatorios.',
            'nombres.max'                => 'Los nombres no pueden exceder los 100 caracteres.',
            'apellidos.required'         => 'Los apellidos son obligatorios.',
            'apellidos.max'              => 'Los apellidos no pueden exceder los 100 caracteres.',
            'fecha_nacimiento.required'  => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date'      => 'La fecha de nacimiento debe ser una fecha válida.',
            'celular.required'           => 'El número de celular es obligatorio.',
            'celular.regex'              => 'El número de celular no tiene formato válido.',
            'telefono.regex'             => 'El número de telefono no tiene formato válido.',
            'correo.email'               => 'El correo debe tener un formato válido.',
            'correo.required'            => 'El correo electrónico es obligatorio.',
            'correo.unique'              => 'El correo electrónico ya está registrado.',
            'estado_civil.required'      => 'El estado civil es obligatorio.',
            'estado_civil.enum'          => 'El valor de estado civil no es válido.',
            'sexo.required'              => 'El sexo es obligatorio.',
            'sexo.enum'                  => 'El valor de sexo no es válido.',
            'direccion.required'         => 'La dirección es obligatoria.',
            'direccion.string'           => 'La dirección debe ser una cadena de texto.',
        ];
    }
        /**
     * Map validated data to the Domain Usuario object.
     */
    public function toDomain(): DomainUsuario
    {
        $request = $this->validated();

        return new DomainUsuario(
            0,
            $request['identificacion'],
            $request['nombre_usuario'],
            $request['nombres'],
            $request['apellidos'],
            new DateTimeImmutable($request['fecha_nacimiento']),
            $request['celular'],
            $request['telefono'] ?? null,
            $request['correo'],
            EstadoCivil::from($request['estado_civil']),
            Sexo::from($request['sexo']),
            $request['direccion']
        );
    }
}
