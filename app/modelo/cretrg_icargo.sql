-- ============================================================
--   Database name:  MODEL_1                                   
--   DBMS name:      ORACLE Version 8                          
--   Created on:     21/12/2003  23:35                         
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

--  Before update trigger "tub_agencia" for table "AGENCIA"
create trigger tub_agencia before update
of AGE_CODIGO,
   TIPAGE_CODIGO,
   EST_ID
on AGENCIA for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
    seq NUMBER;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "TIPOAGENCIA"
    cursor cpk1_agencia(var_tipage_codigo varchar) is
       select 1
       from   TIPOAGENCIA
       where  TIPAGE_CODIGO = var_tipage_codigo
        and   var_tipage_codigo is not null;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "ESTACION"
    cursor cpk2_agencia(var_est_id number) is
       select 1
       from   ESTACION
       where  EST_ID = var_est_id
        and   var_est_id is not null;

begin
    seq := IntegrityPackage.GetNestLevel;
    
    --  Parent "TIPOAGENCIA" must exist when updating a child in "AGENCIA"
    if (:new.TIPAGE_CODIGO is not null) and (seq = 0) then 
       open  cpk1_agencia(:new.TIPAGE_CODIGO);
       fetch cpk1_agencia into dummy;
       found := cpk1_agencia%FOUND;
       close cpk1_agencia;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "TIPOAGENCIA". Cannot update child in "AGENCIA".';
          raise integrity_error;
       end if;
    end if;
    
    --  Parent "ESTACION" must exist when updating a child in "AGENCIA"
    if (:new.EST_ID is not null) and (seq = 0) then 
       open  cpk2_agencia(:new.EST_ID);
       fetch cpk2_agencia into dummy;
       found := cpk2_agencia%FOUND;
       close cpk2_agencia;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "ESTACION". Cannot update child in "AGENCIA".';
          raise integrity_error;
       end if;
    end if;

--  Errors handling
exception
    when integrity_error then
       raise_application_error(errno, errmsg);
end;
/

CREATE SEQUENCE "ICARGO"."APLICACION_SEQ" INCREMENT BY 1 
    START WITH 1 MAXVALUE 1.0E28 MINVALUE 1 NOCYCLE 
    CACHE 20 NOORDER;
/
create trigger "ICARGO"."APLICACION_TRIGGER" 
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
   PAI_CODIGO,
   EST_ID
on CIUDAD for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
    seq NUMBER;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "ESTACION"
    cursor cpk1_ciudad(var_est_id number) is
       select 1
       from   ESTACION
       where  EST_ID = var_est_id
        and   var_est_id is not null;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "PAIS"
    cursor cpk2_ciudad(var_pai_codigo varchar) is
       select 1
       from   PAIS
       where  PAI_CODIGO = var_pai_codigo
        and   var_pai_codigo is not null;

begin
    seq := IntegrityPackage.GetNestLevel;
    
    --  Parent "ESTACION" must exist when updating a child in "CIUDAD"
    if (:new.EST_ID is not null) and (seq = 0) then 
       open  cpk1_ciudad(:new.EST_ID);
       fetch cpk1_ciudad into dummy;
       found := cpk1_ciudad%FOUND;
       close cpk1_ciudad;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "ESTACION". Cannot update child in "CIUDAD".';
          raise integrity_error;
       end if;
    end if;
    
    --  Parent "PAIS" must exist when updating a child in "CIUDAD"
    if (:new.PAI_CODIGO is not null) and (seq = 0) then 
       open  cpk2_ciudad(:new.PAI_CODIGO);
       fetch cpk2_ciudad into dummy;
       found := cpk2_ciudad%FOUND;
       close cpk2_ciudad;
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
   PAI_CODIGO,
   EST_ID
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

--  Before update trigger "tub_cliente" for table "CLIENTE"
create trigger tub_cliente before update
of CLI_CODIGO,
   EST_ID
on CLIENTE for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
    seq NUMBER;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "ESTACION"
    cursor cpk1_cliente(var_est_id number) is
       select 1
       from   ESTACION
       where  EST_ID = var_est_id
        and   var_est_id is not null;

begin
    seq := IntegrityPackage.GetNestLevel;
    
    --  Parent "ESTACION" must exist when updating a child in "CLIENTE"
    if (:new.EST_ID is not null) and (seq = 0) then 
       open  cpk1_cliente(:new.EST_ID);
       fetch cpk1_cliente into dummy;
       found := cpk1_cliente%FOUND;
       close cpk1_cliente;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "ESTACION". Cannot update child in "CLIENTE".';
          raise integrity_error;
       end if;
    end if;

