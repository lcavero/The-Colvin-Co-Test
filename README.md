The Colvin Co Test
==================

Requirements:
-------------
Debes escribir una aplicación en el framework que elijas. Podrás elegir los métodos de
entrada y salida que prefieras: formulario web, command de consola, archivo de texto, etc.<br><br>
La aplicación tendrá como dato de entrada un repositorio PHP de Github y como salida una
lista de palabras utilizadas en los nombres de clase. Además debe indicarse cuantas veces
aparece dicha palabra en todos los nombres de clase del repositorio. No es necesario
parsear el contenido de los archivos, únicamente el nombre de los mismos.
<br><br>
Se requiere que el repositorio tenga al menos dos niveles de directorios pero se recomienda
usar un repositorio no demasiado grande para hacer las pruebas.
<br><br>
Como pista: api.github.com
<br><br>Ejemplo entrada: myuser/myrepo
<br><br>Clases repositorio:
<br>src/Service.php
<br>src/MainController.php
<br>src/tests/TestService.php
<br>src/tests/TestController.php:
<br><br>Salida deseada:
<br>Test: 2
<br>Service: 2
<br>Controller: 2
<br>Main: 1
<br><br>Ejemplo entrada: ​symfony/symfony
<br>Salida deseada:
<br>Test 1191
<br>Exception 263
<br>Cache 110
<br>...

Info
----
PHP Version. 7.0
<br>
Symfony Version: 3.4
<br>
Tests: tests/AppBundle