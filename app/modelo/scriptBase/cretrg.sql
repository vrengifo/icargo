-- ============================================================
--   Database name:  ICARGO                                    
--   DBMS name:      ORACLE Version 8                          
--   Created on:     28/02/2006  20:09                         
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

CREATE SEQUENCE APLICACION_SEQ INCREMENT BY 1 
    START WITH 1 MAXVALUE 1.0E28 MINVALUE 1 NOCYCLE 
    CACHE 20 NOORDER;
/
create trigger APLICACION_TRIGGER 
BEFORE INSERT ON "APLICACION" 
FOR EACH ROW
begin select APLICACION_SEQ.nextval into :new.ID_APLICACION from dual;
end;
/

--  After update trigger "tua_aplicacion" for table "APLICACION"
create trigger tua_aplicacion after update
of ID_APLICACION
on APLICACION for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
begin
    IntegrityPackage.NextNestLevel;
    
    --  Modify parent code of "APLICACION" for all children in "SUBAPLICACION"
    if (updating('ID_APLICACION') and :old.ID_APLICACION != :new.ID_APLICACION) then
       update SUBAPLICACION
        set   ID_APLICACION = :new.ID_APLICACION
       where  ID_APLICACION = :old.ID_APLICACION;
    end if;
    
    --  Modify parent code of "APLICACION" for all children in "USUARIO_APLICACION"
    if (updating('ID_APLICACION') and :old.ID_APLICACION != :new.ID_APLICACION) then
       update USUARIO_APLICACION
        set   ID_APLICACION = :new.ID_APLICACION
       where  ID_APLICACION = :old.ID_APLICACION;
    end if;
    IntegrityPackage.PreviousNestLevel;

--  Errors handling
exception
    when integrity_error then
       begin
       IntegrityPackage.InitNestLevel;
       raise_application_error(errno, errmsg);
       end;
end;
/

--  After delete trigger "tda_aplicacion" for table "APLICACION"
create trigger tda_aplicacion after delete
on APLICACION for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;

begin
    IntegrityPackage.NextNestLevel;
    
    --  Delete all children in "SUBAPLICACION"
    delete SUBAPLICACION
    where  ID_APLICACION = :old.ID_APLICACION;
    
    --  Delete all children in "USUARIO_APLICACION"
    delete USUARIO_APLICACION
    where  ID_APLICACION = :old.ID_APLICACION;
    IntegrityPackage.PreviousNestLevel;

--  Errors handling
exception
    when integrity_error then
       begin
       IntegrityPackage.InitNestLevel;
       raise_application_error(errno, errmsg);
       end;
end;
/

--  Before update trigger "tub_ciudad" for table "CIUDAD"
create trigger tub_ciudad before update
of CIU_CODIGO,
   PAI_CODIGO
on CIUDAD for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
    seq NUMBER;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "PAIS"
    cursor cpk1_ciudad(var_pai_codigo varchar) is
       select 1
       from   PAIS
       where  PAI_CODIGO = var_pai_codigo
        and   var_pai_codigo is not null;

begin
    seq := IntegrityPackage.GetNestLevel;
    
    --  Parent "PAIS" must exist when updating a child in "CIUDAD"
    if (:new.PAI_CODIGO is not null) and (seq = 0) then 
       open  cpk1_ciudad(:new.PAI_CODIGO);
       fetch cpk1_ciudad into dummy;
       found := cpk1_ciudad%FOUND;
       close cpk1_ciudad;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "PAIS". Cannot update child in "CIUDAD".';
          raise integrity_error;
       end if;
    end if;

--  Errors handling
exception
    when integrity_error then
       raise_application_error(errno, errmsg);
end;
/

--  After update trigger "tua_ciudad" for table "CIUDAD"
create trigger tua_ciudad after update
of CIU_CODIGO,
   PAI_CODIGO
on CIUDAD for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
begin
    IntegrityPackage.NextNestLevel;
    
    --  Modify parent code of "CIUDAD" for all children in "ESTACION"
    if (updating('CIU_CODIGO') and :old.CIU_CODIGO != :new.CIU_CODIGO) then
       update ESTACION
        set   CIU_CODIGO = :new.CIU_CODIGO
       where  CIU_CODIGO = :old.CIU_CODIGO;
    end if;
    IntegrityPackage.PreviousNestLevel;

