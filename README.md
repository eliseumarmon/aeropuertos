# **Proyecto: API RESTful para la Gestión de Conexiones Aéreas en Europa**

**Descripción General:**
Este proyecto tiene como objetivo crear una aplicación RESTful utilizando **Slim** que permita gestionar y consultar datos relacionados con ciudades y aeropuertos de Europa. La aplicación responderá a consultas sobre la disponibilidad de conexiones entre ciudades y sus respectivos aeropuertos. En las fases posteriores, se agregarán complejidades, como las conexiones directas e indirectas (con escalas).

## **Fases del Proyecto:**

### **Fase 1: Creación de la API con Persistencia**

**Objetivo:**
En esta primera fase, se creará una API RESTful básica que permita a los usuarios consultar información sobre ciudades y sus aeropuertos. Los datos serán almacenados de manera estática en un archivo **JSON**.

**Tareas:**

1. **Definición de Estructura de Datos:**

   * **Ciudades**: nombre de la ciudad, país, etc.
   * **Aeropuertos**: nombre del aeropuerto, ciudad a la que pertenece, etc.
   * Relación entre ciudades y aeropuertos (un aeropuerto puede estar asociado a varias ciudades o una sola ciudad).

2. **Desarrollo de la API:**

   * **Endpoints:**

     * `GET /cities`: Obtener un listado de todas las ciudades.
     * `GET /city/:id`: Obtener los detalles de una ciudad específica, incluyendo los aeropuertos que tiene.
     * `GET /airports`: Obtener un listado de todos los aeropuertos.
     * `GET /airport/:id`: Obtener los detalles de un aeropuerto específico.

3. **Persistencia de Datos:**

   * Los datos estarán guardados en la base de datos llamada **aeropuertos**, siguiendo la estructura de datos proporcionada en el punto 1.

4. **Tecnologías a Usar:**

   * **PHP** con **Slim**.
   * **Persistencia**: MySQL.
   * **Documentación**: Postman para pruebas de la API.

### **Fase 2: Conexiones Directas entre Aeropuertos**

**Objetivo:**
En esta fase, se ampliará la funcionalidad para permitir consultar las conexiones directas entre aeropuertos. El sistema debe poder responder preguntas sobre si existe una conexión directa entre dos ciudades y qué aeropuertos están involucrados.

**Tareas:**

1. **Definición de Conexiones Directas:**

   * Se define que una "conexión directa" es aquella que existe entre dos aeropuertos sin escalas. Se debe asociar una conexión entre aeropuertos, y cada conexión deberá tener la ciudad de origen y la ciudad de destino.

2. **Desarrollo de Endpoints para Consultas:**

   * `GET /connections`: Obtener todas las conexiones directas entre aeropuertos.
   * `GET /connections/:from/:to`: Obtener si existe una conexión directa entre dos ciudades, y si existe, qué aeropuertos están involucrados.
   * `GET /airport/:id/connections`: Obtener todas las conexiones directas desde un aeropuerto.

3. **Persistencia:**

   * Las conexiones directas serán representadas como un conjunto de relaciones entre los aeropuertos (de ciudad a ciudad).

4. **Tecnologías a Usar:**

   * **PHP** con **Slim**.
   * **Persistencia**: MySQL.

### **Fase 3: Conexiones con Escalas (Conexiones Indirectas)**

**Objetivo:**
Añadir la capacidad para que la API pueda identificar rutas entre dos ciudades que involucren una o más escalas. Esto permite hacer consultas de rutas indirectas (con escalas), respondiendo a preguntas como "¿Cuál es la ruta más corta o con menos escalas entre dos ciudades?".

**Tareas:**

1. **Definición de Conexiones con Escalas:**

   * Se introducirá la posibilidad de que una conexión pase por varios aeropuertos antes de llegar a su destino final. Esto implica que una ruta entre dos ciudades puede ser de varias escalas.

2. **Desarrollo de Endpoints para Conexiones con Escalas:**

   * `GET /connections/with-stops/:from/:to`: Consultar las rutas entre dos ciudades que impliquen una o más escalas, mostrando los aeropuertos por los que pasa la conexión.
   * `GET /airport/:id/connections/with-stops`: Consultar todas las conexiones con escalas desde un aeropuerto.

3. **Lógica para Escalas:**

   * Se diseñará un algoritmo que permita identificar conexiones con escalas (por ejemplo, el algoritmo de **Búsqueda en Profundidad** o **Búsqueda en Anchura**).
   * Se manejarán las rutas y escalas de manera eficiente para optimizar las consultas.

4. **Persistencia:**

   * Para representar las conexiones con escalas, se actualizará la base de datos para incluir rutas entre aeropuertos y ciudades que involucren una o más escalas.

5. **Tecnologías a Usar:**

   * **PHP** con **Slim**.
   * **Persistencia**: MySQL
