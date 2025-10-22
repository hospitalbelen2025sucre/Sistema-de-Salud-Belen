# Sistema de Salud Integral BEL�N

## Descripci�n
Sistema de gesti�n de salud con XAMPP que incluye agendamiento de citas, gesti�n de resultados PAP, sistema de reclamos y panel administrativo.

## Estructura del Proyecto
\\\
sistema-salud-xampp/
+-- includes/
�   +-- db.php (Configuraci�n de base de datos)
+-- admin/
�   +-- auth.php (Autenticaci�n administrativa)
+-- citas.php (Agendamiento de citas)
+-- login.php (Inicio de sesi�n)
+-- logout.php (Cierre de sesi�n)
+-- healthcare-system.html (P�gina principal)
+-- styles.css (Estilos)
+-- database.sql (Script de base de datos)
+-- README.md (Esta documentaci�n)
\\\

## Requisitos
- XAMPP (Apache, MySQL, PHP)
- Navegador web

## Instalaci�n
1. Copiar la carpeta del proyecto a htdocs de XAMPP
2. Iniciar Apache y MySQL en el panel de control de XAMPP
3. Crear la base de datos ejecutando el script database.sql
4. Acceder a http://localhost/sistema-salud-xampp/

## Credenciales por Defecto
- Usuario: admin
- Contrase�a: medico2024

## Funcionalidades
- Agendamiento de citas m�dicas
- Gesti�n de resultados PAP
- Sistema de autenticaci�n
- Panel administrativo