--  Errors handling
exception
    when integrity_error then
       raise_application_error(errno, errmsg);
end;
/

--  After update trigger "tua_cliente" for table "CLIENTE"
create trigger tua_cliente after update
of CLI_CODIGO,
   EST_ID
on CLIENTE for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
begin
    IntegrityPackage.NextNestLevel;
    
    --  Modify parent code of "CLIENTE" for all children in "KILOEQUIVALENCIA"
    if (updating('CLI_CODIGO') and :old.CLI_CODIGO != :new.CLI_CODIGO) then
       update KILOEQUIVALENCIA
        set   CLI_CODIGO = :new.CLI_CODIGO
       where  CLI_CODIGO = :old.CLI_CODIGO;
    end if;
    
    --  Modify parent code of "CLIENTE" for all children in "GUIA"
    if (updating('CLI_CODIGO') and :old.CLI_CODIGO != :new.CLI_CODIGO) then
       update GUIA
        set   CLI_CODIGO = :new.CLI_CODIGO
       where  CLI_CODIGO = :old.CLI_CODIGO;
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

--  After delete trigger "tda_cliente" for table "CLIENTE"
create trigger tda_cliente after delete
on CLIENTE for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;

begin
    IntegrityPackage.NextNestLevel;
    
    --  Delete all children in "KILOEQUIVALENCIA"
    delete KILOEQUIVALENCIA
    where  CLI_CODIGO = :old.CLI_CODIGO;
    
    --  Delete all children in "GUIA"
    delete GUIA
    where  CLI_CODIGO = :old.CLI_CODIGO;
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

--  After update trigger "tua_entregado_en" for table "ENTREGADO_EN"
create trigger tua_entregado_en after update
of ENT_ID
on ENTREGADO_EN for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
begin
    IntegrityPackage.NextNestLevel;
    
    --  Modify parent code of "ENTREGADO_EN" for all children in "GUIA"
    if (updating('ENT_ID') and :old.ENT_ID != :new.ENT_ID) then
       update GUIA
        set   ENT_ID = :new.ENT_ID
       where  ENT_ID = :old.ENT_ID;
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

--  After delete trigger "tda_entregado_en" for table "ENTREGADO_EN"
create trigger tda_entregado_en after delete
on ENTREGADO_EN for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;

begin
    IntegrityPackage.NextNestLevel;
    
    --  Delete all children in "GUIA"
    delete GUIA
    where  ENT_ID = :old.ENT_ID;
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
of EST_ID,
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

--  Errors handling
exception
    when integrity_error then
       raise_application_error(errno, errmsg);
end;
/

