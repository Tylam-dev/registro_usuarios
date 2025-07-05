import type { Usuario } from "../Models/Usuario";

export async function getUsuarios(): Promise<Usuario[]> {
  try {
    const response = await fetch('http://localhost:8000/api/v1/usuarios');
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const text = await response.text();
    try {
      return JSON.parse(text) as Usuario[];
    } catch (parseError) {
      console.error('Invalid JSON response:', text);
      throw parseError;
    }
  } catch (error) {
    console.error('Error fetching usuarios:', error);
    throw error;
  }
}

export async function postUsuario(usuario: Usuario): Promise<Usuario> {
  try {
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
    try {
      return JSON.parse(text) as Usuario;
    } catch (parseError) {
      console.error('Invalid JSON response:', text);
      throw parseError;
    }
  } catch (error) {
    console.error('Error posting usuario:', error);
    throw error;
  }
}

export async function deleteUsuario(usuario: Usuario): Promise<Usuario> {
  try {
    const response = await fetch(`http://localhost:8000/api/v1/usuarios/${usuario.id}`, {
      method: 'DELETE'
    });
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const text = await response.text();
    try {
      return JSON.parse(text) as Usuario;
    } catch (parseError) {
      console.error('Invalid JSON response:', text);
      throw parseError;
    }
  } catch (error) {
    console.error('Error posting usuario:', error);
    throw error;
  }
}

export async function updateUsuario(usuario: Usuario): Promise<Usuario> {
  try {
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
    try {
      return JSON.parse(text) as Usuario;
    } catch (parseError) {
      console.error('Invalid JSON response:', text);
      throw parseError;
    }
  } catch (error) {
    console.error('Error posting usuario:', error);
    throw error;
  }
}