--  Errors handling
exception
    when integrity_error then
       begin
       IntegrityPackage.InitNestLevel;
       raise_application_error(errno, errmsg);
       end;
end;
/

--  After delete trigger "tda_ciudad" for table "CIUDAD"
create trigger tda_ciudad after delete
on CIUDAD for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;

begin
    IntegrityPackage.NextNestLevel;
    
    --  Delete all children in "ESTACION"
    delete ESTACION
    where  CIU_CODIGO = :old.CIU_CODIGO;
    IntegrityPackage.PreviousNestLevel;

--  Errors handling
exception
    when integrity_error then
       begin
       IntegrityPackage.InitNestLevel;
       raise_application_error(errno, errmsg);
       end;
end;
/

CREATE SEQUENCE "ICARGO"."ESTACION_SEQ" INCREMENT BY 1 
    START WITH 1 MAXVALUE 1.0E28 MINVALUE 1 NOCYCLE 
    CACHE 20 NOORDER;
/
create trigger "ICARGO"."ESTACION_TRIGGER" 
BEFORE INSERT ON "ESTACION" 
FOR EACH ROW
begin select ESTACION_SEQ.nextval into :new.EST_ID from dual;
end;
/

--  Before update trigger "tub_estacion" for table "ESTACION"
create trigger tub_estacion before update
of EST_CODIGO,
   CIU_CODIGO,
   MON_CODIGO
on ESTACION for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
    seq NUMBER;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "CIUDAD"
    cursor cpk1_estacion(var_ciu_codigo varchar) is
       select 1
       from   CIUDAD
       where  CIU_CODIGO = var_ciu_codigo
        and   var_ciu_codigo is not null;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "MONEDA"
    cursor cpk2_estacion(var_mon_codigo varchar) is
       select 1
       from   MONEDA
       where  MON_CODIGO = var_mon_codigo
        and   var_mon_codigo is not null;
    
    --  Declaration of UpdateParentRestrict constraint for "OFICINA"
    cursor cfk1_estacion(var_est_codigo varchar) is
       select 1
       from   OFICINA
       where  EST_CODIGO = var_est_codigo
        and   var_est_codigo is not null;
    
    --  Declaration of UpdateParentRestrict constraint for "IMPDESCARXEST"
    cursor cfk2_estacion(var_est_codigo varchar) is
       select 1
       from   IMPDESCARXEST
       where  EST_CODIGO = var_est_codigo
        and   var_est_codigo is not null;
    
    --  Declaration of UpdateParentRestrict constraint for "KILOEQUIVALENCIAXEST"
    cursor cfk3_estacion(var_est_codigo varchar) is
       select 1
       from   KILOEQUIVALENCIAXEST
       where  EST_CODIGO = var_est_codigo
        and   var_est_codigo is not null;
    
    --  Declaration of UpdateParentRestrict constraint for "BULTO"
    cursor cfk4_estacion(var_est_codigo varchar) is
       select 1
       from   BULTO
       where  BUL_ORIGEN = var_est_codigo
        and   var_est_codigo is not null;
    
    --  Declaration of UpdateParentRestrict constraint for "BULTO"
    cursor cfk5_estacion(var_est_codigo varchar) is
       select 1
       from   BULTO
       where  BUL_DESTINO = var_est_codigo
        and   var_est_codigo is not null;
    
    --  Declaration of UpdateParentRestrict constraint for "DOCUMENTO"
    cursor cfk6_estacion(var_est_codigo varchar) is
       select 1
       from   DOCUMENTO
       where  DOC_ORIGEN = var_est_codigo
        and   var_est_codigo is not null;
    
    --  Declaration of UpdateParentRestrict constraint for "DOCUMENTO"
    cursor cfk7_estacion(var_est_codigo varchar) is
       select 1
       from   DOCUMENTO
       where  DOC_DESTINO = var_est_codigo
        and   var_est_codigo is not null;

