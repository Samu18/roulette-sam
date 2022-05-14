
# Juego de Ruleta

Es un juego donde varios participantes pueden jugar a la ruleta, teniendo como opciones de elegir los colores verde, rojo y negro. Cada Participante puede apostar dentro de un rango de porcentaje definido.



## Documentación

[Documentación](https://linktodocumentation)

Para la instalación del juego se debe tener en cuenta los siguientes pasos:

Paso 1. Tener instalado Docker, ref: https://docs.docker.com/desktop/

Paso 2. Clonar el proyecto en su entorno local.

Paso 3. Desplegar el contenedor de docker, con la configuración que trae el proyecto, ubicado desde la raiz del proyecto para ejecutar el comando.

$ docker compose up

Paso 4. Instalar las depencias del proyecto, ubicado desde la raiz del proyecto para ejecutar el comando.

$ composer install

Paso 5. Ejecutar la creación de la base de datos y sus respectivas tablas, ubicado desde la raiz del proyecto para ejecutar el comando.

$ php bin/console doctrine:database:create

$ php bin/console doctrine:schema:update --force

Paso 6. Crear registro de usuario admin para gestionar los jugadores, ubicado desde la raiz del proyecto para ejecutar el comando.

$ php bin/console doctrine:query:sql "INSERT INTO users VALUES (NULL, 'admin2@gmail.com', 'a:1:{i:0;s:10:\"ROLE_ADMIN\";}', '$2y$12$9R6EaE.CktGvj2k1ywekpOxPMWVTUTVUP4WK.NeaXaXqV2lfcHLBa', 'x', '1', '2022-05-12 11:43:08', NULL, NULL, NULL, NULL, 'admin')"

Paso 7. Entrar al proyecto por el navegador. 

* Ruta de gestion remota: http://3.23.21.49/roulette-sam/public/login 

* Ruta de gestion local: http://localhost:8081/roulette-sam/public/login 

    *Para ingresar en la gestión las credenciales son:*

    Usuario: admin | Contraseña: e#KvwC5E&EAE


* Ruta de juego remota: http://localhost:8081/roulette-sam/public

* Ruta de juego local: http://localhost:8081/roulette-sam/public

![App Screenshot](https://i.postimg.cc/nhV7JGQZ/game-roulette-sam.jpg)

## Autor
Samuel David Sánchez Vallejo <samuel.softdev@gmail.com>
- [@Samu18](https://github.com/Samu18)

