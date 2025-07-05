export class Usuario {
    public id: number;
    public identificacion: string;
    public nombreUsuario: string;
    public nombres: string;  
    public apellidos: string;
    public correo: string;
    public telefono: string;
    public fechaNacimiento: Date;
    public sexo: string;
    public direccion: string;
    public celular: string;
    public estadoCivil: string;
    constructor(
        id: number,
        identificacion: string,
        nombreUsuario: string,
        nombres: string,
        apellidos: string,
        correo: string,
        telefono: string,
        fechaNacimiento: Date,
        sexo: string,
        direccion: string,
        celular: string,
        estadoCivil: string,
    )
    {
        this.id = id;
        this.identificacion = identificacion;
        this.nombreUsuario = nombreUsuario;
        this.nombres= nombres;
        this.apellidos = apellidos;
        this.correo = correo;
        this.telefono = telefono;
        this.fechaNacimiento = fechaNacimiento;
        this.sexo = sexo;
        this.direccion = direccion;
        this.celular = celular;
        this.estadoCivil = estadoCivil;
    }
    public static usuarioNuevo(): Usuario {
        return new Usuario(
            0,                                 // id
            '',                                // identificacion
            '',                                // nombreUsuario
            '',                                // nombres
            '',                                // apellidos
            '',                                // correo
            '',                                // telefono
            new Date(),                       // fechaNacimiento
            'M',                                // sexo
            '',                                // direccion
            '',                                // celular
            ''                                 // estadoCivil
        );
    }
}