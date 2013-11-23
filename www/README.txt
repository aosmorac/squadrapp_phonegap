Algunas pautas para ordenar y tener un mejor control de lo que se genere a nivel de codigo y estructura 
en la aplicación:



------------------------
Hojas de Estilo
------------------------

1. Cada seccion inicia con comentario indicado a que seccion corresponde
	Ej: /* Menu */
2. Cada seccion finaliza con comentario indicando el fin de los estilos de esa seccion.
	Ej: /*--- Fin Menu ---*/
3. Si un estilo corresponde directamente a una imagen, como puede ser un boton, el estilo debe llevar el nombre de la imagen.
	Ej: .btn_back_54x54 
	    el cual tendra una imagen de fondo 
	    btn_back_54x54.png

------------------------
Imagenes
------------------------
1. Imagenes necesarias en el funcionamiento de la aplicación se almacenan en el directorio img/
2. Imagenes de ayuda, de elementos generados o que no son del funcionamiento basico de la aplicacion se almacenan en images/
3. Las imagenes correspondientes a botones se almacenan en img/btn
4. Nombre de imagenes correspondientes a botones inician con prefijo btn_
	Ej: btn_back_54x54.png
5. Nombres genericos para imagenes, en lo posible en ingles, y agregar las dimenciones en el nombre
	Ej. search_54x54.png


-------------------------
Java Script
-------------------------
1. Nombre de funciones en ingles y que inicien con verbo.