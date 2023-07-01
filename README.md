# _Tecnologías de virtualización_
# _Eva 2.3_
# _Adil Villablanca Cuevas_
-------------------------------------------
_Tecnologías implementadas_

1.ECS
2.Docker
3.ECR
4.VPC
5.Application Load Balancer
6.Security Group
7.RDS Aurora MySQL
8.Git

-----------------------------------------

![N|Solid](https://www.computerhope.com/jargon/d/docker.jpg)

# Es necesario clonar repositorio desde github.com, para comenzar con la implementación.

_git clone https://github.com/adilvillablanca/adildocker.git_

# luego se debe ir a la carpeta donde se encuentra alojado el repo usando el comando cd en consola.

_cd wordpress/_

# A continuación es necesario empaquetizar contenedor y crear imagen con el siguiente comando.

_docker build  -t dockeradil ._

# Es imprescindible antes de correr el contenedor ir hacia el grupo de seguridad del EC2 y editar reglas de entrada.

_Se debe, agregar bajo el protocolo tcp puerto 80 en origen y elegir anywhere ipv4_

# Para avanzar en el procedimiento y correr el contenedor en --name= podemos elegir el nombre a eleccion. 

_docker run -d -p 80:80 --name=nombrecontenedor nombreimagen_


-------------------------------------------------------
# Ahora debemos crear una base de datos Mysql en amazon AWS.

1. _Primero ir Amazon RDS._

2. _Debemos hacer click en crear base de datos._

3. _Elegir Creación estándar._

4. _El tipo de motor elegir debe ser Aurora(MySQL Compatible)._

5. _En plantillas utilizar desarrollo y pruebas._

6. _Luego en el Identificador del clúster de base de datos debemos escribir un nombre a nuetra elección._

7. _En credenciales elegir el nombre de usuario maestro._

8. _La contraseña maestra a crear debe contener a lo menos 8 caracteres._

9. _Al crear el cluster storage se debe seleccionar Aurora Standard._

10. _Cuando configuremos la instancia elegiremos las Clases con ráfagas y seleccionaremos la que creamos más acorde, un abuena opción es la small, es una bd con 2GB de memoria ram, suficiente para una web estándar con poca exigencia. _

11. _Para lograr una buena resiliencia de la infraestructura, se debe crear nodo si no es el caso aplicado y lo que se busca es un ambiente de desarrollo, elegir No crear una réplica de Aurora._

12. _En Conectividad  se debe elegir "conectarse a un recurso informático de EC2", esto lo haremos con la finalidad de establecer una conexión con la instancia ec2 y el contenedor (Todas las demás opciones se deben dejar por defecto)._

13. _En Autenticación de bases de datos elegir "Autenticación con contraseña."_

14. _dejar todo lo demás por defecto y hacer click en crear base de datos._

-------------------------------------

# Instalar Git en EC2

_sudo yum install git_

# Instalar Mysql en instancia EC2. 

_sudo yum install mariadb105-server-utils.x86_64_

# Conectarse a Mysql aurora para otorgar privilegios al usuario en esa BD que implementamos.

_mysql -h puntodeconexioninstancia -P 3306 -u admin -p_

# Para mostrar la base de datos

_show databases;_

# Crear base de datos con el siguiente comando

_create database 'nombre'_

# Dar privilegios al usuario admin en la base de datos

_GRANT ALL PRIVILEGES ON wordpress.* TO admin_

# confirmar los cambios de privilegios

_FLUSH PRIVILEGES;_

--------------------------------------------
# Recomendación para crear los Grupos de Seguridad

_En primer lugar crear 2 grupos para lograr conectar la instancia EC2 con el RDS_

ec2-rds-1 
Salida Tipo MYSQL Aurora Protocolo TCP Intervalo de puertos 3306 Origen SG tareas

rds-ec2-1
Entrada Tipo MYSQL Aurora Protocolo TCP Intervalo de puertos 3306 Origen SG tareas

Crear un tercer y cuarto Grupos de seguridad, para el balanceador de carga y otro para las tareas "Contenedores"

ALB-SG 

Entrada Tipo HTTP TCP Intervalo de puertos 80 Origen Anywhere IPV4.

Salida Tipo personalizado TCP Intervalo de puertos 8001
Destino Security Group Tareas.

Crear un último Grupo de Seguridad.

TASK-SG

entrada Tipo HTTP TCP Intervalo de puertos 80 Origen Anywhere IPV4

Entrada Tipo personalizado TCP Intervalo de puertos 8001
Origen Red interna vpc

Entrada Tipo HTTPS TCP Intervalo de puertos 443
Origen Anywhere IPV4

Entrada Tipo HTTP TCP Intervalo de puertos 80
Origen Red interna VPC

Salida Tipo MYSQLAURORA TCP Intervalo de puertos 3306
Destino Security Group ec2-rds-1

-------------------------------------------------------

# Archivo Dockerfile y wp_config.php aplicar nuestras preferencias con vim.

ENV	                 --------------------------------------------------- STRING.

WORDPRESS_DB_NAME    ----------------------   Nombre base de datos que creamos.

WORDPRESS_DB_USER	----------------------------------- admin(o usuario que le otorgamos permisos en la base de datos).

WORDPRESS_DB_PASSWORD------------------------------------	contraseña maestra mysql o del usuario mysql.

WORDPRESS_DB_HOST     ---------------------------------- 	Punto de conexion instancia RDS.


# Editar wp-config.php.

ENV---------------------------------------------------	STRING

define('DB_NAME',----------------------------------	'Nombre base de datos que creamos'

define('DB_USER',-------------------------------------	'admin(o usuario que le otorgamos permisos en la base de datos)'

define('DB_PASSWORD',----------------------------------------------	'contraseña maestra mysql o del usuario mysql'

define('DB_HOST',-------------------------------------------	'Punto de conexión instancia RDS'

-------------------------------------------------------

# Repositorio ECR

Privado
Nombre del repositorio

# Cree una imagen de Docker

1. docker build -t wordpress .

2. docker tag wordpress:latest 773425074112.dkr.ecr.us-east-1.amazonaws.com/wordpress:latest

3. docker push 773425074112.dkr.ecr.us-east-1.amazonaws.com/wordpress:latest

# En el ECS debemos crear una definición de tarea, para luego crear el cluster con esa imagen de nuestro repositorio.

1_Crear una nueva definición de tarea

2_Familia de definición de tareas Especifique un nombre 3_de familia de definición de tarea único.

4_Nombre y la uri que se copia del repo que creamos

5_Mapeos de puertos es el puerto 80 HTTP

5_click en Siguiente

6_Entorno de la aplicación Elegimos AWS FARGATE

7_Sistema operativo/arquitectura Linux

8_Tamaño de la tarea 2 vCPU y 4 GB de memoria

9_Rol de tarea elegimos un rol con permisos 

10_Rol de ejecución de tareas 

11_Almacenamiento efímero 30 GB
Creamos

-------------------------------------------------------

# Crear Cluster

1_Nombre del clúster
2_Redes elegimos todas
3_Creamos

-------------------------------------------------------

# Crear Servicio

1_Opciones informáticas Estrategia de proveedor de capacidad

2_Configuración de implementación Servicio

3_Familia elegimos nuestra tarea y la versión

4_Nombre del servicio

5_Tipo de servicio Réplica Tareas deseadas 1

6_Redes Subredes todas

7_Grupo de seguridad launch-wizard-1, task-sg, rds-ec2-1, ec2-rds-1

8_Balanceo de carga

9_Balanceador de carga de aplicaciones

10_Crear un nuevo balanceador de carga

11_Nombre del balanceador de carga

12_Crear nuevo agente de escucha puerto 80 http

13_Grupo de destino Crear nuevo grupo de destino elegir el nombre

14_Crear servicio

15_Cuando se inicie el balanceador de carga debemos cambiar su security group por el de ALB-SG que tiene la regla de tráfico

# Verificar el correcto funcionamiento debemos esperar a que se termine el cloudformation y copiar el DNS de nuestro load balancer para probar el sitio wordpress


.us-east-1.elb.amazonaws.com:80






















.





