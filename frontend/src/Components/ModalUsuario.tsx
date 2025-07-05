import * as React from 'react';
import TextField from '@mui/material/TextField';
import Grid from '@mui/material/Grid';
import Backdrop from '@mui/material/Backdrop';
import Box from '@mui/material/Box';
import Modal from '@mui/material/Modal';
import Fade from '@mui/material/Fade';
import Button from '@mui/material/Button';
import type { Usuario } from '../Models/Usuario';
import { deleteUsuario, postUsuario, updateUsuario } from '../Hooks/UsuariosHook';
import { FormControl, InputLabel, MenuItem, Select, Typography, type SelectChangeEvent } from '@mui/material';

const style = {
  position: 'absolute',
  top: '50%',
  left: '50%',
  transform: 'translate(-50%, -50%)',
  width: 600,
  bgcolor: 'background.paper',
  border: '2px solid #000',
  boxShadow: 24,
  p: 4,
};

interface ModalProps {
  isOpenModal: boolean;
  setOpenModal: React.Dispatch<React.SetStateAction<boolean>>;
  usuarioSeleccionado: Usuario;
  setUsuarioSeleccionado: React.Dispatch<React.SetStateAction<Usuario>>;
  funcion: string; 
}


export default function ModalUsuario({isOpenModal, setOpenModal, usuarioSeleccionado, setUsuarioSeleccionado, funcion}: ModalProps) {
  const handleClose = () => setOpenModal(false);

  const [mensajeError, setMensajeError] = React.useState<string>("")
  const handleChangeEstadoCivil = (e: SelectChangeEvent) => setUsuarioSeleccionado(prev => ({ ...prev, estadoCivil: e.target.value }))
  const handleChangeSexo = (e: SelectChangeEvent) =>  setUsuarioSeleccionado(prev => ({ ...prev, sexo: e.target.value }))
  const handleGuardar = async() => {
    try{
      if(funcion == "EDITAR"){
        await updateUsuario(usuarioSeleccionado)
      }else if(funcion == "CREAR"){
        await postUsuario(usuarioSeleccionado)
      }
      handleClose();
    }catch(error)
    {
      setMensajeError(`${error}`)
    }
  }
  const handleEliminar = async() => {
    try{
      await deleteUsuario(usuarioSeleccionado)
      handleClose();
    }catch(error)
    {
      setMensajeError(`${error}`)
    }
  }
  React.useEffect(() => {
      setMensajeError("")
    }, [isOpenModal]);
  return (
    <div>
      <Modal
        aria-labelledby="transition-modal-title"
        aria-describedby="transition-modal-description"
        open={isOpenModal}
        onClose={handleClose}
        closeAfterTransition
        slots={{ backdrop: Backdrop }}
        slotProps={{
          backdrop: {
            timeout: 500,
          },
        }}
      >
        <Fade in={isOpenModal}>
          <Box sx={style}>
            <form noValidate autoComplete="off" onSubmit={(e)=> e.preventDefault }>
              <Grid container spacing={3} flexDirection={"column"} alignContent={"space-around"}>
                <Grid container justifyContent={"center"}>
                  <Grid component={"div"}>
                    <TextField
                      fullWidth
                      label="Identificación"
                      value={usuarioSeleccionado.identificacion}
                      onChange={e => setUsuarioSeleccionado(prev => ({ ...prev, identificacion: e.target.value }))}
                    />
                  </Grid>
                  <Grid component={"div"}>
                    <TextField
                      fullWidth
                      label="Nombre de Usuario"
                      value={usuarioSeleccionado.nombreUsuario}
                      onChange={e => setUsuarioSeleccionado(prev => ({ ...prev, nombreUsuario: e.target.value }))}
                    />
                  </Grid>
                </Grid>
                <Grid container justifyContent={"center"}>
                  <Grid component={"div"}>
                    <TextField
                      fullWidth
                      label="Nombres"
                      value={usuarioSeleccionado.nombres}
                      onChange={e => setUsuarioSeleccionado(prev => ({ ...prev, nombres: e.target.value }))}
                    />
                  </Grid>
                  <Grid component={"div"}>
                    <TextField
                      fullWidth
                      label="Apellidos"
                      value={usuarioSeleccionado.apellidos}
                      onChange={e => setUsuarioSeleccionado(prev => ({ ...prev, apellidos: e.target.value }))}
                    />
                  </Grid>
                </Grid>
                <Grid container justifyContent={"center"}>
                  <Grid component={"div"}>
                    <TextField
                      fullWidth
                      label="Correo"
                      value={usuarioSeleccionado.correo}
                      onChange={e => setUsuarioSeleccionado(prev => ({ ...prev, correo: e.target.value }))}
                    />
                  </Grid>
                  <Grid component={"div"}>
                    <TextField
                      fullWidth
                      label="Teléfono"
                      value={usuarioSeleccionado.telefono}
                      onChange={e => setUsuarioSeleccionado(prev => ({ ...prev, telefono: e.target.value }))}
                    />
                  </Grid>
                </Grid>
                <Grid container justifyContent={"center"}>
                  <Grid component={"div"}>
                    <TextField
                      fullWidth
                      label="Dirección"
                      value={usuarioSeleccionado.direccion}
                      onChange={e => setUsuarioSeleccionado(prev => ({ ...prev, direccion: e.target.value }))}
                    />
                  </Grid>
                  <Grid component={"div"}>
                    <TextField
                      fullWidth
                      label="Celular"
                      value={usuarioSeleccionado.celular}
                      onChange={e => setUsuarioSeleccionado(prev => ({ ...prev, celular: e.target.value }))}
                    />
                  </Grid>
                </Grid>
                <Grid container justifyContent={"space-between"}>
                  <Grid component={"div"} width={140}>
                    <FormControl fullWidth>
                      <InputLabel id="demo-simple-select-label">Estado Civil</InputLabel>
                      <Select
                        labelId="demo-simple-select-label"
                        id="demo-simple-select"
                        value={usuarioSeleccionado.estadoCivil}
                        label="Estado Civil"
                        onChange={handleChangeEstadoCivil}
                      >
                        <MenuItem value={"soltero"}>Soltero</MenuItem>
                        <MenuItem value={"casado"}>Casado</MenuItem>
                        <MenuItem value={"divorciado"}>Divorciado</MenuItem>
                        <MenuItem value={"viudo"}>Viudo</MenuItem>
                      </Select>
                    </FormControl>
                  </Grid>
                  <Grid component={"div"}>
                    <TextField
                      fullWidth
                      label="Fecha de Nacimiento"
                      type="date"
                      value={usuarioSeleccionado.fechaNacimiento instanceof Date ? usuarioSeleccionado.fechaNacimiento.toISOString().substring(0,10) : ''}
                      onChange={e => setUsuarioSeleccionado(prev => ({ ...prev, fechaNacimiento: new Date(e.target.value) }))}
                    />
                  </Grid>
                  <Grid component={"div"}>
                    <FormControl fullWidth>
                      <InputLabel id="demo-simple-select-label">Sexo</InputLabel>
                      <Select
                        labelId="demo-simple-select-label"
                        id="demo-simple-select"
                        value={usuarioSeleccionado.sexo}
                        label="Sexo"
                        onChange={handleChangeSexo}
                      >
                        <MenuItem value={"F"}>Femenino</MenuItem>
                        <MenuItem value={"M"}>Masculino</MenuItem>
                      </Select>
                    </FormControl>
                  </Grid>
                </Grid>
              </Grid>
              <Grid component={"div"} textAlign={"center"}>
                <Typography color='red'>{mensajeError}</Typography>
              </Grid>
                <Grid component={"div"} >
                  <Box sx={{ display: 'flex', justifyContent: 'flex-end', mt: 2, gap:2 }}>
                    <Button onClick={handleClose} sx={{ color:'#111418'}}>Cancelar</Button>
                    <Button variant="contained" 
                            sx={{ bgcolor: '#f0f2f5', color:'#111418'}} 
                            onClick={handleGuardar}>
                      Guardar
                    </Button>
                    {funcion == "EDITAR" 
                      && 
                      <Button variant="contained" 
                              color={"error"} 
                              onClick={handleEliminar}>
                        Eliminar
                      </Button>
                    }
                  </Box>
                </Grid>
            </form>
          </Box>
        </Fade>
      </Modal>
    </div>
  );
}