--  After update trigger "tua_estacion" for table "ESTACION"
create trigger tua_estacion after update
of EST_ID,
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
    
    --  Modify parent code of "ESTACION" for all children in "CIUDAD"
    if (updating('EST_ID') and :old.EST_ID != :new.EST_ID) then
       update CIUDAD
        set   EST_ID = :new.EST_ID
       where  EST_ID = :old.EST_ID;
    end if;
    
    --  Modify parent code of "ESTACION" for all children in "CLIENTE"
    if (updating('EST_ID') and :old.EST_ID != :new.EST_ID) then
       update CLIENTE
        set   EST_ID = :new.EST_ID
       where  EST_ID = :old.EST_ID;
    end if;
    
    --  Modify parent code of "ESTACION" for all children in "TIPOCAMBIO"
    if (updating('EST_ID') and :old.EST_ID != :new.EST_ID) then
       update TIPOCAMBIO
        set   EST_ID = :new.EST_ID
       where  EST_ID = :old.EST_ID;
    end if;
    
    --  Modify parent code of "ESTACION" for all children in "KILOEQUIVALENCIA"
    if (updating('EST_ID') and :old.EST_ID != :new.EST_ID) then
       update KILOEQUIVALENCIA
        set   CLI_CODIGO = :new.EST_ID
       where  CLI_CODIGO = :old.EST_ID;
    end if;
    
    --  Modify parent code of "ESTACION" for all children in "USUARIO"
    if (updating('EST_ID') and :old.EST_ID != :new.EST_ID) then
       update USUARIO
        set   EST_ID = :new.EST_ID
       where  EST_ID = :old.EST_ID;
    end if;
    
    --  Modify parent code of "ESTACION" for all children in "AGENCIA"
    if (updating('EST_ID') and :old.EST_ID != :new.EST_ID) then
       update AGENCIA
        set   EST_ID = :new.EST_ID
       where  EST_ID = :old.EST_ID;
    end if;
    
    --  Modify parent code of "ESTACION" for all children in "GUIA"
    if (updating('EST_ID') and :old.EST_ID != :new.EST_ID) then
       update GUIA
        set   EST_ORIGEN = :new.EST_ID
       where  EST_ORIGEN = :old.EST_ID;
    end if;
    
    --  Modify parent code of "ESTACION" for all children in "GUIA"
    if (updating('EST_ID') and :old.EST_ID != :new.EST_ID) then
       update GUIA
        set   EST_DESTINO = :new.EST_ID
       where  EST_DESTINO = :old.EST_ID;
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
    
    --  Delete all children in "CIUDAD"
    delete CIUDAD
    where  EST_ID = :old.EST_ID;
    
    --  Delete all children in "CLIENTE"
    delete CLIENTE
    where  EST_ID = :old.EST_ID;
    
    --  Delete all children in "TIPOCAMBIO"
    delete TIPOCAMBIO
    where  EST_ID = :old.EST_ID;
    
    --  Delete all children in "KILOEQUIVALENCIA"
    delete KILOEQUIVALENCIA
    where  CLI_CODIGO = :old.EST_ID;
    
    --  Delete all children in "USUARIO"
    delete USUARIO
    where  EST_ID = :old.EST_ID;
    
    --  Delete all children in "AGENCIA"
    delete AGENCIA
    where  EST_ID = :old.EST_ID;
    
    --  Delete all children in "GUIA"
    delete GUIA
    where  EST_ORIGEN = :old.EST_ID;
    
    --  Delete all children in "GUIA"
    delete GUIA
    where  EST_DESTINO = :old.EST_ID;
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
    
    --  Modify parent code of "FORMA_PAGO" for all children in "FORMA_PAGOXGUIA"
    if (updating('FORPAG_ID') and :old.FORPAG_ID != :new.FORPAG_ID) then
       update FORMA_PAGOXGUIA
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
    
    --  Delete all children in "FORMA_PAGOXGUIA"
    delete FORMA_PAGOXGUIA
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

--  Before update trigger "tub_forma_pagoxguia" for table "FORMA_PAGOXGUIA"
create trigger tub_forma_pagoxguia before update
of FORPAG_ID,
   TAR_ID,
   GUI_ID
on FORMA_PAGOXGUIA for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
    seq NUMBER;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "GUIA"
    cursor cpk1_forma_pagoxguia(var_gui_id number) is
       select 1
       from   GUIA
       where  GUI_ID = var_gui_id
        and   var_gui_id is not null;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "FORMA_PAGO"
    cursor cpk2_forma_pagoxguia(var_forpag_id varchar) is
       select 1
       from   FORMA_PAGO
       where  FORPAG_ID = var_forpag_id
        and   var_forpag_id is not null;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "TARJETA"
    cursor cpk3_forma_pagoxguia(var_tar_id varchar) is
       select 1
       from   TARJETA
       where  TAR_ID = var_tar_id
        and   var_tar_id is not null;

begin
    seq := IntegrityPackage.GetNestLevel;
    
    --  Parent "GUIA" must exist when updating a child in "FORMA_PAGOXGUIA"
    if (:new.GUI_ID is not null) and (seq = 0) then 
       open  cpk1_forma_pagoxguia(:new.GUI_ID);
       fetch cpk1_forma_pagoxguia into dummy;
       found := cpk1_forma_pagoxguia%FOUND;
       close cpk1_forma_pagoxguia;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "GUIA". Cannot update child in "FORMA_PAGOXGUIA".';
          raise integrity_error;
       end if;
    end if;
    
    --  Parent "FORMA_PAGO" must exist when updating a child in "FORMA_PAGOXGUIA"
    if (:new.FORPAG_ID is not null) and (seq = 0) then 
       open  cpk2_forma_pagoxguia(:new.FORPAG_ID);
       fetch cpk2_forma_pagoxguia into dummy;
       found := cpk2_forma_pagoxguia%FOUND;
       close cpk2_forma_pagoxguia;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "FORMA_PAGO". Cannot update child in "FORMA_PAGOXGUIA".';
          raise integrity_error;
       end if;
    end if;
    
    --  Parent "TARJETA" must exist when updating a child in "FORMA_PAGOXGUIA"
    if (:new.TAR_ID is not null) and (seq = 0) then 
       open  cpk3_forma_pagoxguia(:new.TAR_ID);
       fetch cpk3_forma_pagoxguia into dummy;
       found := cpk3_forma_pagoxguia%FOUND;
       close cpk3_forma_pagoxguia;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "TARJETA". Cannot update child in "FORMA_PAGOXGUIA".';
          raise integrity_error;
       end if;
    end if;

