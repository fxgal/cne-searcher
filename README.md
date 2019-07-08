# cne-searcher

_Módulo de búsqueda de datos personales en la BD del Consejo Nacional Electoral de Venezuela_

## Importante 🚀

_Los datos proporcionados por este script dependen directamente del Poder Electoral Venezolano._

Mira **Deployment** para conocer como desplegar el proyecto.


### Pre-requisitos 📋

_PHP 7.2.*_

### Inicio

_Crea una instancia de la clase, puedes pasar los parámetros iniciales (opcional) $cédula y $nacionalidad_
```
$cne = new CNE($cedula, $nacionalidad);
```
### Consulta

_Ejecuta el método search para realizar una búsqueda, en este punto puedes cambiar los parámetros iniciales si lo deseas_
_Con parámetros nuevos__
```
$result = $cne->search($cedula, $nacionalidad);
```
_Con los parámetros iniciales_
```
$result = $cne->search();
```
_También puedes cambiar los parámetros asignando valores directamente a los atributos_

```
$cne->cedula = 20000000;
$cne->nacionalidad = 'V';
```
_El método devuelve un json con los datos encontrados o un mensaje informando el status de la búsqueda_

## Autor ✒️

* **Félix Galindo** - *Trabajo Inicial* - [fxgal](https://github.com/fxgal/)
