import './App.css'
import '@fontsource/roboto/300.css';
import '@fontsource/roboto/400.css';
import '@fontsource/roboto/500.css';
import '@fontsource/roboto/700.css';
import NavBar from './Components/NavBar';
import { Button, Typography } from '@mui/material';
import TableUsuarios from './Components/TablaUsuarios';
import ModalUsuario from './Components/ModalUsuario';
import { useEffect, useState } from 'react';
import { Usuario } from './Models/Usuario';
import { getUsuarios } from './Hooks/UsuariosHook';


function App() {
  const [isOpenModal, setOpenModal] = useState(false);
  const [usuarios, setUsuarios] = useState<Usuario[]>([Usuario.usuarioNuevo()]);
  const [usuarioSeleccionado, setUsuarioSeleccionado] = useState<Usuario>(Usuario.usuarioNuevo());
  const [funcionModal, setFuncionModal] = useState<string>("EDITAR")
  useEffect(() => {
    async function fetchUsuarios() {
      try {
        const usuarios = await getUsuarios();
        setUsuarios(usuarios);
      } catch (error) {
        console.error('Error fetching usuarios:', error);
      }
    }
    fetchUsuarios();
  }, [isOpenModal]);
  return (
    <>
      <div className='mainContainer'>
        <NavBar />
        <div className='titleContainer'>
          <Typography variant="h2" component="div" fontFamily={['roboto']}>
            Usuarios
          </Typography>
          <Button onClick={() => {setUsuarioSeleccionado(Usuario.usuarioNuevo()); 
                                  setOpenModal(true)} 
                          } 
            variant="contained" 
            sx={{ bgcolor: '#f0f2f5', color:'#111418'}}>Agregar</Button>
        </div>
        <div className='tableContainer'>
          <TableUsuarios rows={usuarios} setOpenModal={setOpenModal} setUsuarioSeleccionado={setUsuarioSeleccionado}/>
        </div>
      </div>
      <ModalUsuario isOpenModal={isOpenModal} 
                    setOpenModal={setOpenModal}
                    usuarioSeleccionado={usuarioSeleccionado}
                    setUsuarioSeleccionado={setUsuarioSeleccionado}
                    funcion={funcionModal}/>
    </>
  )
}

export default App