begin
    seq := IntegrityPackage.GetNestLevel;
    
    --  Parent "CIUDAD" must exist when updating a child in "ESTACION"
    if (:new.CIU_CODIGO is not null) and (seq = 0) then 
       open  cpk1_estacion(:new.CIU_CODIGO);
       fetch cpk1_estacion into dummy;
       found := cpk1_estacion%FOUND;
       close cpk1_estacion;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "CIUDAD". Cannot update child in "ESTACION".';
          raise integrity_error;
       end if;
    end if;
    
    --  Parent "MONEDA" must exist when updating a child in "ESTACION"
    if (:new.MON_CODIGO is not null) and (seq = 0) then 
       open  cpk2_estacion(:new.MON_CODIGO);
       fetch cpk2_estacion into dummy;
       found := cpk2_estacion%FOUND;
       close cpk2_estacion;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "MONEDA". Cannot update child in "ESTACION".';
          raise integrity_error;
       end if;
    end if;
    
    --  Cannot modify parent code in "ESTACION" if children still exist in "OFICINA"
    if (updating('EST_CODIGO') and :old.EST_CODIGO != :new.EST_CODIGO) then
       open  cfk1_estacion(:old.EST_CODIGO);
       fetch cfk1_estacion into dummy;
       found := cfk1_estacion%FOUND;
       close cfk1_estacion;
       if found then
          errno  := -20005;
          errmsg := 'Children still exist in "OFICINA". Cannot modify parent code in "ESTACION".';
          raise integrity_error;
       end if;
    end if;
    
    --  Cannot modify parent code in "ESTACION" if children still exist in "IMPDESCARXEST"
    if (updating('EST_CODIGO') and :old.EST_CODIGO != :new.EST_CODIGO) then
       open  cfk2_estacion(:old.EST_CODIGO);
       fetch cfk2_estacion into dummy;
       found := cfk2_estacion%FOUND;
       close cfk2_estacion;
       if found then
          errno  := -20005;
          errmsg := 'Children still exist in "IMPDESCARXEST". Cannot modify parent code in "ESTACION".';
          raise integrity_error;
       end if;
    end if;
    
    --  Cannot modify parent code in "ESTACION" if children still exist in "KILOEQUIVALENCIAXEST"
    if (updating('EST_CODIGO') and :old.EST_CODIGO != :new.EST_CODIGO) then
       open  cfk3_estacion(:old.EST_CODIGO);
       fetch cfk3_estacion into dummy;
       found := cfk3_estacion%FOUND;
       close cfk3_estacion;
       if found then
          errno  := -20005;
          errmsg := 'Children still exist in "KILOEQUIVALENCIAXEST". Cannot modify parent code in "ESTACION".';
          raise integrity_error;
       end if;
    end if;
    
    --  Cannot modify parent code in "ESTACION" if children still exist in "BULTO"
    if (updating('EST_CODIGO') and :old.EST_CODIGO != :new.EST_CODIGO) then
       open  cfk4_estacion(:old.EST_CODIGO);
       fetch cfk4_estacion into dummy;
       found := cfk4_estacion%FOUND;
       close cfk4_estacion;
       if found then
          errno  := -20005;
          errmsg := 'Children still exist in "BULTO". Cannot modify parent code in "ESTACION".';
          raise integrity_error;
       end if;
    end if;
    
    --  Cannot modify parent code in "ESTACION" if children still exist in "BULTO"
    if (updating('EST_CODIGO') and :old.EST_CODIGO != :new.EST_CODIGO) then
       open  cfk5_estacion(:old.EST_CODIGO);
       fetch cfk5_estacion into dummy;
       found := cfk5_estacion%FOUND;
       close cfk5_estacion;
       if found then
          errno  := -20005;
          errmsg := 'Children still exist in "BULTO". Cannot modify parent code in "ESTACION".';
          raise integrity_error;
       end if;
    end if;
    
    --  Cannot modify parent code in "ESTACION" if children still exist in "DOCUMENTO"
    if (updating('EST_CODIGO') and :old.EST_CODIGO != :new.EST_CODIGO) then
       open  cfk6_estacion(:old.EST_CODIGO);
       fetch cfk6_estacion into dummy;
       found := cfk6_estacion%FOUND;
       close cfk6_estacion;
       if found then
          errno  := -20005;
          errmsg := 'Children still exist in "DOCUMENTO". Cannot modify parent code in "ESTACION".';
          raise integrity_error;
       end if;
    end if;
    
    --  Cannot modify parent code in "ESTACION" if children still exist in "DOCUMENTO"
    if (updating('EST_CODIGO') and :old.EST_CODIGO != :new.EST_CODIGO) then
       open  cfk7_estacion(:old.EST_CODIGO);
       fetch cfk7_estacion into dummy;
       found := cfk7_estacion%FOUND;
       close cfk7_estacion;
       if found then
          errno  := -20005;
          errmsg := 'Children still exist in "DOCUMENTO". Cannot modify parent code in "ESTACION".';
          raise integrity_error;
       end if;
    end if;

