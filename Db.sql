/*Eliminar Vistas*/

DROP VIEW SESSION_START;

DROP VIEW PRODUCT_DETAIL;

DROP VIEW PRODUCT_LIST;

DROP VIEW GENRES_LIST_MANAGEMENT;

DROP VIEW BANKS_ENTITY_LIST_MANAGEMENT;

DROP VIEW DEPARTMENTS_MANAGEMENT;

DROP VIEW PURCHASINGS_STATUS_MANAGEMENT;

DROP VIEW TRANSACTIONS_LIST;

DROP VIEW PRODUCT_LIST_MANAGEMENT;

DROP VIEW PRODUCT_DATA_PU;

/*Eliminar Tablas*/

DROP TABLE USERS;

DROP TABLE PRODUCTS;

DROP TABLE PAYS;

DROP TABLE DIRECTIONS;

DROP TABLE TRANSACTIONS;

DROP TABLE BANKING_ENTITIES;

DROP TABLE TRANSACTIONPRODUCT;

DROP TABLE CARS;

DROP TABLE CARPRODUCT;

DROP TABLE ADMINISTRATORS;

DROP TABLE PURCHASING_STATUS;

DROP TABLE DEPARTMENTS;

DROP TABLE GENRES;

DROP TABLE NEWS;

/*Eliminar Secuencias*/

DROP SEQUENCE USERS_SEQ;

DROP SEQUENCE PRODUCTS_SEQ;

DROP SEQUENCE DIRECTIONS_SEQ;

DROP SEQUENCE PAYS_SEQ;

DROP SEQUENCE TRANSACTIONS_SEQ;

DROP SEQUENCE TRPR_SEQ;

DROP SEQUENCE CARS_SEQ;

DROP SEQUENCE CARPR_SEQ;

DROP SEQUENCE ADMINISTRATORS_SEQ;

DROP SEQUENCE GENRES_SEQ;

DROP SEQUENCE DEPARTMENTS_SEQ;

DROP SEQUENCE PURCHASING_STATUS_SEQ;

DROP SEQUENCE BANKING_ENTITIES_SEQ;

DROP SEQUENCE NEWS_SEQ;

/*Eliminar tablespaces*/

DROP TABLESPACE USER_DATA;

DROP TABLESPACE TRANSACTION_DATA;

DROP TABLESPACE PRODUCT_DATA;

DROP TABLESPACE CAR_DATA;

/*Crear Tablas*/

CREATE TABLE USERS (
    USER_ID          NUMBER NOT NULL,
    GENRE_ID         NUMBER NOT NULL,
    ACTIVE           NUMBER(1) NOT NULL,
    FOUNDER           NUMBER(1) NOT NULL,
    CODE             VARCHAR2(10) NOT NULL,
    NAME             VARCHAR2(30) NOT NULL,
    SURNAME          VARCHAR2(40) NOT NULL,
    BIRTHDATE        DATE NOT NULL,
    PHONE            NUMBER NOT NULL,
    EMAIL            VARCHAR2(30) NOT NULL,
    USER_PASSWORD    VARCHAR2(100) NOT NULL,
    IMAGE            VARCHAR2(100) NOT NULL,
    EARNINGS         NUMBER NOT NULL,
    HIGHER_USER_ID   NUMBER NULL,
    CREATED_AT       DATE NOT NULL,
    CONSTRAINT users_pk PRIMARY KEY (USER_ID),
    CONSTRAINT higher_fk FOREIGN KEY (HIGHER_USER_ID) REFERENCES USERS(USER_ID),
    CONSTRAINT user_genre_fk FOREIGN KEY (GENRE_ID) REFERENCES GENRES (GENRE_ID)
);

CREATE TABLE PURCHASING_STATUS (
    PURCHASING_STATUS_ID          NUMBER NOT NULL,
    ACTIVE           NUMBER(1) NOT NULL,
    NAME             VARCHAR2(30) NOT NULL,
    CREATED_AT       DATE NOT NULL,
    CONSTRAINT status_pk PRIMARY KEY (PURCHASING_STATUS_ID)
);

CREATE TABLE DEPARTMENTS (
    DEPARTMENT_ID          NUMBER NOT NULL,
    ACTIVE           NUMBER(1) NOT NULL,
    NAME             VARCHAR2(30) NOT NULL,
    CREATED_AT       DATE NOT NULL,
    CONSTRAINT department_pk PRIMARY KEY (DEPARTMENT_ID)
);

CREATE TABLE BANKING_ENTITIES (
    BANKING_ENTITY_ID         NUMBER NOT NULL,
    ACTIVE           NUMBER(1) NOT NULL,
    NAME             VARCHAR2(30) NOT NULL,
    CREATED_AT       DATE NOT NULL,
    CONSTRAINT banking_entity_pk PRIMARY KEY (BANKING_ENTITY_ID)
);

CREATE TABLE GENRES (
    GENRE_ID          NUMBER NOT NULL,
    ACTIVE           NUMBER(1) NOT NULL,
    NAME             VARCHAR2(30) NOT NULL,
    CREATED_AT       DATE NOT NULL,
    CONSTRAINT genre_pk PRIMARY KEY (GENRE_ID)
);

CREATE TABLE PRODUCTS (
    PRODUCT_ID             NUMBER NOT NULL,
    USER_ID         NUMBER NULL,
    ACTIVE          NUMBER(1) NOT NULL,
    NAME            VARCHAR2(30) NOT NULL,
    PRICE           NUMBER NOT NULL,
    UNITS           NUMBER NOT NULL,
    CONTENT         VARCHAR2(10) NOT NULL,
    STOCK           NUMBER NOT NULL,
    DESCRIPTION     VARCHAR2(60) NOT NULL,
    IMAGE           VARCHAR2(100) NOT NULL,
    CREATED_AT      DATE NOT NULL,
    CONSTRAINT products_pk PRIMARY KEY (PRODUCT_ID),
    CONSTRAINT products_user_fk FOREIGN KEY (USER_ID) REFERENCES USERS(USER_ID)
);

CREATE TABLE PAYS (
    PAY_ID              NUMBER NOT NULL,
    USER_ID         NUMBER NOT NULL,
    BANKING_ENTITY_ID   NUMBER NOT NULL,
    ACTIVE          NUMBER(1) NOT NULL,
    NUMBER_ELECTION NUMBER NOT NULL,
    CREATED_AT      DATE NOT NULL,
    CONSTRAINT pays_pk PRIMARY KEY (PAY_ID),
    CONSTRAINT pays_user_fk FOREIGN KEY (USER_ID) REFERENCES USERS(USER_ID),
    CONSTRAINT pays_be_fk FOREIGN KEY (BANKING_ENTITY_ID) REFERENCES BANKING_ENTITIES(BANKING_ENTITY_ID)
);

CREATE TABLE DIRECTIONS (
    DIRECTION_ID              NUMBER NOT NULL,
    USER_ID         NUMBER NOT NULL,
    DEPARTMENT_ID     NUMBER NOT NULL,
    ACTIVE          NUMBER(1) NOT NULL,
    CITY             VARCHAR2(30) NOT NULL,
    CARRER          VARCHAR2(15) NOT NULL,
    STREET          VARCHAR2(15) NOT NULL,
    POSTAL_CODE     NUMBER NOT NULL,
    DIRECTION        VARCHAR2(50) NOT NULL,
    CREATED_AT      DATE NOT NULL,
    CONSTRAINT directions_pk PRIMARY KEY (DIRECTION_ID),
    CONSTRAINT directions_user_fk FOREIGN KEY (USER_ID) REFERENCES USERS(USER_ID),
    CONSTRAINT directions_department_fk FOREIGN KEY (DEPARTMENT_ID) REFERENCES DEPARTMENTS(DEPARTMENT_ID)
);

CREATE TABLE CARS (
    CAR_ID                NUMBER NOT NULL,
    USER_ID           NUMBER NOT NULL,
    ACTIVE          NUMBER(1) NOT NULL,
    CREATED_AT        DATE NOT NULL,
    CONSTRAINT cars_pk PRIMARY KEY (CAR_ID),
    CONSTRAINT c_user_fk FOREIGN KEY (USER_ID) REFERENCES USERS (USER_ID)
);

