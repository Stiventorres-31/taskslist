# Prueba Técnica - Sistema de Gestión de Tareas

## Descripción del Proyecto

Este proyecto es un sistema básico de gestión de tareas desarrollado con Laravel y Vue.js. El objetivo de esta prueba técnica es identificar y corregir errores en el código tanto en el backend como en el frontend. El sistema permite a los usuarios crear, actualizar, eliminar y visualizar tareas.

## Objetivo de la Prueba

El objetivo principal de esta prueba es evaluar tus habilidades para depurar y corregir errores en un proyecto existente que utiliza Laravel, PHP, JavaScript, y Vue.js. Deberás:

- Identificar y corregir errores en el backend relacionado con la creación, actualización, eliminación y validación de tareas.
- Corregir problemas en el frontend que afectan la visualización y manejo de la lista de tareas.
- Asegurarte de que las tareas se gestionen correctamente en la interfaz de usuario.

Además, deberás agregar una funcionalidad extra para filtrar las tareas completadas y pendientes.


**Errores encontrado:**

- Al momento de crear una tarea, no se creaba porque el nombre de la tabla que estaba definida en el back-end no coinicidia con el que estaba en la base de datos.

- Cuando se iba a eliminar una tarea, no se podia porque no se cargaban los datos que fueron insertado. La solucion fue retormar los datos que se iban insertando en el back-end y asi poder obtener la informacion y el id, que el id era el mayor problema.


- Al momento de completar una tarea no funcionaba porque no encontraba la action. la solucion fue crear la action con el endpoint en el back-end y asi se pudo realizar las peticiones para actualizar el estado de la tarea.

- No se estaban mostrando los errores de validacion de campos que vienen del back-end. Se definieron variables de estado para recibir los errores y mostrarlo en formato de lista en el front-end

- En el component se estaba definiendo una action llamada fetchTasks pero no existia. Se desarrollo por completo la function y el endpoint para poder obtener los datos de la base de datos y se fueron almacenando el la variable tasks

- Se terminó de desarrollar la vista para poder crear los usuarios, se definieron los endpoints para crear, leer, editar y actualizar a los usuarios

- Se creó una funcion en computed para rentornar el filtrado de las tareas completadas, las que no estaban completadas y todas, y los datos que se retornaran se mostraran en el listado de las tareas