--  Errors handling
exception
    when integrity_error then
       raise_application_error(errno, errmsg);
end;
/

CREATE SEQUENCE "ICARGO"."GUIA_SEQ" INCREMENT BY 1 
    START WITH 1 MAXVALUE 1.0E28 MINVALUE 1 NOCYCLE 
    CACHE 20 NOORDER;
/
create trigger "ICARGO"."GUIA_TRIGGER" 
BEFORE INSERT ON "GUIA" 
FOR EACH ROW
begin select GUIA_SEQ.nextval into :new.GUI_ID from dual;
end;
/

--  Before update trigger "tub_guia" for table "GUIA"
create trigger tub_guia before update
of GUI_ID,
   TIPGUI_ID,
   TIPRO_ID,
   ENT_ID,
   CLI_CODIGO,
   REPVEN_ID,
   USU_CODIGO,
   EST_ORIGEN,
   EST_DESTINO
on GUIA for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
    seq NUMBER;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "TIPO_PRODUCTO"
    cursor cpk1_guia(var_tipro_id varchar) is
       select 1
       from   TIPO_PRODUCTO
       where  TIPRO_ID = var_tipro_id
        and   var_tipro_id is not null;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "ENTREGADO_EN"
    cursor cpk2_guia(var_ent_id varchar) is
       select 1
       from   ENTREGADO_EN
       where  ENT_ID = var_ent_id
        and   var_ent_id is not null;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "TIPO_GUIA"
    cursor cpk3_guia(var_tipgui_id varchar) is
       select 1
       from   TIPO_GUIA
       where  TIPGUI_ID = var_tipgui_id
        and   var_tipgui_id is not null;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "CLIENTE"
    cursor cpk4_guia(var_cli_codigo varchar) is
       select 1
       from   CLIENTE
       where  CLI_CODIGO = var_cli_codigo
        and   var_cli_codigo is not null;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "ESTACION"
    cursor cpk5_guia(var_est_origen number) is
       select 1
       from   ESTACION
       where  EST_ID = var_est_origen
        and   var_est_origen is not null;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "ESTACION"
    cursor cpk6_guia(var_est_destino number) is
       select 1
       from   ESTACION
       where  EST_ID = var_est_destino
        and   var_est_destino is not null;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "REPORTE_VENTA"
    cursor cpk7_guia(var_repven_id number) is
       select 1
       from   REPORTE_VENTA
       where  REPVEN_ID = var_repven_id
        and   var_repven_id is not null;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "USUARIO"
    cursor cpk8_guia(var_usu_codigo varchar) is
       select 1
       from   USUARIO
       where  USU_CODIGO = var_usu_codigo
        and   var_usu_codigo is not null;

