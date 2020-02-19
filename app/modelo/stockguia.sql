-- ============================================================
--   Database name:  MODEL_1                                   
--   DBMS name:      ORACLE Version 8                          
--   Created on:     04/01/2004  9:13                          
-- ============================================================

-- ============================================================
--   Table: STOCKXESTACION                                     
-- ============================================================
create table STOCKXESTACION
(
    EST_ID              NUMBER(20)             null    ,
    STOEST_ID           NUMBER(20)             not null,
    STOEST_FECHA        DATE                   null    ,
    STOEST_VINI         VARCHAR2(10)           null    ,
    STOEST_VFIN         VARCHAR2(10)           null    ,
    STOEST_UAUDIT       VARCHAR2(20)           null    ,
    constraint PK_STOCKXESTACION primary key (STOEST_ID)
)
/

-- ============================================================
--   Table: STOESTGUI                                          
-- ============================================================
create table STOESTGUI
(
    ESTGUI_ID           VARCHAR2(4)            null    ,
    STOEST_ID           NUMBER(20)             null    ,
    SEG_NROGUIA         VARCHAR2(10)           null    
)
/

-- ============================================================
--   Table: ESTADO_GUIA                                        
-- ============================================================
create table ESTADO_GUIA
(
    ESTGUI_ID           VARCHAR2(4)            not null,
    ESTGUI_DESCRIPCION  VARCHAR2(30)           null    ,
    constraint PK_ESTADO_GUIA primary key (ESTGUI_ID)
)
/

-- ============================================================
--   Table: STOESTGUI_HISTORIAL                                
-- ============================================================
create table STOESTGUI_HISTORIAL
(
    ESTGUI_ID           VARCHAR2(4)            null    ,
    STOEST_ID           NUMBER(20)             null    ,
    SEG_NROGUIA         varchar2(10)           null    ,
    SEG_FECHA           DATE                   null    
)
/

alter table STOCKXESTACION
    add constraint FK_STOCKXES_RESTXSTOE_ESTACION foreign key  (EST_ID)
       references ESTACION (EST_ID) on delete cascade
/

alter table STOESTGUI
    add constraint FK_STOESTGU_REGXSEG_ESTADO_G foreign key  (ESTGUI_ID)
       references ESTADO_GUIA (ESTGUI_ID) on delete cascade
/

alter table STOESTGUI
    add constraint FK_STOESTGU_RSTOESTXS_STOCKXES foreign key  (STOEST_ID)
       references STOCKXESTACION (STOEST_ID) on delete cascade
/