CREATE TABLE ADMINISTRATORS (
    ADMINISTRATOR_ID                NUMBER NOT NULL,
    EMAIL           VARCHAR2(30) NOT NULL,
    ADMINISTRATOR_PASSWORD        VARCHAR2(20) NOT NULL,
    CREATED_AT        DATE NOT NULL,
    CONSTRAINT administrators_pk PRIMARY KEY (ADMINISTRATOR_ID)
);

CREATE TABLE CARPRODUCT (
    CP_ID                  NUMBER NOT NULL,
    CAR_ID           NUMBER NOT NULL,
    PRODUCT_ID           NUMBER NOT NULL,
    ACTIVE          NUMBER(1) NOT NULL,
    UNITS            NUMBER NOT NULL,
    PRICE            NUMBER NOT NULL,
    CREATED_AT        DATE NOT NULL,
    CONSTRAINT carproduct_pk PRIMARY KEY (CP_ID),
    CONSTRAINT cp_car_fk FOREIGN KEY (CAR_ID) REFERENCES CARS (CAR_ID),
    CONSTRAINT cp_product_fk FOREIGN KEY (PRODUCT_ID) REFERENCES PRODUCTS (PRODUCT_ID)
);

CREATE TABLE TRANSACTIONS (
    TRANSACTION_ID                NUMBER NOT NULL,
    NUMBER_BILL     NUMBER NOT NULL,
    BUYER_ID       NUMBER NOT NULL,
    DIRECTION_ID           NUMBER NOT NULL,
    PAY_ID           NUMBER NOT NULL,
    TOTAL             NUMBER NOT NULL,
    DATE_TIME  DATE NOT NULL,
    CREATED_AT      DATE NOT NULL,
    CONSTRAINT shops_pk PRIMARY KEY (TRANSACTION_ID),
    CONSTRAINT tr_buyer_fk FOREIGN KEY (BUYER_ID) REFERENCES USERS (USER_ID),
    CONSTRAINT tr_direction_fk FOREIGN KEY (DIRECTION_ID) REFERENCES PAYS (PAY_ID),
    CONSTRAINT tr_pay_fk FOREIGN KEY (PAY_ID) REFERENCES DIRECTIONS (DIRECTION_ID)
);

CREATE TABLE NEWS (
    NEWS_ID         NUMBER NOT NULL,
    ACTIVE           NUMBER(1) NOT NULL,
    TITLE             VARCHAR2(200) NOT NULL,
    CONTENT             VARCHAR2(350) NOT NULL,
    LINK            VARCHAR2(150) NOT NULL,
    IMAGE           VARCHAR2(100) NOT NULL,
    CREATED_AT       DATE NOT NULL,
    CONSTRAINT news_pk PRIMARY KEY (NEWS_ID)
);

CREATE TABLE TRANSACTIONPRODUCT (
    TP_ID                  NUMBER NOT NULL,
    TRANSACTION_ID      NUMBER NOT NULL,
    PRODUCT_ID       NUMBER NOT NULL,
    SELLER_ID        NUMBER NULL,
    PURCHASING_STATUS_ID      NUMBER NOT NULL,
    UNITS            NUMBER NOT NULL,
    CREATED_AT      DATE NOT NULL,
    CONSTRAINT trpr_pk PRIMARY KEY ( TP_ID ),
    CONSTRAINT trpr_transaction_fk FOREIGN KEY (TRANSACTION_ID) REFERENCES TRANSACTIONS (TRANSACTION_ID),
    CONSTRAINT trpr_product_fk FOREIGN KEY (PRODUCT_ID) REFERENCES PRODUCTS (PRODUCT_ID),
    CONSTRAINT trpr_user_fk FOREIGN KEY (SELLER_ID) REFERENCES USERS (USER_ID),
    CONSTRAINT purchase_status_fk FOREIGN KEY (PURCHASING_STATUS_ID) REFERENCES PURCHASING_STATUS(PURCHASING_STATUS_ID)
);

/*Crear table space*/

CREATE TABLESPACE USER_DATA 
DATAFILE 'C:\Users\eduar\Oracle\tb/userdata.dbf'
SIZE 500M 
AUTOEXTEND ON 
NEXT 50M 
MAXSIZE 2G;

CREATE TABLESPACE PRODUCT_DATA 
DATAFILE 'C:\Users\eduar\Oracle\tb/productdata.dbf'
SIZE 500M 
AUTOEXTEND ON 
NEXT 50M 
MAXSIZE 3G;

CREATE TABLESPACE TRANSACTION_DATA 
DATAFILE 'C:\Users\eduar\Oracle\tb/transactiondata.dbf'
SIZE 500M 
AUTOEXTEND ON 
NEXT 50M 
MAXSIZE 4G;

CREATE TABLESPACE CAR_DATA 
DATAFILE 'C:\Users\eduar\Oracle\tb/cardata.dbf'
SIZE 500M 
AUTOEXTEND ON 
NEXT 50M 
MAXSIZE 5G;

/*Mover las tablas a los tablespace*/

ALTER TABLE USERS MOVE TABLESPACE USER_DATA;
ALTER TABLE GENRES MOVE TABLESPACE USER_DATA;

ALTER TABLE USERS MOVE TABLESPACE PRODUCT_DATA;
ALTER TABLE PRODUCTS MOVE TABLESPACE PRODUCT_DATA;
ALTER TABLE GENRES MOVE TABLESPACE USER_DATA;

ALTER TABLE CARS MOVE TABLESPACE CAR_DATA;
ALTER TABLE CARPRODUCT MOVE TABLESPACE CAR_DATA;
ALTER TABLE USERS MOVE TABLESPACE CAR_DATA;
ALTER TABLE PRODUCTS MOVE TABLESPACE CAR_DATA;
ALTER TABLE GENRES MOVE TABLESPACE USER_DATA;

ALTER TABLE TRANSACTIONS MOVE TABLESPACE TRANSACTION_DATA;
ALTER TABLE TRANSACTIONPRODUCT MOVE TABLESPACE TRANSACTION_DATA;
ALTER TABLE USERS MOVE TABLESPACE TRANSACTION_DATA;
ALTER TABLE PRODUCTS MOVE TABLESPACE TRANSACTION_DATA;
ALTER TABLE DIRECTIONS MOVE TABLESPACE TRANSACTION_DATA;
ALTER TABLE PAYS MOVE TABLESPACE TRANSACTION_DATA;
ALTER TABLE DEPARTMENTS MOVE TABLESPACE TRANSACTION_DATA;
ALTER TABLE GENRES MOVE TABLESPACE USER_DATA;

/*Crear Secuencias*/

CREATE SEQUENCE USERS_SEQ
START WITH 1
INCREMENT BY 1;

CREATE SEQUENCE PRODUCTS_SEQ
START WITH 1
INCREMENT BY 1;

CREATE SEQUENCE PAYS_SEQ
START WITH 1
INCREMENT BY 1;

CREATE SEQUENCE DIRECTIONS_SEQ
START WITH 1
INCREMENT BY 1;

CREATE SEQUENCE TRANSACTIONS_SEQ
START WITH 1
INCREMENT BY 1;

CREATE SEQUENCE TRPR_SEQ
START WITH 1
INCREMENT BY 1;

CREATE SEQUENCE CARS_SEQ
START WITH 1
INCREMENT BY 1;

CREATE SEQUENCE CARPR_SEQ
START WITH 1
INCREMENT BY 1;

CREATE SEQUENCE ADMINISTRATORS_SEQ
START WITH 1
INCREMENT BY 1;

CREATE SEQUENCE GENRES_SEQ
START WITH 1
INCREMENT BY 1;

CREATE SEQUENCE DEPARTMENTS_SEQ
START WITH 1
INCREMENT BY 1;

CREATE SEQUENCE PURCHASING_STATUS_SEQ
START WITH 1
INCREMENT BY 1;

CREATE SEQUENCE BANKING_ENTITIES_SEQ
START WITH 1
INCREMENT BY 1;

CREATE SEQUENCE NEWS_SEQ
START WITH 1
INCREMENT BY 1;

/*Crear Triggers*/

CREATE OR REPLACE TRIGGER USERS_TRG
BEFORE INSERT ON USERS
FOR EACH ROW
BEGIN
    IF :NEW.USER_ID IS NULL THEN
        SELECT EDUARDED.USERS_SEQ.NEXTVAL INTO :NEW.USER_ID FROM DUAL;
    END IF;