begin
    seq := IntegrityPackage.GetNestLevel;
    
    --  Parent "TIPO_PRODUCTO" must exist when updating a child in "GUIA"
    if (:new.TIPRO_ID is not null) and (seq = 0) then 
       open  cpk1_guia(:new.TIPRO_ID);
       fetch cpk1_guia into dummy;
       found := cpk1_guia%FOUND;
       close cpk1_guia;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "TIPO_PRODUCTO". Cannot update child in "GUIA".';
          raise integrity_error;
       end if;
    end if;
    
    --  Parent "ENTREGADO_EN" must exist when updating a child in "GUIA"
    if (:new.ENT_ID is not null) and (seq = 0) then 
       open  cpk2_guia(:new.ENT_ID);
       fetch cpk2_guia into dummy;
       found := cpk2_guia%FOUND;
       close cpk2_guia;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "ENTREGADO_EN". Cannot update child in "GUIA".';
          raise integrity_error;
       end if;
    end if;
    
    --  Parent "TIPO_GUIA" must exist when updating a child in "GUIA"
    if (:new.TIPGUI_ID is not null) and (seq = 0) then 
       open  cpk3_guia(:new.TIPGUI_ID);
       fetch cpk3_guia into dummy;
       found := cpk3_guia%FOUND;
       close cpk3_guia;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "TIPO_GUIA". Cannot update child in "GUIA".';
          raise integrity_error;
       end if;
    end if;
    
    --  Parent "CLIENTE" must exist when updating a child in "GUIA"
    if (:new.CLI_CODIGO is not null) and (seq = 0) then 
       open  cpk4_guia(:new.CLI_CODIGO);
       fetch cpk4_guia into dummy;
       found := cpk4_guia%FOUND;
       close cpk4_guia;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "CLIENTE". Cannot update child in "GUIA".';
          raise integrity_error;
       end if;
    end if;
    
    --  Parent "ESTACION" must exist when updating a child in "GUIA"
    if (:new.EST_ORIGEN is not null) and (seq = 0) then 
       open  cpk5_guia(:new.EST_ORIGEN);
       fetch cpk5_guia into dummy;
       found := cpk5_guia%FOUND;
       close cpk5_guia;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "ESTACION". Cannot update child in "GUIA".';
          raise integrity_error;
       end if;
    end if;
    
    --  Parent "ESTACION" must exist when updating a child in "GUIA"
    if (:new.EST_DESTINO is not null) and (seq = 0) then 
       open  cpk6_guia(:new.EST_DESTINO);
       fetch cpk6_guia into dummy;
       found := cpk6_guia%FOUND;
       close cpk6_guia;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "ESTACION". Cannot update child in "GUIA".';
          raise integrity_error;
       end if;
    end if;
    
    --  Parent "REPORTE_VENTA" must exist when updating a child in "GUIA"
    if (:new.REPVEN_ID is not null) and (seq = 0) then 
       open  cpk7_guia(:new.REPVEN_ID);
       fetch cpk7_guia into dummy;
       found := cpk7_guia%FOUND;
       close cpk7_guia;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "REPORTE_VENTA". Cannot update child in "GUIA".';
          raise integrity_error;
       end if;
    end if;
    
    --  Parent "USUARIO" must exist when updating a child in "GUIA"
    if (:new.USU_CODIGO is not null) and (seq = 0) then 
       open  cpk8_guia(:new.USU_CODIGO);
       fetch cpk8_guia into dummy;
       found := cpk8_guia%FOUND;
       close cpk8_guia;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "USUARIO". Cannot update child in "GUIA".';
          raise integrity_error;
       end if;
    end if;

--  Errors handling
exception
    when integrity_error then
       raise_application_error(errno, errmsg);
end;
/

--  After update trigger "tua_guia" for table "GUIA"
create trigger tua_guia after update
of GUI_ID,
   TIPGUI_ID,
   TIPRO_ID,
   ENT_ID,
   CLI_CODIGO,
   REPVEN_ID,
   USU_CODIGO,
   EST_ORIGEN,
   EST_DESTINO
on GUIA for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
begin
    IntegrityPackage.NextNestLevel;
    
    --  Modify parent code of "GUIA" for all children in "FORMA_PAGOXGUIA"
    if (updating('GUI_ID') and :old.GUI_ID != :new.GUI_ID) then
       update FORMA_PAGOXGUIA
        set   GUI_ID = :new.GUI_ID
       where  GUI_ID = :old.GUI_ID;
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

--  After delete trigger "tda_guia" for table "GUIA"
create trigger tda_guia after delete
on GUIA for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;

begin
    IntegrityPackage.NextNestLevel;
    
    --  Delete all children in "FORMA_PAGOXGUIA"
    delete FORMA_PAGOXGUIA
    where  GUI_ID = :old.GUI_ID;
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

--  Before update trigger "tub_kiloequivalencia" for table "KILOEQUIVALENCIA"
create trigger tub_kiloequivalencia before update
of CLI_CODIGO,
   KILEQU_FECHA,
   MON_CODIGO,
   EST_ID
on KILOEQUIVALENCIA for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
    seq NUMBER;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "ESTACION"
    cursor cpk1_kiloequivalencia(var_cli_codigo number) is
       select 1
       from   ESTACION
       where  EST_ID = var_cli_codigo
        and   var_cli_codigo is not null;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "CLIENTE"
    cursor cpk2_kiloequivalencia(var_cli_codigo varchar) is
       select 1
       from   CLIENTE
       where  CLI_CODIGO = var_cli_codigo
        and   var_cli_codigo is not null;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "MONEDA"
    cursor cpk3_kiloequivalencia(var_mon_codigo varchar) is
       select 1
       from   MONEDA
       where  MON_CODIGO = var_mon_codigo
        and   var_mon_codigo is not null;

