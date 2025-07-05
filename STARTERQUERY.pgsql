-- Active: 1751665944149@@localhost@5432@PruebaGF
-- 1. Definición de tipos ENUM (solo se crean si no existen)
DO $$
BEGIN
  IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'estado_civil') THEN
    CREATE TYPE estado_civil AS ENUM('soltero','casado','viudo','divorciado');
  END IF;
  IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'sexo') THEN
    CREATE TYPE sexo AS ENUM('M','F');
  END IF;
END;
$$;

-- 2. Creación de la tabla usuarios

CREATE TABLE IF NOT EXISTS usuarios (
  id                SERIAL PRIMARY KEY,
  identificacion    VARCHAR(20)   NOT NULL UNIQUE,
  nombre_usuario    VARCHAR(50)   NOT NULL UNIQUE,
  nombres           VARCHAR(100)  NOT NULL,
  apellidos         VARCHAR(100)  NOT NULL,
  fecha_nacimiento  DATE          NOT NULL,
  celular           VARCHAR(20)   NOT NULL,
  telefono          VARCHAR(20),
  correo            VARCHAR(100)  NOT NULL UNIQUE,
  estado_civil      estado_civil  NOT NULL,
  sexo              sexo          NOT NULL,
  direccion         TEXT          NOT NULL,
  created_at        TIMESTAMPTZ   NOT NULL DEFAULT NOW(),
  updated_at        TIMESTAMPTZ   NOT NULL DEFAULT NOW(),
  deleted_at        TIMESTAMPTZ
);

-- 3. Trigger para mantener updated_at automatizado

CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
  NEW.updated_at = NOW();
  RETURN NEW;
END;


DROP TRIGGER IF EXISTS set_timestamp ON usuarios;
CREATE TRIGGER set_timestamp
  BEFORE UPDATE ON usuarios
  FOR EACH ROW
  EXECUTE FUNCTION update_updated_at_column();