END;

CREATE OR REPLACE TRIGGER PRODUCTS_TRG
BEFORE INSERT ON PRODUCTS
FOR EACH ROW
BEGIN
    IF :NEW.PRODUCT_ID IS NULL THEN
        SELECT EDUARDED.PRODUCTS_SEQ.NEXTVAL INTO :NEW.PRODUCT_ID FROM DUAL;
    END IF;
END;

CREATE OR REPLACE TRIGGER PAYS_TRG
BEFORE INSERT ON PAYS
FOR EACH ROW
BEGIN
    IF :NEW.PAY_ID IS NULL THEN
        SELECT EDUARDED.PAYS_SEQ.NEXTVAL INTO :NEW.PAY_ID FROM DUAL;
    END IF;
END;

CREATE OR REPLACE TRIGGER DIRECTIONS_TRG
BEFORE INSERT ON DIRECTIONS
FOR EACH ROW
BEGIN
    IF :NEW.DIRECTION_ID IS NULL THEN
        SELECT EDUARDED.DIRECTIONS_SEQ.NEXTVAL INTO :NEW.DIRECTION_ID FROM DUAL;
    END IF;
END;

CREATE OR REPLACE TRIGGER TRANSACTIONS_TRG
BEFORE INSERT ON TRANSACTIONS
FOR EACH ROW
BEGIN
    IF :NEW.TRANSACTION_ID IS NULL THEN
        SELECT EDUARDED.TRANSACTIONS_SEQ.NEXTVAL INTO :NEW.TRANSACTION_ID FROM DUAL;
    END IF;
END;

CREATE OR REPLACE TRIGGER TRPR_TRG
BEFORE INSERT ON TRANSACTIONPRODUCT
FOR EACH ROW
BEGIN
    IF :NEW.TP_ID IS NULL THEN
        SELECT EDUARDED.TRPR_SEQ.NEXTVAL INTO :NEW.TP_ID FROM DUAL;
    END IF;
END;

CREATE OR REPLACE TRIGGER CARS_TRG
BEFORE INSERT ON CARS
FOR EACH ROW
BEGIN
    IF :NEW.CAR_ID IS NULL THEN
        SELECT EDUARDED.CARS_SEQ.NEXTVAL INTO :NEW.CAR_ID FROM DUAL;
    END IF;
END;

CREATE OR REPLACE TRIGGER CARPR_TRG
BEFORE INSERT ON CARPRODUCT
FOR EACH ROW
BEGIN
    IF :NEW.CP_ID IS NULL THEN
        SELECT EDUARDED.CARPR_SEQ.NEXTVAL INTO :NEW.CP_ID FROM DUAL;
    END IF;
END;

CREATE OR REPLACE TRIGGER ADMINISTRATOR_TRG
BEFORE INSERT ON ADMINISTRATORS
FOR EACH ROW
BEGIN
    IF :NEW.ADMINISTRATOR_ID IS NULL THEN
        SELECT EDUARDED.ADMINISTRATORS_SEQ.NEXTVAL INTO :NEW.ADMINISTRATOR_ID FROM DUAL;
    END IF;
END;

CREATE OR REPLACE TRIGGER DEPARTMENT_TRG
BEFORE INSERT ON DEPARTMENTS
FOR EACH ROW
BEGIN
    IF :NEW.DEPARTMENT_ID IS NULL THEN
        SELECT EDUARDED.DEPARTMENTS_SEQ.NEXTVAL INTO :NEW.DEPARTMENT_ID FROM DUAL;
    END IF;
END;

CREATE OR REPLACE TRIGGER GENRE_TRG
BEFORE INSERT ON GENRES
FOR EACH ROW
BEGIN
    IF :NEW.GENRE_ID IS NULL THEN
        SELECT EDUARDED.GENRES_SEQ.NEXTVAL INTO :NEW.GENRE_ID FROM DUAL;
    END IF;
END;

CREATE OR REPLACE TRIGGER PURCHASING_STATUS_TRG
BEFORE INSERT ON PURCHASING_STATUS
FOR EACH ROW
BEGIN
    IF :NEW.PURCHASING_STATUS_ID IS NULL THEN
        SELECT EDUARDED.PURCHASING_STATUS_SEQ.NEXTVAL INTO :NEW.PURCHASING_STATUS_ID FROM DUAL;
    END IF;
END;

CREATE OR REPLACE TRIGGER BANKING_ENTITY_TRG
BEFORE INSERT ON BANKING_ENTITIES
FOR EACH ROW
BEGIN
    IF :NEW.BANKING_ENTITY_ID IS NULL THEN
        SELECT EDUARDED.BANKING_ENTITIES_SEQ.NEXTVAL INTO :NEW.BANKING_ENTITY_ID FROM DUAL;
    END IF;
END;

CREATE OR REPLACE TRIGGER NEW_TRG
BEFORE INSERT ON NEWS
FOR EACH ROW
BEGIN
    IF :NEW.NEWS_ID IS NULL THEN
        SELECT EDUARDED.NEWS_SEQ.NEXTVAL INTO :NEW.NEWS_ID FROM DUAL;
    END IF;
END;

/*Crear o Reemplazar Vistas*/

CREATE OR REPLACE VIEW SESSION_START AS
SELECT USER_ID, ACTIVE, CODE, NAME, SURNAME, PHONE, EMAIL, USER_PASSWORD, FOUNDER, IMAGE, HIGHER_USER_ID
FROM users;

CREATE OR REPLACE VIEW PRODUCT_DETAIL AS
SELECT PRODUCT_ID, USER_ID, NAME, PRICE, UNITS, CONTENT, STOCK, DESCRIPTION, IMAGE
FROM products;

CREATE OR REPLACE VIEW PRODUCT_LIST AS
SELECT PRODUCT_ID, ACTIVE, NAME, PRICE, IMAGE
FROM products;

CREATE OR REPLACE VIEW GENRES_LIST_MANAGEMENT AS
SELECT GENRE_ID, ACTIVE, NAME
FROM genres;

CREATE OR REPLACE VIEW BANKS_ENTITY_LIST_MANAGEMENT AS
SELECT BANKING_ENTITY_ID, NAME
FROM BANKING_ENTITIES;

CREATE OR REPLACE VIEW DEPARTMENTS_MANAGEMENT AS
SELECT DEPARTMENT_ID, NAME
FROM departments;

CREATE OR REPLACE VIEW PURCHASINGS_STATUS_MANAGEMENT AS
SELECT PURCHASING_STATUS_ID, NAME
FROM PURCHASING_STATUS;

CREATE OR REPLACE VIEW TRANSACTIONS_LIST AS
SELECT TRANSACTION_ID, BUYER_ID, NUMBER_BILL, DATE_TIME
FROM transactions;

CREATE OR REPLACE VIEW PRODUCT_LIST_MANAGEMENT AS
SELECT PRODUCT_ID, ACTIVE, NAME, PRICE, UNITS, CONTENT, STOCK
FROM products;

CREATE OR REPLACE VIEW PRODUCT_DATA_PU AS
SELECT PRODUCT_ID, USER_ID, PRICE
FROM products;

/*Funciones*/

create or replace FUNCTION ADD_USER(userId IN NUMBER, userCode IN VARCHAR2) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE users
    SET HIGHER_USER_ID = userId
    WHERE code = userCode;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el pago';
END;

create or replace FUNCTION ASSIGN_FOUNDER(userCode IN VARCHAR2) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE users
    SET FOUNDER = 1
    WHERE code = userCode;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el pago';
END;

create or replace FUNCTION CHANGE_PASSWORD(u_user_id IN NUMBER, u_new_password IN VARCHAR2) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE users
    SET user_password = u_new_password
    WHERE user_id = u_user_id;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el pago';
END;