--  Errors handling
exception
    when integrity_error then
       raise_application_error(errno, errmsg);
end;
/

--  After update trigger "tua_estacion" for table "ESTACION"
create trigger tua_estacion after update
of EST_CODIGO,
   CIU_CODIGO,
   MON_CODIGO
on ESTACION for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
begin
    IntegrityPackage.NextNestLevel;
    
    --  Modify parent code of "ESTACION" for all children in "TIPOCAMBIO"
    if (updating('EST_CODIGO') and :old.EST_CODIGO != :new.EST_CODIGO) then
       update TIPOCAMBIO
        set   EST_CODIGO = :new.EST_CODIGO
       where  EST_CODIGO = :old.EST_CODIGO;
    end if;
    IntegrityPackage.PreviousNestLevel;

--  Errors handling
exception
    when integrity_error then
       begin
       IntegrityPackage.InitNestLevel;
       raise_application_error(errno, errmsg);
       end;
end;
/

--  After delete trigger "tda_estacion" for table "ESTACION"
create trigger tda_estacion after delete
on ESTACION for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;

begin
    IntegrityPackage.NextNestLevel;
    
    --  Delete all children in "TIPOCAMBIO"
    delete TIPOCAMBIO
    where  EST_CODIGO = :old.EST_CODIGO;
    IntegrityPackage.PreviousNestLevel;

--  Errors handling
exception
    when integrity_error then
       begin
       IntegrityPackage.InitNestLevel;
       raise_application_error(errno, errmsg);
       end;
end;
/

--  After update trigger "tua_forma_pago" for table "FORMA_PAGO"
create trigger tua_forma_pago after update
of FORPAG_ID
on FORMA_PAGO for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
begin
    IntegrityPackage.NextNestLevel;
    
    --  Modify parent code of "FORMA_PAGO" for all children in "FORMA_PAGOXDOC"
    if (updating('FORPAG_ID') and :old.FORPAG_ID != :new.FORPAG_ID) then
       update FORMA_PAGOXDOC
        set   FORPAG_ID = :new.FORPAG_ID
       where  FORPAG_ID = :old.FORPAG_ID;
    end if;
    IntegrityPackage.PreviousNestLevel;

--  Errors handling
exception
    when integrity_error then
       begin
       IntegrityPackage.InitNestLevel;
       raise_application_error(errno, errmsg);
       end;
end;
/

--  After delete trigger "tda_forma_pago" for table "FORMA_PAGO"
create trigger tda_forma_pago after delete
on FORMA_PAGO for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;

begin
    IntegrityPackage.NextNestLevel;
    
    --  Delete all children in "FORMA_PAGOXDOC"
    delete FORMA_PAGOXDOC
    where  FORPAG_ID = :old.FORPAG_ID;
    IntegrityPackage.PreviousNestLevel;

--  Errors handling
exception
    when integrity_error then
       begin
       IntegrityPackage.InitNestLevel;
       raise_application_error(errno, errmsg);
       end;
end;
/

--  Before update trigger "tub_forma_pagoxdoc" for table "FORMA_PAGOXDOC"
create trigger tub_forma_pagoxdoc before update
of FORPAG_ID,
   TAR_ID,
   STO_ID,
   OFI_ID,
   STOTIP_ID,
   STO_NRO
