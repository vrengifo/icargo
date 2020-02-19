-- ============================================================
--   Database name:  MODEL_1                                   
--   DBMS name:      ORACLE Version 8                          
--   Created on:     04/01/2004  9:06                          
-- ============================================================

-- Integrity package declaration
create or replace package IntegrityPackage AS
 procedure InitNestLevel;
 function GetNestLevel return number;
 procedure NextNestLevel;
 procedure PreviousNestLevel;
 end IntegrityPackage;
/

-- Integrity package definition
create or replace package body IntegrityPackage AS
 NestLevel number;

-- Procedure to initialize the trigger nest level
 procedure InitNestLevel is
 begin
 NestLevel := 0;
 end;


-- Function to return the trigger nest level
 function GetNestLevel return number is
 begin
 if NestLevel is null then
     NestLevel := 0;
 end if;
 return(NestLevel);
 end;

-- Procedure to increase the trigger nest level
 procedure NextNestLevel is
 begin
 if NestLevel is null then
     NestLevel := 0;
 end if;
 NestLevel := NestLevel + 1;
 end;

-- Procedure to decrease the trigger nest level
 procedure PreviousNestLevel is
 begin
 NestLevel := NestLevel - 1;
 end;

 end IntegrityPackage;
/

CREATE SEQUENCE "ICARGO"."MANIFIESTO_EMBARQUE_SEQ" INCREMENT BY 1 
    START WITH 1 MAXVALUE 1.0E28 MINVALUE 1 NOCYCLE 
    CACHE 20 NOORDER;
/
create trigger "ICARGO"."MANIFIESTO_EMBARQUE_TRIGGER" 
BEFORE INSERT ON "MANIFIESTO_EMBARQUE" 
FOR EACH ROW
begin select MANIFIESTO_EMBARQUE_SEQ.nextval into :new.MANEMB_ID from dual;
end;
/

CREATE SEQUENCE "ICARGO"."MANEMB_DETALLE_SEQ" INCREMENT BY 1 
    START WITH 1 MAXVALUE 1.0E28 MINVALUE 1 NOCYCLE 
    CACHE 20 NOORDER;
/
create trigger "ICARGO"."MANEMB_DETALLE_TRIGGER" 
BEFORE INSERT ON "MANEMB_DETALLE" 
FOR EACH ROW
begin select MANEMB_DETALLE_SEQ.nextval into :new.MANEMBDET_ID from dual;
end;
/

CREATE SEQUENCE "ICARGO"."MANIFIESTO_DESEMBARQUE_SEQ" INCREMENT BY 1 
    START WITH 1 MAXVALUE 1.0E28 MINVALUE 1 NOCYCLE 
    CACHE 20 NOORDER;
/
create trigger "ICARGO"."MANIFIESTO_DESEMBARQUE_TRIGGER" 
BEFORE INSERT ON "MANIFIESTO_DESEMBARQUE" 
FOR EACH ROW
begin select MANIFIESTO_DESEMBARQUE_SEQ.nextval into :new.MANDES_ID from dual;
end;
/

CREATE SEQUENCE "ICARGO"."MANDES_DETALLE_SEQ" INCREMENT BY 1 
    START WITH 1 MAXVALUE 1.0E28 MINVALUE 1 NOCYCLE 
    CACHE 20 NOORDER;
/
create trigger "ICARGO"."MANDES_DETALLE_TRIGGER" 
BEFORE INSERT ON "MANDES_DETALLE" 
FOR EACH ROW
begin select MANDES_DETALLE_SEQ.nextval into :new.MANDESDET_ID from dual;
end;
/