create or replace FUNCTION DECREASE_INVENTORY(p_product_id IN NUMBER, t_cantidad IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE products
    SET stock = stock - t_cantidad
    WHERE product_id = p_product_id;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el pago';
END;

create or replace FUNCTION DECREASE_QUANTITY(cp_product_id IN NUMBER, c_user_id IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE carproduct
    SET units = units - 1 WHERE car_id IN (SELECT car_id FROM cars WHERE user_id = c_user_id)
    AND product_id = cp_product_id;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el pago';
END;

create or replace FUNCTION DELETE_BANK_ENTITY(be_bank_entity_id IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE BANKING_ENTITIES
    SET active = 0
    WHERE BANKING_ENTITY_ID = be_bank_entity_id;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el pago';
END;

create or replace FUNCTION DELETE_CAR(user_id IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE cars
    SET active = 0
    WHERE user_id = user_id;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el pago';
END;

create or replace FUNCTION DELETE_DEPARTMENT(d_department_id IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE departments
    SET active = 0
    WHERE department_id = d_department_id;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el pago';
END;

create or replace FUNCTION DELETE_DIRECTION(d_direction_id IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE directions
    SET active = 0
    WHERE direction_id = d_direction_id;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar la direccion';
END;

create or replace FUNCTION DELETE_GENRE(g_genre_id IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE genres
    SET active = 0
    WHERE genre_id = g_genre_id;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el pago';
END;

create or replace FUNCTION DELETE_NEW(n_new_id IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE news
    SET active = 0
    WHERE news_id = n_new_id;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el pago';
END;

create or replace FUNCTION DELETE_PAY(p_pay_id IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE pays
    SET active = 0
    WHERE pay_id = p_pay_id;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el pago';
END;

create or replace FUNCTION DELETE_PRODUCT(p_product_id IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE products
    SET active = 0
    WHERE product_id = p_product_id;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el producto';
END;

create or replace FUNCTION DELETE_PRODUCT_CAR(cp_product_id IN NUMBER, c_user_id IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE carproduct
    SET active = 0 WHERE car_id IN (SELECT car_id FROM cars WHERE user_id = c_user_id)
    AND product_id = cp_product_id;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el pago';
END;

create or replace FUNCTION DELETE_PURCHASING_STATUS(ps_purchasing_status_id IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE PURCHASING_STATUS
    SET active = 0
    WHERE PURCHASING_STATUS_ID = ps_purchasing_status_id;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el pago';
END;

create or replace FUNCTION DELETE_USER(u_user_id IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE users
    SET active = 0
    WHERE user_id = u_user_id;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el usuario';
END;

create or replace FUNCTION DETAIL_PRODUCT(p_product_id IN NUMBER)
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    OPEN v_cursor FOR
        SELECT * 
        FROM PRODUCT_DETAIL 
        WHERE product_id = p_product_id;
    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN NULL;
    WHEN OTHERS THEN
        RAISE;
END DETAIL_PRODUCT;

create or replace FUNCTION DETAIL_SALE(t_transaction_id IN NUMBER)
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    OPEN v_cursor FOR
        SELECT u.name AS USER_NAME, u.surname, u.email, u.phone, d.carrer, d.street, d.postal_code, 
        d.city, de.name AS DEPARTMENT_NAME, pa.number_election, p.name AS PRODUCT_NAME, p.price, p.content, 
        be.name AS BANK_ENTITY_NAME, tp.units
        FROM TRANSACTIONS t
        INNER JOIN TRANSACTIONPRODUCT tp ON t.transaction_id = tp.transaction_id
        INNER JOIN products p ON p.product_id = tp.transaction_id
        INNER JOIN users u ON u.user_id = t.buyer_id
        INNER JOIN pays pa ON pa.pay_id = t.pay_id
        INNER JOIN directions d ON d.direction_id = t.direction_id
        INNER JOIN departments de ON de.department_id = d.department_id
        INNER JOIN banking_entities be ON be.banking_entity_id = pa.banking_entity_id
        WHERE t.transaction_id = t_transaction_id;
    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN NULL;
    WHEN OTHERS THEN
        RAISE;
END DETAIL_SALE;

create or replace FUNCTION DETAIL_SHOP(t_transaction_id IN NUMBER)
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    OPEN v_cursor FOR
        SELECT u.name AS USER_NAME, u.surname, u.email, u.phone, d.carrer, d.street, d.postal_code, 
        d.city, de.name AS DEPARTMENT_NAME, pa.number_election, p.name AS PRODUCT_NAME, p.price, p.content, 
        be.name AS BANK_ENTITY_NAME, tp.units
        FROM TRANSACTIONS t
        INNER JOIN TRANSACTIONPRODUCT tp ON t.transaction_id = tp.transaction_id
        INNER JOIN products p ON p.product_id = tp.transaction_id
        LEFT JOIN users u ON u.user_id = tp.seller_id
        INNER JOIN pays pa ON pa.pay_id = t.pay_id
        INNER JOIN directions d ON d.direction_id = t.direction_id
        INNER JOIN departments de ON de.department_id = d.department_id
        INNER JOIN banking_entities be ON be.banking_entity_id = pa.banking_entity_id
        WHERE t.transaction_id = t_transaction_id;
    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN NULL;
    WHEN OTHERS THEN
        RAISE;
END DETAIL_SHOP;

create or replace FUNCTION DIRECTION_LIST_MANAGEMENT(
    p_user_id NUMBER  -- El ID del usuario, que se recibirá siempre
) RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde ACTIVE sea igual a 1
    -- y el USER_ID sea el dueño del pago
    OPEN v_cursor FOR
    SELECT *
    FROM DIRECTIONS
    WHERE ACTIVE = 1
    AND USER_ID = p_user_id;  -- Compara si el ID que llega es del dueño del pago
    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END DIRECTION_LIST_MANAGEMENT;

create or replace FUNCTION GET_ALL_NEWS RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde ACTIVE sea igual a 1
    -- y el USER_ID sea el dueño del pago
    OPEN v_cursor FOR
    SELECT * FROM (
    SELECT N.*, DBMS_RANDOM.VALUE AS RANDOM_ORDER
    FROM news N
    WHERE N.active = 1
    ORDER BY N.CREATED_AT DESC
) 
ORDER BY RANDOM_ORDER;
    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END GET_ALL_NEWS;

create or replace FUNCTION GET_ALL_PRODUCTS(
    p_user_id NUMBER := NULL  -- Parámetro opcional, por defecto NULL
) RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar productos activos, con stock disponible y del superior
    OPEN v_cursor FOR
    SELECT * FROM (
    SELECT P.*, DBMS_RANDOM.VALUE as RANDOM_ORDER
    FROM PRODUCTS P
    JOIN USERS U ON P.USER_ID = U.USER_ID
    WHERE P.ACTIVE = 1 
    AND P.STOCK > 0
    AND (p_user_id IS NULL OR U.HIGHER_USER_ID = p_user_id)
    ORDER BY P.CREATED_AT DESC
) 
ORDER BY RANDOM_ORDER;
    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END GET_ALL_PRODUCTS;

create or replace FUNCTION GET_BANK_ENTITIES RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde ACTIVE sea igual a 1
    -- y el USER_ID sea el dueño del pago
    OPEN v_cursor FOR
    SELECT *
    FROM banking_entities
    WHERE ACTIVE = 1;  -- Compara si el ID que llega es del dueño del pago
    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END GET_BANK_ENTITIES;

create or replace FUNCTION GET_BANK_ENTITY(be_bank_entity_id IN NUMBER)
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    OPEN v_cursor FOR
        SELECT * 
        FROM BANKS_ENTITY_LIST_MANAGEMENT
        WHERE BANKING_ENTITY_ID = be_bank_entity_id;

    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN NULL;
    WHEN OTHERS THEN
        RAISE;
END GET_BANK_ENTITY;

create or replace FUNCTION GET_DATA_PRODUCT_P(p_id IN NUMBER)
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor con el registro del usuario que coincide por email y está activo
    OPEN v_cursor FOR
    SELECT *
    FROM PRODUCT_DATA_PU
    WHERE product_id = p_id;
    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        -- Manejo de excepción si no se encuentra el usuario
        RETURN NULL;
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END GET_DATA_PRODUCT_P;

create or replace FUNCTION GET_DEPARTMENT(d_department_id IN NUMBER)
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    OPEN v_cursor FOR
        SELECT * 
        FROM DEPARTMENTS_MANAGEMENT
        WHERE DEPARTMENT_ID = d_department_id;
    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN NULL;
    WHEN OTHERS THEN
        RAISE;
END GET_DEPARTMENT;

create or replace FUNCTION GET_DEPARTMENTS RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde ACTIVE sea igual a 1
    -- y el USER_ID sea el dueño del pago
    OPEN v_cursor FOR
    SELECT *
    FROM DEPARTMENTS
    WHERE ACTIVE = 1;  -- Compara si el ID que llega es del dueño del pago
    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END GET_DEPARTMENTS;

create or replace FUNCTION GET_DIRECTION(d_direction_id IN NUMBER)
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    OPEN v_cursor FOR
        SELECT * 
        FROM directions 
        WHERE direction_id = d_direction_id;
    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN NULL;
    WHEN OTHERS THEN
        RAISE;
END GET_DIRECTION;

create or replace FUNCTION GET_GENRE(g_genre_id IN NUMBER)
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    OPEN v_cursor FOR
        SELECT * 
        FROM GENRES_LIST_MANAGEMENT 
        WHERE genre_id = g_genre_id;
    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN NULL;
    WHEN OTHERS THEN
        RAISE;
END GET_GENRE;

create or replace FUNCTION GET_GENRES RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde ACTIVE sea igual a 1
    -- y el USER_ID sea el dueño del pago
    OPEN v_cursor FOR
    SELECT *
    FROM GENRES
    WHERE ACTIVE = 1;  -- Compara si el ID que llega es del dueño del pago
    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END GET_GENRES;

create or replace FUNCTION GET_LAST_CAR
RETURN SYS_REFCURSOR
IS
    cur SYS_REFCURSOR;
BEGIN
    OPEN cur FOR
    SELECT NVL(MAX(CAR_ID), 0) AS ID
    FROM CARS;
    RETURN cur;
END;

create or replace FUNCTION GET_LAST_TRANSACTION
RETURN SYS_REFCURSOR
IS
    cur SYS_REFCURSOR;
BEGIN
    OPEN cur FOR
    SELECT NVL(MAX(transaction_id), 0) AS ID
    FROM TRANSACTIONS_LIST;
    RETURN cur;
END;

create or replace FUNCTION GET_NEWS(n_news_id IN NUMBER)
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    OPEN v_cursor FOR
        SELECT * 
        FROM news 
        WHERE news_id = n_news_id;
    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN NULL;
    WHEN OTHERS THEN
        RAISE;
END GET_NEWS;

create or replace FUNCTION GET_PASSWORD(p_email IN VARCHAR2)
RETURN VARCHAR2
IS
    v_password VARCHAR2(255);
BEGIN
    -- Asegúrate de usar un filtro que garantice una única fila
    SELECT user_password
    INTO v_password
    FROM USERS
    WHERE email = p_email
    AND ROWNUM = 1;  -- Garantiza que solo se obtenga una fila
    RETURN v_password;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN NULL;
    WHEN TOO_MANY_ROWS THEN
        -- Maneja el caso en que se devuelvan múltiples filas (opcional)
        RETURN NULL;
    WHEN OTHERS THEN
        RAISE;
END GET_PASSWORD;

create or replace FUNCTION GET_PAY(p_pay_id IN NUMBER)
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    OPEN v_cursor FOR
        SELECT P.PAY_ID, p.NUMBER_ELECTION, be.NAME
        FROM pays p
        INNER JOIN banking_entities be ON be.BANKING_ENTITY_ID = p.BANKING_ENTITY_ID
        WHERE pay_id = p_pay_id;
    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN NULL;
    WHEN OTHERS THEN
        RAISE;
END GET_PAY;

create or replace FUNCTION GET_PRODUCT(p_product_id IN NUMBER)
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    OPEN v_cursor FOR
        SELECT * 
        FROM PRODUCT_DETAIL 
        WHERE product_id = p_product_id;
    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN NULL;
    WHEN OTHERS THEN
        RAISE;
END GET_PRODUCT;

create or replace FUNCTION GET_PRODUCTS RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde ACTIVE sea igual a 1
    -- y el USER_ID sea el dueño del pago
    OPEN v_cursor FOR
    SELECT p.product_id, p.name, p.price, p.stock, p.units, p.content, u.name AS USER_NAME, u.user_id
    FROM PRODUCTS p
    INNER JOIN USERS u ON u.user_id = p.user_id;  -- Compara si el ID que llega es del dueño del pago
    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END GET_PRODUCTS;

create or replace FUNCTION GET_PRODUCTS_ADMIN RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde ACTIVE sea igual a 1
    -- y el USER_ID sea el dueño del pago
    OPEN v_cursor FOR
    SELECT p.product_id, p.name, p.price, p.stock, p.units, p.content
    FROM PRODUCTS p
    where user_id is null;  -- Compara si el ID que llega es del dueño del pago
    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END GET_PRODUCTS_ADMIN;

create or replace FUNCTION GET_PURCHASING_STATUES RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde ACTIVE sea igual a 1
    -- y el USER_ID sea el dueño del pago
    OPEN v_cursor FOR
    SELECT *
    FROM purchasing_status
    WHERE ACTIVE = 1; -- Compara si el ID que llega es del dueño del pago
    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END GET_PURCHASING_STATUES;

create or replace FUNCTION GET_PURCHASING_STATUS(ps_purchasing_status_id IN NUMBER)
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    OPEN v_cursor FOR
        SELECT * 
        FROM PURCHASINGS_STATUS_MANAGEMENT 
        WHERE PURCHASING_STATUS_ID = ps_purchasing_status_id;
    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN NULL;
    WHEN OTHERS THEN
        RAISE;
END GET_PURCHASING_STATUS;

create or replace FUNCTION GET_USER(u_id IN NUMBER)
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor con el registro del usuario que coincide por email y está activo
    OPEN v_cursor FOR
    SELECT *
    FROM SESSION_START
    WHERE user_id = u_id;
    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        -- Manejo de excepción si no se encuentra el usuario
        RETURN NULL;
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END GET_USER;

create or replace FUNCTION GET_USERS RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde ACTIVE sea igual a 1
    -- y el USER_ID sea el dueño del pago
    OPEN v_cursor FOR
    SELECT *
    FROM USERS;  -- Compara si el ID que llega es del dueño del pago
    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END GET_USERS;

create or replace FUNCTION GETS_NEWS RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde ACTIVE sea igual a 1
    -- y el USER_ID sea el dueño del pago
    OPEN v_cursor FOR
    SELECT * FROM (
    SELECT N.*, DBMS_RANDOM.VALUE AS RANDOM_ORDER
    FROM news N
    WHERE N.active = 1
    ORDER BY N.CREATED_AT DESC
) 
WHERE ROWNUM <= 2
ORDER BY RANDOM_ORDER;
    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END GETS_NEWS;

create or replace FUNCTION INCREASE_PROFITS(t_id_seller IN NUMBER, t_total IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE users
    SET earnings = earnings + t_total
    WHERE user_id = t_id_seller;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el pago';
END;

create or replace FUNCTION INCREASE_QUANTITY(cp_product_id IN NUMBER, c_user_id IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE carproduct
    SET units = units + 1 WHERE car_id IN (SELECT car_id FROM cars WHERE user_id = c_user_id)
    AND product_id = cp_product_id;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el pago';
END;

create or replace FUNCTION LOGIN(u_email IN VARCHAR2)
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor con el registro del usuario que coincide por email y está activo
    OPEN v_cursor FOR
    SELECT *
    FROM SESSION_START
    WHERE email = u_email
    AND ACTIVE = 1;
    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        -- Manejo de excepción si no se encuentra el usuario
        RETURN NULL;
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END LOGIN;

create or replace FUNCTION LOGINA(a_email IN VARCHAR2, a_password IN VARCHAR2)
RETURN NUMBER
IS
    v_count NUMBER;
BEGIN
    -- Contar los registros que coinciden con el email
    SELECT COUNT(*)
    INTO v_count
    FROM ADMINISTRATORS
    WHERE email = a_email
    AND administrator_password = a_password;
    -- Retornar 1 si el usuario existe, 0 si no
    IF v_count > 0 THEN
        RETURN 1;
    ELSE
        RETURN 0;
    END IF;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN 0;
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END LOGINA;

create or replace FUNCTION PAY_LIST_MANAGEMENT(
    p_user_id NUMBER  -- El ID del usuario, que se recibirá siempre
) RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde ACTIVE sea igual a 1
    -- y el USER_ID sea el dueño del pago
    OPEN v_cursor FOR
    SELECT P.PAY_ID, p.NUMBER_ELECTION, be.NAME
    FROM PAYS p
    INNER JOIN banking_entities be ON be.BANKING_ENTITY_ID = p.BANKING_ENTITY_ID
    WHERE p.ACTIVE = 1
    AND USER_ID = p_user_id;  -- Compara si el ID que llega es del dueño del pago
    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END PAY_LIST_MANAGEMENT;

CREATE OR REPLACE FUNCTION PRODUCTS_LIST(
    p_user_id NUMBER := NULL  -- Parámetro opcional, por defecto NULL
) RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar productos activos, con stock disponible
    OPEN v_cursor FOR
    SELECT * FROM (
        SELECT P.*, DBMS_RANDOM.VALUE as RANDOM_ORDER
        FROM PRODUCTS P
        WHERE P.ACTIVE = 1 
          AND P.STOCK > 0
          AND (
              -- Si p_user_id es NULL, solo permite productos con USER_ID nulo a usuarios de nivel 1
              (p_user_id IS NULL AND P.USER_ID IS NULL AND EXISTS (
                  SELECT 1
                  FROM USERS U
                  WHERE U.USER_ID = p_user_id AND U.HIGHER_USER_ID IS NULL -- Usuario de nivel 1
              ))
              OR
              -- Si p_user_id tiene un valor
              (p_user_id IS NOT NULL AND (
                  -- Permitir productos sin USER_ID si el usuario es nivel 1
                  (P.USER_ID IS NULL AND EXISTS (
                      SELECT 1
                      FROM USERS U
                      WHERE U.USER_ID = p_user_id AND U.HIGHER_USER_ID IS NULL -- Usuario de nivel 1
                  ))
                  OR
                  -- Mostrar productos que pertenecen a su nivel superior
                  (P.USER_ID IS NOT NULL AND P.USER_ID IN (
                      SELECT U.HIGHER_USER_ID
                      FROM USERS U
                      WHERE U.USER_ID = p_user_id -- Parámetro del usuario actual
                  ))
              ))
          )
        ORDER BY P.CREATED_AT DESC
    ) 
    WHERE ROWNUM <= 6
    ORDER BY RANDOM_ORDER;

    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END PRODUCTS_LIST;

create or replace FUNCTION PRODUCTS_LIST_CAR(
    p_user_id NUMBER  -- El ID del usuario, que se recibirá siempre
) RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde ACTIVE sea igual a 1
    -- y el USER_ID sea el dueño del pago
    OPEN v_cursor FOR
    SELECT p.product_id AS PRODUCT_ID, cp.cp_id, p.image, p.name, p.price, p.units, cp.units AS AMOUNT, p.stock
    FROM CARS c
    INNER JOIN carproduct cp ON c.car_id = cp.car_id
    INNER JOIN products p ON p.product_id = cp.product_id
    WHERE c.USER_ID = p_user_id
    AND cp.active = 1 AND c.active = 1;  -- Compara si el ID que llega es del dueño del pago
    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END PRODUCTS_LIST_CAR;

CREATE OR REPLACE FUNCTION PRODUCTS_LIST_CAR_P(
    p_user_id NUMBER  -- El ID del usuario, que se recibirá siempre
) RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde ACTIVE sea igual a 1
    -- y el USER_ID sea el dueño del pago
    OPEN v_cursor FOR
    SELECT 
        u.name AS NAME_SELLER, 
        u.surname, 
        u.email, 
        u.phone, 
        cp.cp_id, 
        p.image, 
        p.name AS NAME_PRODUCT, 
        p.price, 
        p.units, 
        p.content, 
        cp.units AS AMOUNT
    FROM CARS c
    INNER JOIN carproduct cp ON c.car_id = cp.car_id
    INNER JOIN products p ON p.product_id = cp.product_id
    LEFT JOIN users u ON u.user_id = p.user_id  -- LEFT JOIN para que sea opcional si user_id es nulo
    WHERE c.USER_ID = p_user_id
    AND cp.active = 1 
    AND c.active = 1
    AND (p.user_id IS NULL OR u.user_id IS NOT NULL);  -- Condición: si el product no tiene user_id, no hacer JOIN
    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END PRODUCTS_LIST_CAR_P;

create or replace FUNCTION PRODUCTS_LIST_MANAGEMENT(
    p_user_id NUMBER  -- El ID del usuario, que se recibirá siempre
) RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde ACTIVE sea igual a 1
    -- y el USER_ID sea el dueño del pago
    OPEN v_cursor FOR
    SELECT *
    FROM PRODUCTS
    WHERE ACTIVE = 1
    AND USER_ID = p_user_id;  -- Compara si el ID que llega es del dueño del pago
    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END PRODUCTS_LIST_MANAGEMENT;

create or replace FUNCTION REGISTER_BANK_ENTITY(
    d_active IN NUMBER,
    d_name IN VARCHAR2,
    d_created_at IN DATE
) RETURN VARCHAR2
AS
    v_resultado VARCHAR2(100);
BEGIN
    BEGIN
        INSERT INTO banking_entities (active, name, created_at)
        VALUES (d_active, d_name, d_created_at);
        COMMIT;
        v_resultado := 1;
    EXCEPTION
        WHEN OTHERS THEN
            v_resultado := 'Error al registrar direccion: ' || SQLERRM;
            ROLLBACK;
    END;
    RETURN v_resultado;
END;

create or replace FUNCTION REGISTER_CAR(
    c_user_id IN NUMBER,
    c_active IN NUMBER,
    c_created_at IN DATE
) RETURN VARCHAR2
AS
    v_resultado VARCHAR2(100);
BEGIN
    BEGIN
        INSERT INTO CARS (user_id, active, created_at)
        VALUES (c_user_id, c_active, c_created_at);
        COMMIT;
        v_resultado := 1;
    EXCEPTION
        WHEN OTHERS THEN
            v_resultado := 'Error al registrar usuario: ' || SQLERRM;
            ROLLBACK;
    END;
    RETURN v_resultado;
END;

create or replace FUNCTION REGISTER_CP(
    cp_id_car IN NUMBER,
    cp_id_product IN NUMBER,
    cp_active IN NUMBER,
    cp_units IN NUMBER,
    cp_price IN NUMBER,
    cp_created_at IN DATE
) RETURN VARCHAR2
AS
    v_resultado VARCHAR2(100);
BEGIN
    BEGIN
        INSERT INTO carproduct (car_id, product_id, active, units, price, created_at)
        VALUES (cp_id_car, cp_id_product, cp_active, cp_units, cp_price, cp_created_at);
        COMMIT;
        v_resultado := 1;
    EXCEPTION
        WHEN OTHERS THEN
            v_resultado := 'Error al registrar usuario: ' || SQLERRM;
            ROLLBACK;
    END;
    RETURN v_resultado;
END;

create or replace FUNCTION REGISTER_DEPARTMENT(
    d_active IN NUMBER,
    d_name IN VARCHAR2,
    d_created_at IN DATE
) RETURN VARCHAR2
AS
    v_resultado VARCHAR2(100);
BEGIN
    BEGIN
        INSERT INTO DEPARTMENTS (active, name, created_at)
        VALUES (d_active, d_name, d_created_at);
        COMMIT;
        v_resultado := 1;
    EXCEPTION
        WHEN OTHERS THEN
            v_resultado := 'Error al registrar direccion: ' || SQLERRM;
            ROLLBACK;
    END;
    RETURN v_resultado;
END;

create or replace FUNCTION REGISTER_DIRECTION(
    d_user_id IN NUMBER,
    d_department_id IN NUMBER,
    d_active IN NUMBER,
    d_city IN VARCHAR2,
    d_carrer IN VARCHAR2,
    d_street IN VARCHAR2,
    d_postal_code IN NUMBER,
    d_direction IN VARCHAR2, 
    d_created_at IN DATE
) RETURN VARCHAR2
AS
    v_resultado VARCHAR2(100);
BEGIN
    BEGIN
        INSERT INTO DIRECTIONS (user_id, department_id, active, city, carrer, street, postal_code, direction, created_at)
        VALUES (d_user_id, d_department_id, d_active, d_city, d_carrer, d_street, d_postal_code, d_direction, d_created_at);
        COMMIT;
        v_resultado := 1;
    EXCEPTION
        WHEN OTHERS THEN
            v_resultado := 'Error al registrar direccion: ' || SQLERRM;
            ROLLBACK;
    END;
    RETURN v_resultado;
END;

create or replace FUNCTION REGISTER_GENRE(
    g_active IN NUMBER,
    g_name IN VARCHAR2,
    g_created_at IN DATE
) RETURN VARCHAR2
AS
    v_resultado VARCHAR2(100);
BEGIN
    BEGIN
        INSERT INTO GENRES (active, name, created_at)
        VALUES (g_active, g_name, g_created_at);
        COMMIT;
        v_resultado := 1;
    EXCEPTION
        WHEN OTHERS THEN
            v_resultado := 'Error al registrar direccion: ' || SQLERRM;
            ROLLBACK;
    END;
    RETURN v_resultado;
END;

create or replace FUNCTION REGISTER_NEWS(
    n_active IN NUMBER,
    n_title IN VARCHAR2,
    n_content IN VARCHAR2,
    n_link IN VARCHAR2,
    n_image IN VARCHAR2,    
    n_created_at IN DATE
) RETURN VARCHAR2
AS
    v_resultado VARCHAR2(100);
BEGIN
    BEGIN
        INSERT INTO news (active, title, content, link, image, created_at)
        VALUES (n_active, n_title, n_content, n_link, n_image, n_created_at);
        COMMIT;
        v_resultado := 1;
    EXCEPTION
        WHEN OTHERS THEN
            v_resultado := 'Error al registrar usuario: ' || SQLERRM;
            ROLLBACK;
    END;
    RETURN v_resultado;
END;

create or replace FUNCTION REGISTER_PAY(
    p_user_id IN NUMBER,
    p_bank_entity_id IN NUMBER,
    p_active IN NUMBER,
    p_number_election IN NUMBER,
    p_created_at IN DATE
) RETURN VARCHAR2
AS
    v_resultado VARCHAR2(100);
BEGIN
    BEGIN
        INSERT INTO PAYS (user_id, banking_entity_id, active, number_election, created_at)
        VALUES (p_user_id, p_bank_entity_id, p_active, p_number_election, p_created_at);
        COMMIT;
        v_resultado := 1;
    EXCEPTION
        WHEN OTHERS THEN
            v_resultado := 'Error al registrar pago: ' || SQLERRM;
            ROLLBACK;
    END;
    RETURN v_resultado;
END;

create or replace FUNCTION REGISTER_PRODUCT(
    p_user_id IN NUMBER,
    p_active IN NUMBER,
    p_name IN VARCHAR2,
    p_price IN NUMBER,
    p_units IN NUMBER,
    p_content IN VARCHAR2,
    p_stock IN NUMBER,
    p_description IN VARCHAR2,
    p_image IN VARCHAR2,    
    p_created_at IN DATE
) RETURN NUMBER
AS
    v_count NUMBER;
BEGIN
    -- Verificar si el producto con el mismo nombre ya existe para ese usuario
    SELECT COUNT(*) INTO v_count
    FROM PRODUCTS
    WHERE user_id = p_user_id AND name = p_name;
    IF v_count > 0 THEN
        -- Si existe, actualiza el stock
        UPDATE PRODUCTS
        SET stock = stock + p_stock
        WHERE user_id = p_user_id AND name = p_name;
        COMMIT;
        RETURN 1; -- Retorna 1 si se actualizó exitosamente
    ELSE
        -- Si no existe, inserta el nuevo producto
        INSERT INTO PRODUCTS (user_id, active, name, price, units, content, stock, description, image, created_at)
        VALUES (p_user_id, p_active, p_name, p_price, p_units, p_content, p_stock, p_description, p_image, p_created_at);
        COMMIT;
        RETURN 2; -- Retorna 2 si se insertó exitosamente
    END IF;
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
        RETURN -1; -- Retorna -1 en caso de error
END;

create or replace FUNCTION REGISTER_PURCHASING_STATUS(
    ps_active IN NUMBER,
    ps_name IN VARCHAR2,
    ps_created_at IN DATE
) RETURN VARCHAR2
AS
    v_resultado VARCHAR2(100);
BEGIN
    BEGIN
        INSERT INTO purchasing_status (active, name, created_at)
        VALUES (ps_active, ps_name, ps_created_at);
        COMMIT;
        v_resultado := 1;
    EXCEPTION
        WHEN OTHERS THEN
            v_resultado := 'Error al registrar direccion: ' || SQLERRM;
            ROLLBACK;
    END;
    RETURN v_resultado;
END;

create or replace FUNCTION REGISTER_TP(
    tp_id_transaction IN NUMBER,
    tp_id_product IN NUMBER,
    tp_id_seller IN NUMBER,
    tp_id_purchasing_status IN NUMBER,
    tp_units IN NUMBER,
    tp_created_at IN DATE
) RETURN VARCHAR2
AS
    v_resultado VARCHAR2(100);
BEGIN
    BEGIN
        INSERT INTO TRANSACTIONPRODUCT (transaction_id, product_id, seller_id, purchasing_status_id, units, created_at)
        VALUES (tp_id_transaction, tp_id_product, tp_id_seller, tp_id_purchasing_status, tp_units, tp_created_at);
        COMMIT;
        v_resultado := 1;
    EXCEPTION
        WHEN OTHERS THEN
            v_resultado := 'Error al registrar usuario: ' || SQLERRM;
            ROLLBACK;
    END;
    RETURN v_resultado;
END;

create or replace FUNCTION REGISTER_TRANSACTION(
    t_number_bill IN NUMBER,
    t_id_buyer IN NUMBER,
    t_id_direction IN NUMBER,
    t_id_pay IN NUMBER,
    t_total IN NUMBER,
    t_date_time IN DATE,
    t_created_at IN DATE
) RETURN VARCHAR2
AS
    v_resultado VARCHAR2(100);
BEGIN
    BEGIN
        INSERT INTO TRANSACTIONS (number_bill, buyer_id, direction_id, pay_id, total, date_time, created_at)
        VALUES (t_number_bill, t_id_buyer, t_id_direction, t_id_pay, t_total, t_date_time, t_created_at);
        COMMIT;
        v_resultado := 1;
    EXCEPTION
        WHEN OTHERS THEN
            v_resultado := 'Error al registrar usuario: ' || SQLERRM;
            ROLLBACK;
    END;
    RETURN v_resultado;
END;

create or replace FUNCTION REGISTER_USER(
    u_genre_id IN NUMBER,
    u_active IN NUMBER,
    u_founder IN NUMBER,
    u_code IN VARCHAR2,
    u_name IN VARCHAR2,
    u_surname IN VARCHAR2,
    u_birthdate IN DATE,
    u_phone IN NUMBER,
    u_email IN VARCHAR2,
    u_password IN VARCHAR2,
    u_image IN VARCHAR2,
    u_earnings IN NUMBER,
    u_higuer_user_level IN NUMBER,
    u_created_at IN DATE
) RETURN VARCHAR2
AS
    v_resultado VARCHAR2(100);
BEGIN
    BEGIN
        INSERT INTO USERS (genre_id, active, founder, code, name, surname, birthdate, phone, email, user_password, image, earnings, higher_user_id, created_at)
        VALUES (u_genre_id, u_active, u_founder, u_code, u_name, u_surname, u_birthdate, u_phone, u_email, u_password, u_image, u_earnings, u_higuer_user_level, u_created_at);
        COMMIT;
        v_resultado := 1;
    EXCEPTION
        WHEN OTHERS THEN
            v_resultado := 'Error al registrar usuario: ' || SQLERRM;
            ROLLBACK;
    END;
    RETURN v_resultado;
END;

create or replace FUNCTION SALES_LIST(
    p_user_id NUMBER  -- El ID del usuario, que se recibirá siempre
) RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde ACTIVE sea igual a 1
    -- y el USER_ID sea el dueño del pago
    OPEN v_cursor FOR
    SELECT *
    FROM TRANSACTIONS_LIST tl
    INNER JOIN TRANSACTIONPRODUCT tp ON tp.transaction_id = tl.transaction_id
    WHERE tp.seller_id = p_user_id;  -- Compara si el ID que llega es del dueño del pago
    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END SALES_LIST;

create or replace FUNCTION SEARCH_PRODUCTS (p_product_name IN VARCHAR2) 
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los productos donde el nombre coincida
    OPEN v_cursor FOR
    SELECT *
    FROM PRODUCTS p
    WHERE p.name LIKE '%' || p_product_name || '%';  -- Concatenar los comodines correctamente
    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de excepciones
        RAISE;
END SEARCH_PRODUCTS;

create or replace FUNCTION SHOPPING_LIST(
    p_user_id NUMBER  -- El ID del usuario, que se recibirá siempre
) RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde ACTIVE sea igual a 1
    -- y el USER_ID sea el dueño del pago
    OPEN v_cursor FOR
    SELECT *
    FROM TRANSACTIONS_LIST
    WHERE buyer_id = p_user_id;  -- Compara si el ID que llega es del dueño del pago
    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END SHOPPING_LIST;

create or replace FUNCTION UNIQUE_CP(c_id_user IN NUMBER, cp_id_product IN NUMBER)
RETURN NUMBER
IS
  v_count NUMBER;
BEGIN
  SELECT COUNT(*)
  INTO v_count
  FROM cars 
  WHERE active = 1 
  AND car_id IN (
    SELECT car_id 
    FROM carproduct 
    WHERE user_id = c_id_user 
    AND product_id = cp_id_product 
    AND active = 1
  );
  IF v_count > 0 THEN
    RETURN 1;
  ELSE
    RETURN 0;
  END IF;
END;

create or replace FUNCTION UPDATE_BANK_ENTITY(
    be_bank_entity_id IN NUMBER,
    be_name IN VARCHAR2
) 
RETURN VARCHAR2 AS
BEGIN
    -- Construir la consulta dinámica para actualizar solo los campos no nulos
    UPDATE banking_entities
    SET 
        name = NVL(be_name, name)
    WHERE banking_entity_id = be_bank_entity_id;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 'Actualización exitosa';
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al actualizar el pago';
END;

create or replace FUNCTION UPDATE_DEPARTMENT(
    d_direction_id IN NUMBER,
    d_name IN VARCHAR2
) 
RETURN VARCHAR2 AS
BEGIN
    -- Construir la consulta dinámica para actualizar solo los campos no nulos
    UPDATE departments
    SET 
        name = NVL(d_name, name)
    WHERE department_id = d_direction_id;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 'Actualización exitosa';
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al actualizar el pago';
END;

create or replace FUNCTION UPDATE_DIRECTION(
    d_direction_id IN NUMBER,
    d_department_id IN NUMBER,
    d_carrer IN VARCHAR2 DEFAULT NULL,
    d_street IN VARCHAR2 DEFAULT NULL,
    d_postal_code IN NUMBER DEFAULT NULL,
    d_direction IN VARCHAR2 DEFAULT NULL
) 
RETURN VARCHAR2 AS
BEGIN
    -- Construir la consulta dinámica para actualizar solo los campos no nulos
    UPDATE directions
    SET 
        department_id = NVL(d_department_id, department_id),
        carrer = NVL(d_carrer, carrer),
        street = NVL(d_street, street),
        postal_code = NVL(d_postal_code, postal_code),
        direction = NVL(d_direction, direction)
    WHERE direction_id = d_direction_id;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 'Actualización exitosa';
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al actualizar la direccion';
END;

create or replace FUNCTION UPDATE_GENRE(
    g_genre_id IN NUMBER,
    g_name IN VARCHAR2
) 
RETURN VARCHAR2 AS
BEGIN
    -- Construir la consulta dinámica para actualizar solo los campos no nulos
    UPDATE genres
    SET 
        name = NVL(g_name, name)
    WHERE genre_id = g_genre_id;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 'Actualización exitosa';
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al actualizar el pago';
END;

create or replace FUNCTION UPDATE_NEW(
    n_new_id IN NUMBER,
    n_title IN VARCHAR2 DEFAULT NULL,
    n_content IN VARCHAR2 DEFAULT NULL,
    n_link IN VARCHAR2 DEFAULT NULL,
    n_image IN VARCHAR2 DEFAULT NULL
) 
RETURN VARCHAR2 AS
BEGIN
    -- Construir la consulta dinámica para actualizar solo los campos no nulos
    UPDATE news
    SET 
        title = NVL(n_title, title),
        content = NVL(n_content, content),
        link = NVL(n_link, link),      
        image = NVL(n_image, image)
    WHERE news_id = n_new_id;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 'Actualización exitosa';
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al actualizar el producto';
END;

create or replace FUNCTION UPDATE_PAY(
    p_pay_id IN NUMBER,
    p_bank_entity_id IN NUMBER,
    p_election IN VARCHAR2 DEFAULT NULL,
    p_election_number IN VARCHAR2 DEFAULT NULL
) 
RETURN VARCHAR2 AS
BEGIN
    -- Construir la consulta dinámica para actualizar solo los campos no nulos
    UPDATE pays
    SET 
        banking_entity_id = NVL(p_bank_entity_id, banking_entity_id),
        number_election = NVL(p_election_number, number_election)
    WHERE pay_id = p_pay_id;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 'Actualización exitosa';
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al actualizar el pago';
END;

create or replace FUNCTION UPDATE_PRODUCT(
    p_product_id IN NUMBER,
    p_name IN VARCHAR2 DEFAULT NULL,
    p_price IN NUMBER DEFAULT NULL,
    p_units IN NUMBER DEFAULT NULL,
    p_content IN VARCHAR2 DEFAULT NULL,
    p_stock IN NUMBER DEFAULT NULL,
    p_description IN VARCHAR2 DEFAULT NULL,
    p_image IN VARCHAR2 DEFAULT NULL
) 
RETURN VARCHAR2 AS
BEGIN
    -- Construir la consulta dinámica para actualizar solo los campos no nulos
    UPDATE products
    SET 
        name = NVL(p_name, name),
        price = NVL(p_price, price),
        units = NVL(p_units, units),
        content = NVL(p_content, content),
        stock = NVL(p_stock, stock),
        description = NVL(p_description, description),      
        image = NVL(p_image, image)
    WHERE product_id = p_product_id;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 'Actualización exitosa';
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al actualizar el producto';
END;

create or replace FUNCTION UPDATE_PURCHASING_STATUS(
    ps_purchasing_status_id IN NUMBER,
    ps_name IN VARCHAR2
) 
RETURN VARCHAR2 AS
BEGIN
    -- Construir la consulta dinámica para actualizar solo los campos no nulos
    UPDATE purchasing_status
    SET 
        name = NVL(ps_name, name)
    WHERE purchasing_status_id = ps_purchasing_status_id;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 'Actualización exitosa';
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al actualizar el pago';
END;

create or replace FUNCTION UPDATE_USER(
    u_user_id IN NUMBER,
    u_name IN VARCHAR2 DEFAULT NULL,
    u_surname IN VARCHAR2 DEFAULT NULL,
    u_phone IN NUMBER DEFAULT NULL,
    u_email IN VARCHAR2 DEFAULT NULL,
    u_image IN VARCHAR2 DEFAULT NULL
) 
RETURN VARCHAR2 AS
BEGIN
    -- Construir la consulta dinámica para actualizar solo los campos no nulos
    UPDATE users
    SET 
        name = NVL(u_name, name),
        surname = NVL(u_surname, surname),
        phone = NVL(u_phone, phone),
        email = NVL(u_email, email),
        image = NVL(u_image, image)
    WHERE user_id = u_user_id;
    -- Confirmar la transacción
    COMMIT;
    -- Retornar un mensaje de éxito
    RETURN 'Actualización exitosa';
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al actualizar el usuario';
END;

create or replace FUNCTION VALIDATE_UNIQUE_EMAIL(u_email VARCHAR2)
RETURN NUMBER
IS
    email_existe NUMBER;
BEGIN
    SELECT COUNT(*)
    INTO email_existe
    FROM users
    WHERE email = u_email;
    IF email_existe > 0 THEN
        RETURN 1;
    ELSE
        RETURN 0;
    END IF;
END VALIDATE_UNIQUE_EMAIL;