on FORMA_PAGOXDOC for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
    seq NUMBER;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "FORMA_PAGO"
    cursor cpk1_forma_pagoxdoc(var_forpag_id varchar) is
       select 1
       from   FORMA_PAGO
       where  FORPAG_ID = var_forpag_id
        and   var_forpag_id is not null;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "TARJETA"
    cursor cpk2_forma_pagoxdoc(var_tar_id varchar) is
       select 1
       from   TARJETA
       where  TAR_ID = var_tar_id
        and   var_tar_id is not null;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "DOCUMENTO"
    cursor cpk3_forma_pagoxdoc(var_sto_id number,
                               var_ofi_id varchar,
                               var_stotip_id varchar,
                               var_sto_nro varchar) is
       select 1
       from   DOCUMENTO
       where  STO_ID = var_sto_id
        and   OFI_ID = var_ofi_id
        and   STOTIP_ID = var_stotip_id
        and   STO_NRO = var_sto_nro
        and   var_sto_id is not null
        and   var_ofi_id is not null
        and   var_stotip_id is not null
        and   var_sto_nro is not null;

begin
    seq := IntegrityPackage.GetNestLevel;
    
    --  Parent "FORMA_PAGO" must exist when updating a child in "FORMA_PAGOXDOC"
    if (:new.FORPAG_ID is not null) and (seq = 0) then 
       open  cpk1_forma_pagoxdoc(:new.FORPAG_ID);
       fetch cpk1_forma_pagoxdoc into dummy;
       found := cpk1_forma_pagoxdoc%FOUND;
       close cpk1_forma_pagoxdoc;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "FORMA_PAGO". Cannot update child in "FORMA_PAGOXDOC".';
          raise integrity_error;
       end if;
    end if;
    
    --  Parent "TARJETA" must exist when updating a child in "FORMA_PAGOXDOC"
    if (:new.TAR_ID is not null) and (seq = 0) then 
       open  cpk2_forma_pagoxdoc(:new.TAR_ID);
       fetch cpk2_forma_pagoxdoc into dummy;
       found := cpk2_forma_pagoxdoc%FOUND;
       close cpk2_forma_pagoxdoc;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "TARJETA". Cannot update child in "FORMA_PAGOXDOC".';
          raise integrity_error;
       end if;
    end if;
    
    --  Parent "DOCUMENTO" must exist when updating a child in "FORMA_PAGOXDOC"
    if (:new.STO_ID is not null) and
       (:new.OFI_ID is not null) and
       (:new.STOTIP_ID is not null) and
       (:new.STO_NRO is not null) and (seq = 0) then 
       open  cpk3_forma_pagoxdoc(:new.STO_ID,
                                 :new.OFI_ID,
                                 :new.STOTIP_ID,
                                 :new.STO_NRO);
       fetch cpk3_forma_pagoxdoc into dummy;
       found := cpk3_forma_pagoxdoc%FOUND;
       close cpk3_forma_pagoxdoc;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "DOCUMENTO". Cannot update child in "FORMA_PAGOXDOC".';
          raise integrity_error;
       end if;
    end if;

--  Errors handling
exception
    when integrity_error then
       raise_application_error(errno, errmsg);
end;
/

CREATE SEQUENCE IMPDESCARGO_SEQ INCREMENT BY 1 
    START WITH 1 MAXVALUE 1.0E28 MINVALUE 1 NOCYCLE 
    CACHE 20 NOORDER;
/
create trigger IMPDESCARGO_TRIGGER 
BEFORE INSERT ON "IMPDESCARGO" 
FOR EACH ROW
begin select IMPDESCARGO_SEQ.nextval into :new.IDC_ID from dual;
end;
/

CREATE SEQUENCE MANIFIESTO_EMBARQUE_SEQ INCREMENT BY 1 
    START WITH 1 MAXVALUE 1.0E28 MINVALUE 1 NOCYCLE 
    CACHE 20 NOORDER;
/
create trigger MANIFIESTO_EMBARQUE_TRIGGER 
BEFORE INSERT ON "MANIFIESTO_EMBARQUE" 
FOR EACH ROW
begin select MANIFIESTO_EMBARQUE_SEQ.nextval into :new.MANEMB_ID from dual;
end;
/

