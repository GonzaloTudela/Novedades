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
       date_format(n.fecha_inicio, "%d/%m/%y") as fecha_inicio,
       date_format(n.fecha_fin, "%d/%m/%y") as fecha_fin,
       from_unixtime(unix_timestamp(n.timestamp_not), "%d/%m/%y %H:%i") as timestamp_not,
       n.num_version,
       u.nombre,
       u.apellido1
from noticias n
join usuarios u using (id_usuario)
where n.id_usuario != ?  
  and ((curdate()>=n.fecha_inicio and curdate()<=n.fecha_fin) or (curdate()>=n.fecha_inicio and n.fecha_fin is null))
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
       date_format(n.fecha_inicio, "%d/%m/%y") as fecha_inicio,
       date_format(n.fecha_fin, "%d/%m/%y") as fecha_fin,
       from_unixtime(unix_timestamp(n.timestamp_not), "%d/%m/%y %H:%i") as timestamp_not,
       n.num_version,
       u.nombre,
       u.apellido1
from noticias n
join usuarios u using (id_usuario)
where n.id_usuario != ?  
  and ((curdate()>=n.fecha_inicio and curdate()<=n.fecha_fin) or (curdate()>=n.fecha_inicio and n.fecha_fin is null))
  and n.id_noticia not in (
    select l.id_noticia as noticias_leidas
    from leer l
    where id_usuario = ?)';