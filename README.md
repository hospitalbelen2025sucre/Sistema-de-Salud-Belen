# Sistema de Salud Integral BELÉN

## Descripción
Sistema de gestión de salud con XAMPP que incluye agendamiento de citas, gestión de resultados PAP, sistema de reclamos y panel administrativo.

## Estructura del Proyecto
\\\
sistema-salud-xampp/
+-- includes/
¦   +-- db.php (Configuración de base de datos)
+-- admin/
¦   +-- auth.php (Autenticación administrativa)
+-- citas.php (Agendamiento de citas)
+-- login.php (Inicio de sesión)
+-- logout.php (Cierre de sesión)
+-- healthcare-system.html (Página principal)
+-- styles.css (Estilos)
+-- database.sql (Script de base de datos)
+-- README.md (Esta documentación)
\\\

## Requisitos
- XAMPP (Apache, MySQL, PHP)
- Navegador web

## Instalación
1. Copiar la carpeta del proyecto a htdocs de XAMPP
2. Iniciar Apache y MySQL en el panel de control de XAMPP
3. Crear la base de datos ejecutando el script database.sql
4. Acceder a http://localhost/sistema-salud-xampp/

## Credenciales por Defecto
- Usuario: admin
- Contraseña: medico2024

## Funcionalidades
- Agendamiento de citas médicas
- Gestión de resultados PAP
- Sistema de autenticación
- Panel administrativo