begin
    seq := IntegrityPackage.GetNestLevel;
    
    --  Parent "ESTACION" must exist when updating a child in "KILOEQUIVALENCIA"
    if (:new.CLI_CODIGO is not null) and (seq = 0) then 
       open  cpk1_kiloequivalencia(:new.CLI_CODIGO);
       fetch cpk1_kiloequivalencia into dummy;
       found := cpk1_kiloequivalencia%FOUND;
       close cpk1_kiloequivalencia;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "ESTACION". Cannot update child in "KILOEQUIVALENCIA".';
          raise integrity_error;
       end if;
    end if;
    
    --  Parent "CLIENTE" must exist when updating a child in "KILOEQUIVALENCIA"
    if (:new.CLI_CODIGO is not null) and (seq = 0) then 
       open  cpk2_kiloequivalencia(:new.CLI_CODIGO);
       fetch cpk2_kiloequivalencia into dummy;
       found := cpk2_kiloequivalencia%FOUND;
       close cpk2_kiloequivalencia;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "CLIENTE". Cannot update child in "KILOEQUIVALENCIA".';
          raise integrity_error;
       end if;
    end if;
    
    --  Parent "MONEDA" must exist when updating a child in "KILOEQUIVALENCIA"
    if (:new.MON_CODIGO is not null) and (seq = 0) then 
       open  cpk3_kiloequivalencia(:new.MON_CODIGO);
       fetch cpk3_kiloequivalencia into dummy;
       found := cpk3_kiloequivalencia%FOUND;
       close cpk3_kiloequivalencia;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "MONEDA". Cannot update child in "KILOEQUIVALENCIA".';
          raise integrity_error;
       end if;
    end if;

--  Errors handling
exception
    when integrity_error then
       raise_application_error(errno, errmsg);
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
    
    --  Modify parent code of "MONEDA" for all children in "KILOEQUIVALENCIA"
    if (updating('MON_CODIGO') and :old.MON_CODIGO != :new.MON_CODIGO) then
       update KILOEQUIVALENCIA
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
    
    --  Delete all children in "KILOEQUIVALENCIA"
    delete KILOEQUIVALENCIA
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

CREATE SEQUENCE "ICARGO"."REPORTE_VENTA_SEQ" INCREMENT BY 1 
    START WITH 1 MAXVALUE 1.0E28 MINVALUE 1 NOCYCLE 
    CACHE 20 NOORDER;
/
create trigger "ICARGO"."REPORTE_VENTA_TRIGGER" 
BEFORE INSERT ON "REPORTE_VENTA" 
FOR EACH ROW
begin select REPORTE_VENTA_SEQ.nextval into :new.REPVEN_ID from dual;
end;
/

--  After update trigger "tua_reporte_venta" for table "REPORTE_VENTA"
create trigger tua_reporte_venta after update
of REPVEN_ID
on REPORTE_VENTA for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
begin
    IntegrityPackage.NextNestLevel;
    
    --  Modify parent code of "REPORTE_VENTA" for all children in "GUIA"
    if (updating('REPVEN_ID') and :old.REPVEN_ID != :new.REPVEN_ID) then
       update GUIA
        set   REPVEN_ID = :new.REPVEN_ID
       where  REPVEN_ID = :old.REPVEN_ID;
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

--  After delete trigger "tda_reporte_venta" for table "REPORTE_VENTA"
create trigger tda_reporte_venta after delete
on REPORTE_VENTA for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;

begin
    IntegrityPackage.NextNestLevel;
    
    --  Delete all children in "GUIA"
    delete GUIA
    where  REPVEN_ID = :old.REPVEN_ID;
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

CREATE SEQUENCE "ICARGO"."SUBAPLICACION_SEQ" INCREMENT BY 1 
    START WITH 1 MAXVALUE 1.0E28 MINVALUE 1 NOCYCLE 
    CACHE 20 NOORDER;
/
create trigger "ICARGO"."SUBAPLICACION_TRIGGER" 
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
    
    --  Modify parent code of "TARJETA" for all children in "FORMA_PAGOXGUIA"
    if (updating('TAR_ID') and :old.TAR_ID != :new.TAR_ID) then
       update FORMA_PAGOXGUIA
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
    
    --  Delete all children in "FORMA_PAGOXGUIA"
    delete FORMA_PAGOXGUIA
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

