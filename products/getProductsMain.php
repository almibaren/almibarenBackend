<?php
/** ESTO ES PARA LOS POPUS
SELECT p.nombre,p.descripcion,d.nombre, COUNT(p.id) as num
FROM producto p
inner join productoTransaccion pt on p.id=pt.idProducto
inner join transaccion t on pt.idTransaccion=t.id
inner join tipoTransaccion tt on t.idTipo=tt.id
inner join desarrollador d ON p.idDesarrollador = d.id
WHERE tt.nombre='Compra'
GROUP BY p.id
ORDER BY num DESC
LIMIT 0,10
 */