<?php
// SECCION NOVEDADES
/*
Noticias de las que el usuario no es autor, que no ha leído, pertenecen a sus grupos,
no hayan caducado y que han empezado.
*/

$sql_novedades = '
select n.id_usuario,
       n.id_noticia,
       n.titulo,
       n.cuerpo,
       n.fecha_inicio,
       n.fecha_fin,
       n.timestamp_not,
       n.num_version
from noticias n
where (n.id_usuario != ?  and curdate()>=n.fecha_inicio and curdate()<=n.fecha_fin)
  and n.id_noticia not in (
    select l.id_noticia as noticias_leidas
    from leer l
    where id_usuario = ?
)
  and id_noticia in (
    select a.id_noticia
    from equipos e
             join formar f on e.id_equipo = f.id_equipo
             join usuarios u on u.id_usuario = f.id_usuario
             join afectar a on e.id_equipo = a.id_equipo
    where u.id_usuario = ?)';

/*
ADMINISTRADORES, noticias el usuario no es autor, que no ha leido,que pertenezcan a cualquier grupo
y no haya caducado o todavia no ha empezado.
*/
$sql_novedades_admin = '
select n.id_usuario,
       n.id_noticia,
       n.titulo,
       n.cuerpo,
       n.fecha_inicio,
       n.fecha_fin,
       n.timestamp_not,
       n.num_version
from noticias n
where (n.id_usuario != ?  and curdate()>=n.fecha_inicio and curdate()<=n.fecha_fin)
  and n.id_noticia not in (
    select l.id_noticia as noticias_leidas
    from leer l
    where id_usuario = ?)';