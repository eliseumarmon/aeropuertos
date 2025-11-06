# **Proyecto: API RESTful para la Gestión de Conexiones Aéreas en Europa**

**Descripción General:**
Este proyecto tiene como objetivo crear una aplicación RESTful utilizando **Slim** que permita gestionar y consultar datos relacionados con ciudades y aeropuertos de Europa. La aplicación responderá a consultas sobre la disponibilidad de conexiones entre ciudades y sus respectivos aeropuertos. En las fases posteriores, se agregarán complejidades, como las conexiones directas e indirectas (con escalas).

## **Fases del Proyecto:**

### **Fase 1: Creación de la API con Persistencia**

**Objetivo:**
En esta primera fase, se creará una API RESTful básica que permita a los usuarios consultar información sobre ciudades y sus aeropuertos. Los datos serán almacenados de manera estática en una base de datos con **MySQL**.

**Tareas:**

1. **Definición de Estructura de Datos:**

   * **Ciudades**: nombre de la ciudad, país, etc.
   * **Aeropuertos**: nombre del aeropuerto, ciudad a la que pertenece, etc.
   * Relación entre ciudades y aeropuertos (Una ciudad puede tener varios aeropuertos).

2. **Desarrollo de la API:**

   * **Endpoints:**

     * `GET /cities`: Obtener un listado de todas las ciudades.
     * `GET /city/:id`: Obtener los detalles de una ciudad específica, incluyendo los aeropuertos que tiene.
     * `GET /airports`: Obtener un listado de todos los aeropuertos.
     * `GET /airport/:id`: Obtener los detalles de un aeropuerto específico.

3. **Persistencia de Datos:**

   * Los datos estarán guardados en la base de datos llamada **aeropuerto**, siguiendo la estructura de datos proporcionada en el punto 1.

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

### **Fase 4: Manipulación de Datos (CRUD)**

**Objetivo:**
En esta fase, se permitirá la creación, actualización y eliminación de aeropuertos y rutas (conexiones entre aeropuertos) mediante operaciones CRUD (Crear, Leer, Actualizar, Eliminar). Se implementarán las restricciones necesarias, como no poder eliminar un aeropuerto que tenga rutas asociadas. Para eliminar un aeropuerto, primero se deben eliminar sus rutas asociadas.

**Tareas:**

1. **Endpoints para CRUD de Aeropuertos:**

   * `POST /airports`: Crear un nuevo aeropuerto.
   * `PUT /airport/:id`: Actualizar los datos de un aeropuerto existente.
   * `DELETE /airport/:id`: Eliminar un aeropuerto, previa eliminación de sus rutas asociadas.

2. **Endpoints para CRUD de Rutas:**

   * `POST /connections`: Crear una nueva conexión directa entre dos aeropuertos.
   * `DELETE /connections/:id`: Eliminar una conexión entre aeropuertos.

3. **Restricciones en la Eliminación de Aeropuertos:**

   * **Validación al eliminar un aeropuerto**: No se podrá eliminar un aeropuerto que tenga rutas asociadas. Si un aeropuerto tiene rutas, se debe eliminar primero todas sus rutas (conexiones) antes de poder eliminar el aeropuerto.

4. **Persistencia:**

   * Las operaciones CRUD interactuarán directamente con la base de datos **MySQL**, permitiendo la creación, actualización, y eliminación de registros de aeropuertos y conexiones entre aeropuertos.

5. **Tecnologías a Usar:**

   * **PHP** con **Slim 4**.
   * **Persistencia**: **MySQL**.

---

#### **Requisitos Técnicos:**

1. **PHP** (versiones recientes).
2. **Slim 4** para la creación de la API.
3. **Persistencia en MySQL**.
4. Uso de **Postman** o **REST Client** para las pruebas de la API.

---

#### **Recomendaciones para la Implementación:**

* **Pruebas**: Realizar pruebas exhaustivas con herramientas como **Postman** o **REST Client** para asegurarse de que todos los endpoints funcionan correctamente.
* **Seguridad**: Asegúrate de implementar validaciones básicas.
* **Optimización**: Considera el uso de índices en la base de datos para mejorar la velocidad de las consultas, especialmente cuando trabajas con relaciones entre tablas.
