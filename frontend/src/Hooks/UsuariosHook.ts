import type { Usuario } from "../Models/Usuario";

export async function getUsuarios(): Promise<Usuario[]> {
    const response = await fetch('http://localhost:8000/api/v1/usuarios');
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const text = await response.text();
    let raw = JSON.parse(text) as any[];
    const flat = raw.map(item => ({
      ...item,
      estadoCivil: item.estadoCivil?.value ?? '',
      sexo: item.sexo?.value ?? ''
    }));
    return flat as Usuario[];
}

export async function postUsuario(usuario: Usuario): Promise<Usuario> {
 const response = await fetch('http://localhost:8000/api/v1/usuarios', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(usuario),
    });
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const text = await response.text();
    return JSON.parse(text) as Usuario;
}

export async function deleteUsuario(usuario: Usuario) {
    const response = await fetch(`http://localhost:8000/api/v1/usuarios/${usuario.id}`, {
      method: 'DELETE'
    });
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
}

export async function updateUsuario(usuario: Usuario): Promise<Usuario> {
    const response = await fetch('http://localhost:8000/api/v1/usuarios', {
    method: 'PUT',
    headers: {
    'Content-Type': 'application/json',
    },
    body: JSON.stringify(usuario),
    });
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const text = await response.text();
    return JSON.parse(text) as Usuario;
}