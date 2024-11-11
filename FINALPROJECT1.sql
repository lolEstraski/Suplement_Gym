CREATE OR REPLACE PROCEDURE UPWARD AS
BEGIN
    -- Ejecutamos ambos SELECT en un solo bucle
    FOR rec IN (
        SELECT 
            u.user_id, u.higher_user_id,
            u.name, 
            CASE 
                WHEN COUNT(r.user_id) = 0 AND u.higher_user_id IS NULL AND u.founder != 1 THEN 'No Puede Tener Referidos' 
                WHEN COUNT(r.user_id) = 0 AND u.higher_user_id IS NULL AND u.founder = 1 THEN 'No Tiene Referidos'
                ELSE TO_CHAR(COUNT(r.user_id)) 
            END AS num_referidos,
            NVL2(NULLIF(SUM(u2.earnings), 0), SUM(u2.earnings), -1) AS total_ganancias
        FROM 
            users u
        LEFT JOIN 
            users r ON r.higher_user_id = u.user_id
        LEFT JOIN 
            users u2 ON u2.user_id = u.user_id
        GROUP BY 
            u.user_id, 
            u.name, 
            u.higher_user_id,
            u.founder
        HAVING 
            COUNT(r.user_id) > 1
            AND NVL2(NULLIF(SUM(u2.earnings), 0), SUM(u2.earnings), -1) > 10000  -- Filtramos en HAVING correctamente
        ORDER BY 
            num_referidos DESC
    ) LOOP
        IF rec.user_id IS NOT NULL THEN
            -- Ejecutamos el UPDATE si las condiciones se cumplen
            UPDATE users u
            SET u.higher_user_id = (
                SELECT u2.higher_user_id
                FROM users u2
                WHERE u2.user_id = u.higher_user_id
            )
            WHERE u.user_id = rec.user_id;
        END IF;
        -- Imprimimos los resultados
        DBMS_OUTPUT.PUT_LINE('User ID: ' || rec.user_id || 
                             ', Name: ' || rec.name || 
                             ', Num Referidos: ' || rec.num_referidos || 
                             ', Total Ganancias: ' || rec.total_ganancias);
    END LOOP;
    -- Confirmar los cambios
    COMMIT;
END UPWARD;
/
