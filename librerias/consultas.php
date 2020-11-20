<?php
//region SECCION LOGIN.PHP
// Buscamos el password del usuario (hash).
$sql_login = 'select password from usuarios where usuario=?';

// SQL PARA OBTENER LOS DATOS DEL USUARIO, QUE TRABAJA, TIENE CATEGORIA Y SU ESTADO DE USUARIO ES 1 (ALTA)
$sql_info = 'select u.id_usuario, c.nivel, u.nombre, u.apellido1, u.apellido2, t.estado_usu 
from usuarios u
    join trabajar t on u.id_usuario = t.id_usuario
    join categorias c on u.id_categoria = c.id_categoria
where usuario = ? and t.estado_usu = 1';

// SQL PARA OBTENER LAS EMPRESAS DEL USUARIO QUE ESTEN DADAS DE ALTA (1)
$sql_empresas = 'select e.id_empresa, e.nombre_emp, e.estado_emp
from empresas e
    join trabajar t using (id_empresa)
    join usuarios u using (id_usuario)
where u.usuario = ? and e.estado_emp = 1;';

// SQL PARA OBTENER LOS EQUIPOS QUE PERTENECE EL USUARIO
$sql_equipos = 'select e.id_equipo, e.nombre_equ
from usuarios u
    join formar f on u.id_usuario = f.id_usuario
    join equipos e on f.id_equipo = e.id_equipo
where usuario = ?';
//endregion

//region SECCION LEER NORMAS Y NOTICIAS
/*
NOTICIAS (Novedades y Reglas) de las que el usuario no es autor, que no ha leído, pertenecen a sus grupos,
no hayan caducado, que han empezado y no sean una noticia OLD (ACTUALIZADA).
*/
$sql_noticias = '
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
  and (curdate()>=n.fecha_inicio and (curdate()<=n.fecha_fin or fecha_fin is null))
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
    where u.id_usuario = ?)
  and id_noticia not in (select o.id_noticia from noticias o join noticias n on o.id_noticia = n.id_noticia_old)';

/*
NOTICIAS (Novedades y Reglas) para ADMINISTRADORES, noticias que el usuario no es autor, que no ha leido,que pertenezcan
a cualquier grupo y no haya caducado o todavia no ha empezado, INCLUIDAS TODAS LAS VERSIONES DE LAS NOTICIAS.
*/
$sql_noticias_admin = '
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
  and (curdate()>=n.fecha_inicio and (curdate()<=n.fecha_fin or fecha_fin is null))
  and n.id_noticia not in (
    select l.id_noticia as noticias_leidas
    from leer l
    where id_usuario = ?)';
/*
NOVEDADES de las que el usuario no es autor, que no ha leído, pertenecen a sus grupos,
no hayan caducado, que han empezado y no sean una novedad OLD (ACTUALIZADA)
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
       n.tipo,
       u.nombre,
       u.apellido1
from noticias n
join usuarios u using (id_usuario)
where n.id_usuario != ?  
  and (curdate()>=n.fecha_inicio and (curdate()<=n.fecha_fin or fecha_fin is null)) and n.tipo=0
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
    where u.id_usuario = ?)
    and id_noticia not in (select o.id_noticia from noticias o join noticias n on o.id_noticia = n.id_noticia_old)';

/*
NOVEDADES para ADMINISTRADORES, noticias que el usuario no es autor, que no ha leido,que pertenezcan
a cualquier grupo y no haya caducado o todavia no ha empezado, incluidas las novedades que fueron actualizadas.
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
       n.tipo,
       u.nombre,
       u.apellido1
from noticias n
join usuarios u using (id_usuario)
where n.id_usuario != ?  
  and (curdate()>=n.fecha_inicio and (curdate()<=n.fecha_fin or fecha_fin is null)) and n.tipo=0
  and n.id_noticia not in (
    select l.id_noticia as noticias_leidas
    from leer l
    where id_usuario = ?)';
/*
NORMAS (Novedades y Reglas) de las que el usuario no es autor, que no ha leído, pertenecen a sus grupos,
no hayan caducado ,que han empezado y no sean una noticia OLD (ACTUALIZADA)
*/
$sql_normas = '
select n.id_usuario,
       n.id_noticia,
       n.titulo,
       n.cuerpo,
       date_format(n.fecha_inicio, "%d/%m/%y") as fecha_inicio,
       date_format(n.fecha_fin, "%d/%m/%y") as fecha_fin,
       from_unixtime(unix_timestamp(n.timestamp_not), "%d/%m/%y %H:%i") as timestamp_not,
       n.num_version,
       n.tipo,
       u.nombre,
       u.apellido1
