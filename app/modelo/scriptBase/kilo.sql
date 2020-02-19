-- ============================================================
--   Database name:  ICARGO                                    
--   DBMS name:      ORACLE Version 8                          
--   Created on:     10/03/2006  1:01                          
-- ============================================================

-- ============================================================
--   Table: KILOEQUIVALENCIAXCLI                               
-- ============================================================
create table KILOEQUIVALENCIAXCLI
(
    EST_CODIGOO    VARCHAR2(7)            not null,
    EST_CODIGOD    VARCHAR2(7)            not null,
    CLI_CODIGO     VARCHAR2(20)           not null,
    KILEQU_PRECIO  NUMBER(12,2)           not null,
    USU_AUDIT      VARCHAR2(15)           null    ,
    USU_FAUDIT     DATE                   null    ,
    constraint PK_KILEQUCLI primary key (EST_CODIGOO, EST_CODIGOD, CLI_CODIGO)
)
/

-- ============================================================
--   Table: KILOEQUIVALENCIAXEST                               
-- ============================================================
create table KILOEQUIVALENCIAXEST
(
    EST_CODIGOO    VARCHAR2(7)            not null,
    EST_CODIGOD    VARCHAR2(7)            not null,
    KILEQU_PRECIO  NUMBER(12,2)           not null,
    USU_AUDIT      VARCHAR2(15)           null    ,
    USU_FAUDIT     DATE                   null    ,
    constraint PK_KILEQUXEST primary key (EST_CODIGOO, EST_CODIGOD)
)
/

alter table KILOEQUIVALENCIAXCLI
    add constraint FK_KILOEQUI_REF_9064_ESTACION foreign key  (EST_CODIGOO)
       references ESTACION (EST_CODIGO)
/

alter table KILOEQUIVALENCIAXCLI
    add constraint FK_KILOEQUI_REF_9068_ESTACION foreign key  (EST_CODIGOD)
       references ESTACION (EST_CODIGO)
/

alter table KILOEQUIVALENCIAXCLI
    add constraint FK_KILOEQUI_REF_9072_CLIENTE foreign key  (CLI_CODIGO)
       references CLIENTE (CLI_CODIGO)
/

alter table KILOEQUIVALENCIAXEST
    add constraint FK_KILOEQUI_KILEQUXES_ESTACION foreign key  (EST_CODIGOO)
       references ESTACION (EST_CODIGO)
/

alter table KILOEQUIVALENCIAXEST
    add constraint FK_KILOEQUI_REF_9059_ESTACION foreign key  (EST_CODIGOD)
       references ESTACION (EST_CODIGO)
/