--  After update trigger "tua_tipo_guia" for table "TIPO_GUIA"
create trigger tua_tipo_guia after update
of TIPGUI_ID
on TIPO_GUIA for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
begin
    IntegrityPackage.NextNestLevel;
    
    --  Modify parent code of "TIPO_GUIA" for all children in "GUIA"
    if (updating('TIPGUI_ID') and :old.TIPGUI_ID != :new.TIPGUI_ID) then
       update GUIA
        set   TIPGUI_ID = :new.TIPGUI_ID
       where  TIPGUI_ID = :old.TIPGUI_ID;
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

--  After delete trigger "tda_tipo_guia" for table "TIPO_GUIA"
create trigger tda_tipo_guia after delete
on TIPO_GUIA for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;

begin
    IntegrityPackage.NextNestLevel;
    
    --  Delete all children in "GUIA"
    delete GUIA
    where  TIPGUI_ID = :old.TIPGUI_ID;
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

--  After update trigger "tua_tipo_producto" for table "TIPO_PRODUCTO"
create trigger tua_tipo_producto after update
of TIPRO_ID
on TIPO_PRODUCTO for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
begin
    IntegrityPackage.NextNestLevel;
    
    --  Modify parent code of "TIPO_PRODUCTO" for all children in "GUIA"
    if (updating('TIPRO_ID') and :old.TIPRO_ID != :new.TIPRO_ID) then
       update GUIA
        set   TIPRO_ID = :new.TIPRO_ID
       where  TIPRO_ID = :old.TIPRO_ID;
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

--  After delete trigger "tda_tipo_producto" for table "TIPO_PRODUCTO"
create trigger tda_tipo_producto after delete
on TIPO_PRODUCTO for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;

begin
    IntegrityPackage.NextNestLevel;
    
    --  Delete all children in "GUIA"
    delete GUIA
    where  TIPRO_ID = :old.TIPRO_ID;
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

--  After update trigger "tua_tipoagencia" for table "TIPOAGENCIA"
create trigger tua_tipoagencia after update
of TIPAGE_CODIGO
on TIPOAGENCIA for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
begin
    IntegrityPackage.NextNestLevel;
    
    --  Modify parent code of "TIPOAGENCIA" for all children in "AGENCIA"
    if (updating('TIPAGE_CODIGO') and :old.TIPAGE_CODIGO != :new.TIPAGE_CODIGO) then
       update AGENCIA
        set   TIPAGE_CODIGO = :new.TIPAGE_CODIGO
       where  TIPAGE_CODIGO = :old.TIPAGE_CODIGO;
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

--  After delete trigger "tda_tipoagencia" for table "TIPOAGENCIA"
create trigger tda_tipoagencia after delete
on TIPOAGENCIA for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;

begin
    IntegrityPackage.NextNestLevel;
    
    --  Delete all children in "AGENCIA"
    delete AGENCIA
    where  TIPAGE_CODIGO = :old.TIPAGE_CODIGO;
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

--  Before update trigger "tub_tipocambio" for table "TIPOCAMBIO"
create trigger tub_tipocambio before update
of TIPCAM_FECHA,
   MON_CODIGO,
   EST_ID
on TIPOCAMBIO for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
    seq NUMBER;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "ESTACION"
    cursor cpk1_tipocambio(var_est_id number) is
       select 1
       from   ESTACION
       where  EST_ID = var_est_id
        and   var_est_id is not null;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "MONEDA"
    cursor cpk2_tipocambio(var_mon_codigo varchar) is
       select 1
       from   MONEDA
       where  MON_CODIGO = var_mon_codigo
        and   var_mon_codigo is not null;

begin
    seq := IntegrityPackage.GetNestLevel;
    
    --  Parent "ESTACION" must exist when updating a child in "TIPOCAMBIO"
    if (:new.EST_ID is not null) and (seq = 0) then 
       open  cpk1_tipocambio(:new.EST_ID);
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

--  After update trigger "tua_tipovuelo" for table "TIPOVUELO"
create trigger tua_tipovuelo after update
of TIPVUE_CODIGO
on TIPOVUELO for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
begin
    IntegrityPackage.NextNestLevel;
    
    --  Modify parent code of "TIPOVUELO" for all children in "VUELO"
    if (updating('TIPVUE_CODIGO') and :old.TIPVUE_CODIGO != :new.TIPVUE_CODIGO) then
       update VUELO
        set   TIPVUE_CODIGO = :new.TIPVUE_CODIGO
       where  TIPVUE_CODIGO = :old.TIPVUE_CODIGO;
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

