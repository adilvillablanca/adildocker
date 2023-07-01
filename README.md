# adildocker

#Contraseña Base Datos: Duoc.2023

# Tecnologias de virtualización
## _Eva 2.3_

![N|Solid](https://www.computerhope.com/jargon/d/docker.jpg)

# Es necesario clonar repositorio desde github.com, para comenzar con la implemntacion.

git clone https://github.com/adilvillablanca/adildocker.git

# luego se debe ir a la carpeta donde se encuentra alojado el repo usando el comando cd en consola.

cd wordpress/


# A continuacion es necesario empaquetizar contenedor y crear imagen con el suiguiente comando.

docker build  -t dockeradil .

#Es imprecindible antes de correr el contenedor ir hacia el grupo de seguridad del EC2 y editar reglas de entrada.

1. Se debe, agregar bajo el protocolo tcp puerto 80 en origen y elegir anywhere ipv4

# Para avanzar en el procedimiento y correr el contenedor en --name= podemos elegir el nombre a eleccion.

docker run -d -p 80:80 --name=nombrecontenedor nombreimagen

# Ahora debemos crear una base de datos mysql en amazon AWS

1. Primero ir Amazon RDS.

2. Debemos hacer click en crear base de datos.

3. Elegir Creación estándar.

4. El tipo de motor elegir debe ser Aurora(MySQL Compatible).

5. En plantillas utilizar desarrollo y pruebas.

6. Luego en el Identificador del clúster de base de datos debemos escribir un nombre a nuetra elección.

7. En credenciales elegir el nombre de usuario maestro

8. La contraseña maestra a crear debe contener a lo menos 8 caracteres

9. Al crear el cluster storage se debe seleccionar Aurora Standard

10. Cuando configuremos la instancia elegiremos las Clases con ráfagas y selecionaremos la que creamos mas acorde, un abuena opcion es la small, es una bd con 2GB de memoria ram, suficiente para una web estandar con poca exigencia 

11. Para lograr una buena recilencia de la infraestructura, se debe crear nodo si no es el caso aplicado y lo que se busca es un ambiente de desarrollo, elegir No crear una péplica de Aurora.

12. En Conectividad  se debe elegir "conectarse a un recurso informático de EC2", esto lo ahremos con la finalidad de establecer una conexion con la instancia ec2 y el contenedor (Todas las demas opciones se deben dejar por defecto)

13. En Autenticación de bases de datos elegir "Autenticación con contraseña"

14. dejar todo lo demas por defecto y hacer click en crear base de datos

# Instalar Mysql en instancia EC2, para ejecutar comandos mysql.

sudo yum install mariadb105-server-utils.x86_64

# Conectarse a Mysql aurora para otorgar privilegios al usuario en esa BD que implementamos.

mysql -h puntodeconexioninstancia -P 3306 -u admin -p

# Para mostrar la base de datos

show databases;

# Dar privilegios al usuario admin en la base de datos

GRANT ALL PRIVILEGES ON wordpress.* TO admin

"confirmar los cambios de privilegios#

FLUSH PRIVILEGES;

# Para conectar la base de datos hacia el contenedor es necesario abrir la pagina de Wordpress con la ip publica de nuestro servidor ec2, donde esta el Docker y es necesario añadir  elpuerto 80 al final la URL.

1. Elegir idioma de instalacion

2. Dar los strings de conexiones que solicita Wordpress

3. Para obtener el nombre de BD es necesario adquirlo de los pasos anteriores, segun se haya elegido.

4. El nombre de usuario es el usuario maestro entonces es admin y su password.

5. Es requesito modificar el locahost, por el punto de conexión de la instancia de BD.

6. A continuacion nos dirigimos a Amazon RDS y en bases de datos seleccionamos la instancia.

7. en el punto de enlace y puerto copiamos solo el punto de enlace que es algo asi nombreinstancia.cdufmw0hwyde.us-east-1.rds.amazonaws.com

# Con estos pasos se logra implementar Wordpress
.