--  After update trigger "tua_moneda" for table "MONEDA"
create trigger tua_moneda after update
of MON_CODIGO
on MONEDA for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
begin
    IntegrityPackage.NextNestLevel;
    
    --  Modify parent code of "MONEDA" for all children in "ESTACION"
    if (updating('MON_CODIGO') and :old.MON_CODIGO != :new.MON_CODIGO) then
       update ESTACION
        set   MON_CODIGO = :new.MON_CODIGO
       where  MON_CODIGO = :old.MON_CODIGO;
    end if;
    
    --  Modify parent code of "MONEDA" for all children in "TIPOCAMBIO"
    if (updating('MON_CODIGO') and :old.MON_CODIGO != :new.MON_CODIGO) then
       update TIPOCAMBIO
        set   MON_CODIGO = :new.MON_CODIGO
       where  MON_CODIGO = :old.MON_CODIGO;
    end if;
    IntegrityPackage.PreviousNestLevel;

--  Errors handling
exception
    when integrity_error then
       begin
       IntegrityPackage.InitNestLevel;
       raise_application_error(errno, errmsg);
       end;
end;
/

--  After delete trigger "tda_moneda" for table "MONEDA"
create trigger tda_moneda after delete
on MONEDA for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;

begin
    IntegrityPackage.NextNestLevel;
    
    --  Delete all children in "ESTACION"
    delete ESTACION
    where  MON_CODIGO = :old.MON_CODIGO;
    
    --  Delete all children in "TIPOCAMBIO"
    delete TIPOCAMBIO
    where  MON_CODIGO = :old.MON_CODIGO;
    IntegrityPackage.PreviousNestLevel;

--  Errors handling
exception
    when integrity_error then
       begin
       IntegrityPackage.InitNestLevel;
       raise_application_error(errno, errmsg);
       end;
end;
/

--  After update trigger "tua_pais" for table "PAIS"
create trigger tua_pais after update
of PAI_CODIGO
on PAIS for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
begin
    IntegrityPackage.NextNestLevel;
    
    --  Modify parent code of "PAIS" for all children in "CIUDAD"
    if (updating('PAI_CODIGO') and :old.PAI_CODIGO != :new.PAI_CODIGO) then
       update CIUDAD
        set   PAI_CODIGO = :new.PAI_CODIGO
       where  PAI_CODIGO = :old.PAI_CODIGO;
    end if;
    IntegrityPackage.PreviousNestLevel;

--  Errors handling
exception
    when integrity_error then
       begin
       IntegrityPackage.InitNestLevel;
       raise_application_error(errno, errmsg);
       end;
end;
/

--  After delete trigger "tda_pais" for table "PAIS"
create trigger tda_pais after delete
on PAIS for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;

begin
    IntegrityPackage.NextNestLevel;
    
    --  Delete all children in "CIUDAD"
    delete CIUDAD
    where  PAI_CODIGO = :old.PAI_CODIGO;
    IntegrityPackage.PreviousNestLevel;

--  Errors handling
exception
    when integrity_error then
       begin
       IntegrityPackage.InitNestLevel;
       raise_application_error(errno, errmsg);
       end;
end;
/

CREATE SEQUENCE REPORTE_VENTA_SEQ INCREMENT BY 1 
    START WITH 1 MAXVALUE 1.0E28 MINVALUE 1 NOCYCLE 
    CACHE 20 NOORDER;
/
create trigger REPORTE_VENTA_TRIGGER 
BEFORE INSERT ON "REPORTE_VENTA" 
FOR EACH ROW
begin select REPORTE_VENTA_SEQ.nextval into :new.REPVEN_ID from dual;
end;
/

CREATE SEQUENCE SUBAPLICACION_SEQ INCREMENT BY 1 
    START WITH 1 MAXVALUE 1.0E28 MINVALUE 1 NOCYCLE 
    CACHE 20 NOORDER;
/
create trigger SUBAPLICACION_TRIGGER 
BEFORE INSERT ON "SUBAPLICACION" 
FOR EACH ROW
begin select SUBAPLICACION_SEQ.nextval into :new.ID_SUBAPLICACION from dual;
end;
/

--  Before update trigger "tub_subaplicacion" for table "SUBAPLICACION"
create trigger tub_subaplicacion before update
of ID_SUBAPLICACION,
   ID_APLICACION