--  After delete trigger "tda_tipovuelo" for table "TIPOVUELO"
create trigger tda_tipovuelo after delete
on TIPOVUELO for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;

begin
    IntegrityPackage.NextNestLevel;
    
    --  Delete all children in "VUELO"
    delete VUELO
    where  TIPVUE_CODIGO = :old.TIPVUE_CODIGO;
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

--  Before update trigger "tub_usuario" for table "USUARIO"
create trigger tub_usuario before update
of USU_CODIGO,
   USU_USU_CODIGO,
   EST_ID
on USUARIO for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
    seq NUMBER;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "ESTACION"
    cursor cpk1_usuario(var_est_id number) is
       select 1
       from   ESTACION
       where  EST_ID = var_est_id
        and   var_est_id is not null;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "USUARIO"
    cursor cpk2_usuario(var_usu_usu_codigo varchar) is
       select 1
       from   USUARIO
       where  USU_CODIGO = var_usu_usu_codigo
        and   var_usu_usu_codigo is not null;

begin
    seq := IntegrityPackage.GetNestLevel;
    
    --  Parent "ESTACION" must exist when updating a child in "USUARIO"
    if (:new.EST_ID is not null) and (seq = 0) then 
       open  cpk1_usuario(:new.EST_ID);
       fetch cpk1_usuario into dummy;
       found := cpk1_usuario%FOUND;
       close cpk1_usuario;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "ESTACION". Cannot update child in "USUARIO".';
          raise integrity_error;
       end if;
    end if;
    
    --  Parent "USUARIO" must exist when updating a child in "USUARIO"
    if (:new.USU_USU_CODIGO is not null) and (seq = 0) then 
       open  cpk2_usuario(:new.USU_USU_CODIGO);
       fetch cpk2_usuario into dummy;
       found := cpk2_usuario%FOUND;
       close cpk2_usuario;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "USUARIO". Cannot update child in "USUARIO".';
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
of USU_CODIGO,
   USU_USU_CODIGO,
   EST_ID
on USUARIO for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
begin
    IntegrityPackage.NextNestLevel;
    
    --  Modify parent code of "USUARIO" for all children in "USUARIO"
    if (updating('USU_CODIGO') and :old.USU_CODIGO != :new.USU_CODIGO) then
       update USUARIO
        set   USU_USU_CODIGO = :new.USU_CODIGO
       where  USU_USU_CODIGO = :old.USU_CODIGO;
    end if;
    
    --  Modify parent code of "USUARIO" for all children in "GUIA"
    if (updating('USU_CODIGO') and :old.USU_CODIGO != :new.USU_CODIGO) then
       update GUIA
        set   USU_CODIGO = :new.USU_CODIGO
       where  USU_CODIGO = :old.USU_CODIGO;
    end if;
    
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
    
    --  Delete all children in "USUARIO"
    delete USUARIO
    where  USU_USU_CODIGO = :old.USU_CODIGO;
    
    --  Delete all children in "GUIA"
    delete GUIA
    where  USU_CODIGO = :old.USU_CODIGO;
    
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

--  Before update trigger "tub_vuelo" for table "VUELO"
create trigger tub_vuelo before update
of VUE_CODIGO,
   TIPVUE_CODIGO
on VUELO for each row
declare
    integrity_error  exception;
    errno            integer;
    errmsg           char(200);
    dummy            integer;
    found            boolean;
    seq NUMBER;
    
    --  Declaration of UpdateChildParentExist constraint for the parent "TIPOVUELO"
    cursor cpk1_vuelo(var_tipvue_codigo varchar) is
       select 1
       from   TIPOVUELO
       where  TIPVUE_CODIGO = var_tipvue_codigo
        and   var_tipvue_codigo is not null;

begin
    seq := IntegrityPackage.GetNestLevel;
    
    --  Parent "TIPOVUELO" must exist when updating a child in "VUELO"
    if (:new.TIPVUE_CODIGO is not null) and (seq = 0) then 
       open  cpk1_vuelo(:new.TIPVUE_CODIGO);
       fetch cpk1_vuelo into dummy;
       found := cpk1_vuelo%FOUND;
       close cpk1_vuelo;
       if not found then
          errno  := -20003;
          errmsg := 'Parent does not exist in "TIPOVUELO". Cannot update child in "VUELO".';
          raise integrity_error;
       end if;
    end if;

--  Errors handling
exception
    when integrity_error then
       raise_application_error(errno, errmsg);
end;
/