from noticias n
join usuarios u using (id_usuario)
where n.id_usuario != ?  
  and (curdate()>=n.fecha_inicio and (curdate()<=n.fecha_fin or fecha_fin is null)) and n.tipo=1
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
    where u.id_usuario = ?)
    and id_noticia not in (select o.id_noticia from noticias o join noticias n on o.id_noticia = n.id_noticia_old)';

/*
NORMAS (Novedades y Reglas) para ADMINISTRADORES, noticias que el usuario no es autor, que no ha leido,que pertenezcan
a cualquier grupo y no haya caducado o todavia no ha empezado incluidas las desactualizadas.
*/
$sql_normas_admin = '
select n.id_usuario,
       n.id_noticia,
       n.titulo,
       n.cuerpo,
       date_format(n.fecha_inicio, "%d/%m/%y") as fecha_inicio,
       date_format(n.fecha_fin, "%d/%m/%y") as fecha_fin,
       from_unixtime(unix_timestamp(n.timestamp_not), "%d/%m/%y %H:%i") as timestamp_not,
       n.num_version,
       n.tipo,
       u.nombre,
       u.apellido1
from noticias n
         join usuarios u using (id_usuario)
where n.id_usuario != ?
  and (curdate()>=n.fecha_inicio and (curdate()<=n.fecha_fin or fecha_fin is null)) and n.tipo=1
  and n.id_noticia not in (
    select l.id_noticia as noticias_leidas
    from leer l
    where id_usuario = ?)';
//endregion

//region SECCION INFORMACIÓN RELEVANTE
//NOTICIAS (Novedades y Reglas) que pertenecen a sus grupos, no hayan caducado, que han empezado y no esten desactualizadas.
$sql_activas = '
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
where (curdate()>=n.fecha_inicio and (curdate()<=n.fecha_fin or fecha_fin is null))
  and id_noticia in (
    select a.id_noticia
    from equipos e
             join formar f on e.id_equipo = f.id_equipo
             join usuarios u on u.id_usuario = f.id_usuario
             join afectar a on e.id_equipo = a.id_equipo
    where u.id_usuario = ?)
    and id_noticia not in (select o.id_noticia from noticias o join noticias n on o.id_noticia = n.id_noticia_old)';

/*
NOTICIAS (Novedades y Reglas), que pertenezcan a cualquier grupo (para ADMINISTRADORES) y no haya caducado o todavia
 no ha empezado, incluidas las desactualizadas.
*/
$sql_activas_admin = '
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
where (curdate()>=n.fecha_inicio and (curdate()<=n.fecha_fin or fecha_fin is null))
  and id_noticia in (
    select a.id_noticia
    from equipos e
             join formar f on e.id_equipo = f.id_equipo
             join usuarios u on u.id_usuario = f.id_usuario
             join afectar a on e.id_equipo = a.id_equipo
 )';

// INSERTA LA NOTICIA ACTUAL Y EL USUARIO EN LA TABLA LEER
$sql_leer = 'insert into leer (id_usuario, id_noticia) values (?,?)';

// HACER CADUCAR UNA NOTICIA FIJANDO SU FECHA FIN 1 DIA MENOS QUE LA FECHA ACTUAL
$sql_finalizar = 'update noticias set fecha_fin=date_sub(curdate(), interval 1 day) where id_noticia=?';

// SABER SI LA NOTICIA LA HA LEIDO EL USUARIO
$sql_leida = 'select id_noticia, id_usuario from leer where id_noticia = ? and id_usuario = ?';

/* ESTE AÑADIDO DETERMINA QUE NO SEA UNA NOTICIA ANTIGUA (HA SIDO ACTUALIZADA)
and id_noticia not in (select o.id_noticia from noticias o join noticias n on o.id_noticia = n.id_noticia_old)';

CONSULTA PARA PROBAR POR SEPARADO
select o.id_noticia from noticias o join noticias n on o.id_noticia = n.id_noticia_old
*/