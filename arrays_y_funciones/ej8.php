<?php

/*Crea una función crearSlug(string $titulo): string que convierta un título de artículo en un "slug" válido para una URL. Un slug solo debe contener letras minúsculas, 
números y guiones. 
1. Define la función crearSlug. 
2. Dentro de la función, aplica la siguiente secuencia de transformaciones al $titulo: * Conviértelo a minúsculas (strtolower). 
* Reemplaza los espacios por guiones (str_replace). * Reto: Elimina cualquier caracter que no sea letra, número o guión. 
Para esto, tendrás que investigar la función preg_replace() con una expresión regular simple. 
3. Llama a la función con el título de ejemplo y muestra el slug resultante.

Pista 1: Para el reemplazo de espacios, str_replace(' ', '-', $titulo) es tu amigo.
Pista 2: La expresión regular para "cualquier cosa que NO sea a-z, 0-9 o guión" es /[^a-z0-9-]+/. 
preg_replace puede reemplazar todo lo que coincida con esa expresión por una cadena vacía.*/ 





function crearSlug(string $titulo){

$titulo_minusculas = strtolower($titulo);
$titulo_sin_espacios = str_replace(' ' , '-', $titulo_minusculas);


$titulo_reemplazado = preg_replace('/[^a-z0-9-]+/', '', $titulo_sin_espacios);

return $titulo_reemplazado;
}

$string = $_POST['cadena'];

echo crearSlug($string);

?>