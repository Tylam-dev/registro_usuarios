import * as React from 'react';
import Paper from '@mui/material/Paper';
import Table from '@mui/material/Table';
import TableBody from '@mui/material/TableBody';
import TableCell from '@mui/material/TableCell';
import TableContainer from '@mui/material/TableContainer';
import TableHead from '@mui/material/TableHead';
import TablePagination from '@mui/material/TablePagination';
import TableRow from '@mui/material/TableRow';
import { Usuario } from '../Models/Usuario';

interface Column {
  id: 'nombreUsuario' | 'apellidos' | 'identificacion' | 'size' | 'density';
  label: string;
  minWidth?: number;
  align?: 'right';
  format?: (value: number | Date ) => string;
}

const columns: readonly Column[] = [
  { id: 'nombreUsuario', label: 'Nombres', minWidth: 170 },
  { id: 'apellidos', label: 'Apellidos', minWidth: 100 },
  {
    id: 'identificacion',
    label: 'Identeficación',
    minWidth: 170,
    align: 'right'
  }
];

interface TableProps {
  rows: Usuario[];
  setUsuarioSeleccionado: React.Dispatch<React.SetStateAction<Usuario>>;
  setOpenModal: React.Dispatch<React.SetStateAction<boolean>>; // Optional prop for selected user
} 

export default function TableUsuarios({ rows, setOpenModal, setUsuarioSeleccionado }: TableProps) {
  const [page, setPage] = React.useState(0);
  const [rowsPerPage, setRowsPerPage] = React.useState(10);

  const handleChangePage = (event: unknown, newPage: number) => {
    setPage(newPage);
  };

  const handleChangeRowsPerPage = (event: React.ChangeEvent<HTMLInputElement>) => {
    setRowsPerPage(+event.target.value);
    setPage(0);
  };

  return (
    <Paper sx={{ width: '100%', overflow: 'hidden' }}>
      <TableContainer sx={{ maxHeight: 440 }}>
        <Table stickyHeader aria-label="sticky table">
          <TableHead>
            <TableRow>
              {columns.map((column) => (
                <TableCell
                  key={column.id}
                  align={column.align}
                  style={{ minWidth: column.minWidth }}
                >
                  {column.label}
                </TableCell>
              ))}
            </TableRow>
          </TableHead>
          <TableBody>
            {rows
              .slice(page * rowsPerPage, page * rowsPerPage + rowsPerPage)
              .map((row) => {
                return (
                  <TableRow hover role="checkbox" tabIndex={-1} key={row.id}>
                    {columns.map((column) => {
                      const value = row[column.id as keyof Usuario];
                      return (
                        <TableCell onClick={() => {setUsuarioSeleccionado(row);
                                                   setOpenModal(true)}
                                            } key={column.id} align={column.align}>
                          {value.toString()}
                        </TableCell>
                      );
                    })}
                  </TableRow>
                );
              })}
          </TableBody>
        </Table>
      </TableContainer>
      <TablePagination
        rowsPerPageOptions={[10, 25, 100]}
        component="div"
        count={rows.length}
        rowsPerPage={rowsPerPage}
        page={page}
        onPageChange={handleChangePage}
        onRowsPerPageChange={handleChangeRowsPerPage}
      />
    </Paper>
  );
}