on SUBAPLICACION for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
    seq NUMBER;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "APLICACION"
    cursor cpk1_subaplicacion(var_id_aplicacion integer) is
       select 1
       from   APLICACION
       where  ID_APLICACION = var_id_aplicacion
        and   var_id_aplicacion is not null;

begin
    seq := IntegrityPackage.GetNestLevel;
    
    --  Parent "APLICACION" must exist when updating a child in "SUBAPLICACION"
    if (:new.ID_APLICACION is not null) and (seq = 0) then 
       open  cpk1_subaplicacion(:new.ID_APLICACION);
       fetch cpk1_subaplicacion into dummy;
       found := cpk1_subaplicacion%FOUND;
       close cpk1_subaplicacion;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "APLICACION". Cannot update child in "SUBAPLICACION".';
          raise integrity_error;
       end if;
    end if;

--  Errors handling
exception
    when integrity_error then
       raise_application_error(errno, errmsg);
end;
/

--  After update trigger "tua_tarjeta" for table "TARJETA"
create trigger tua_tarjeta after update
of TAR_ID
on TARJETA for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
begin
    IntegrityPackage.NextNestLevel;
    
    --  Modify parent code of "TARJETA" for all children in "FORMA_PAGOXDOC"
    if (updating('TAR_ID') and :old.TAR_ID != :new.TAR_ID) then
       update FORMA_PAGOXDOC
        set   TAR_ID = :new.TAR_ID
       where  TAR_ID = :old.TAR_ID;
    end if;
    IntegrityPackage.PreviousNestLevel;

--  Errors handling
exception
    when integrity_error then
       begin
       IntegrityPackage.InitNestLevel;
       raise_application_error(errno, errmsg);
       end;
end;
/

--  After delete trigger "tda_tarjeta" for table "TARJETA"
create trigger tda_tarjeta after delete
on TARJETA for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;

begin
    IntegrityPackage.NextNestLevel;
    
    --  Delete all children in "FORMA_PAGOXDOC"
    delete FORMA_PAGOXDOC
    where  TAR_ID = :old.TAR_ID;
    IntegrityPackage.PreviousNestLevel;

--  Errors handling
exception
    when integrity_error then
       begin
       IntegrityPackage.InitNestLevel;
       raise_application_error(errno, errmsg);
       end;
end;
/

--  Before insert trigger "tib_terminal" for table "TERMINAL"
create trigger tib_terminal before insert
on TERMINAL for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
    
    --  Declaration of InsertChildParentExist constraint for the parent "OFICINA"
    cursor cpk1_terminal(var_ofi_id varchar) is
       select 1
       from   OFICINA
       where  OFI_ID = var_ofi_id
        and   var_ofi_id is not null;

begin
    
    --  Parent "OFICINA" must exist when inserting a child in "TERMINAL"
    if :new.OFI_ID is not null then
       open  cpk1_terminal(:new.OFI_ID);
       fetch cpk1_terminal into dummy;
       found := cpk1_terminal%FOUND;
       close cpk1_terminal;
       if not found then
          errno  := -20002;
          errmsg := 'Parent does not exist in "OFICINA". Cannot create child in "TERMINAL".';
          raise integrity_error;
       end if;
    end if;

--  Errors handling
exception
    when integrity_error then
       raise_application_error(errno, errmsg);
end;
/

--  Before update trigger "tub_tipocambio" for table "TIPOCAMBIO"
create trigger tub_tipocambio before update
of MON_CODIGO,
   EST_CODIGO
on TIPOCAMBIO for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
    seq NUMBER;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "ESTACION"
    cursor cpk1_tipocambio(var_est_codigo varchar) is
       select 1
       from   ESTACION
       where  EST_CODIGO = var_est_codigo
        and   var_est_codigo is not null;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "MONEDA"
    cursor cpk2_tipocambio(var_mon_codigo varchar) is
       select 1
       from   MONEDA
       where  MON_CODIGO = var_mon_codigo
        and   var_mon_codigo is not null;

