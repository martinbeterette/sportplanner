USE proyecto_pp2;


-- SELECT 
--                     horario.id_horario,
--                     horario.horario_inicio,
--                     horario.horario_fin,
--                     reserva.id_reserva,
--                     zona.descripcion_zona,
--                     sucursal.descripcion_sucursal,
--                     complejo.descripcion_complejo,
--                     IF (reserva.id_reserva IS NULL, 'disponible', 'no-disponible') AS estado
--                 FROM 
--                     zona
--                 JOIN 
--                     sucursal
--                 ON
--                     zona.rela_sucursal = sucursal.id_sucursal
--                 JOIN
--                     complejo
--                 ON
--                     sucursal.rela_complejo = complejo.id_complejo
--                 AND
--                     id_zona = 3
--                 JOIN 
--                     reserva 
--                 ON 
--                     reserva.rela_zona = zona.id_zona
--                 RIGHT JOIN 
--                     horario
--                 ON
--                     horario.id_horario = reserva.rela_horario
--                 AND
--                     reserva.fecha_reserva = '2024-10-03'
--             ORDER BY (horario.horario_inicio);


            SELECT 
                    zona.id_zona,
                    zona.descripcion_zona,
                    sucursal.descripcion_sucursal,
                    reserva.fecha_reserva,
                    IF (reserva.id_reserva IS NULL, 'disponible', 'no-disponible') AS estado
                FROM 
                    zona
                JOIN 
                    sucursal ON zona.rela_sucursal = sucursal.id_sucursal
                LEFT JOIN 
                    reserva ON reserva.rela_zona = zona.id_zona 
                              AND reserva.fecha_reserva = '2024-10-03' -- La fecha que pasas
                              AND reserva.rela_horario = 1 -- El horario que pasas
                WHERE
                    zona.rela_tipo_terreno = 2
                    AND rela_formato_deporte = 2
                    and id_reserva is null

                ORDER BY 
                    (zona.id_zona);