begin
    seq := IntegrityPackage.GetNestLevel;
    
    --  Parent "ESTACION" must exist when updating a child in "TIPOCAMBIO"
    if (:new.EST_CODIGO is not null) and (seq = 0) then 
       open  cpk1_tipocambio(:new.EST_CODIGO);
       fetch cpk1_tipocambio into dummy;
       found := cpk1_tipocambio%FOUND;
       close cpk1_tipocambio;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "ESTACION". Cannot update child in "TIPOCAMBIO".';
          raise integrity_error;
       end if;
    end if;
    
    --  Parent "MONEDA" must exist when updating a child in "TIPOCAMBIO"
    if (:new.MON_CODIGO is not null) and (seq = 0) then 
       open  cpk2_tipocambio(:new.MON_CODIGO);
       fetch cpk2_tipocambio into dummy;
       found := cpk2_tipocambio%FOUND;
       close cpk2_tipocambio;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "MONEDA". Cannot update child in "TIPOCAMBIO".';
          raise integrity_error;
       end if;
    end if;

--  Errors handling
exception
    when integrity_error then
       raise_application_error(errno, errmsg);
end;
/

--  After update trigger "tua_usuario" for table "USUARIO"
create trigger tua_usuario after update
of USU_CODIGO
on USUARIO for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
begin
    IntegrityPackage.NextNestLevel;
    
    --  Modify parent code of "USUARIO" for all children in "USUARIO_APLICACION"
    if (updating('USU_CODIGO') and :old.USU_CODIGO != :new.USU_CODIGO) then
       update USUARIO_APLICACION
        set   USU_CODIGO = :new.USU_CODIGO
       where  USU_CODIGO = :old.USU_CODIGO;
    end if;
    IntegrityPackage.PreviousNestLevel;

--  Errors handling
exception
    when integrity_error then
       begin
       IntegrityPackage.InitNestLevel;
       raise_application_error(errno, errmsg);
       end;
end;
/

--  After delete trigger "tda_usuario" for table "USUARIO"
create trigger tda_usuario after delete
on USUARIO for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;

begin
    IntegrityPackage.NextNestLevel;
    
    --  Delete all children in "USUARIO_APLICACION"
    delete USUARIO_APLICACION
    where  USU_CODIGO = :old.USU_CODIGO;
    IntegrityPackage.PreviousNestLevel;

--  Errors handling
exception
    when integrity_error then
       begin
       IntegrityPackage.InitNestLevel;
       raise_application_error(errno, errmsg);
       end;
end;
/

--  Before update trigger "tub_usuario_aplicacion" for table "USUARIO_APLICACION"
create trigger tub_usuario_aplicacion before update
of USU_CODIGO,
   ID_APLICACION
on USUARIO_APLICACION for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
    seq NUMBER;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "USUARIO"
    cursor cpk1_usuario_aplicacion(var_usu_codigo varchar) is
       select 1
       from   USUARIO
       where  USU_CODIGO = var_usu_codigo
        and   var_usu_codigo is not null;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "APLICACION"
    cursor cpk2_usuario_aplicacion(var_id_aplicacion integer) is
       select 1
       from   APLICACION
       where  ID_APLICACION = var_id_aplicacion
        and   var_id_aplicacion is not null;

begin
    seq := IntegrityPackage.GetNestLevel;
    
    --  Parent "USUARIO" must exist when updating a child in "USUARIO_APLICACION"
    if (:new.USU_CODIGO is not null) and (seq = 0) then 
       open  cpk1_usuario_aplicacion(:new.USU_CODIGO);
       fetch cpk1_usuario_aplicacion into dummy;
       found := cpk1_usuario_aplicacion%FOUND;
       close cpk1_usuario_aplicacion;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "USUARIO". Cannot update child in "USUARIO_APLICACION".';
          raise integrity_error;
       end if;
    end if;
    
    --  Parent "APLICACION" must exist when updating a child in "USUARIO_APLICACION"
    if (:new.ID_APLICACION is not null) and (seq = 0) then 
       open  cpk2_usuario_aplicacion(:new.ID_APLICACION);
       fetch cpk2_usuario_aplicacion into dummy;
       found := cpk2_usuario_aplicacion%FOUND;
       close cpk2_usuario_aplicacion;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "APLICACION". Cannot update child in "USUARIO_APLICACION".';
          raise integrity_error;
       end if;
    end if;

--  Errors handling
exception
    when integrity_error then
       raise_application_error(errno, errmsg);
end;
/

