-- ============================================================
--   Database name:  MODEL_1                                   
--   DBMS name:      ORACLE Version 8                          
--   Created on:     16/05/2005  21:21                         
-- ============================================================

-- ============================================================
--   Table: BULTO                                              
-- ============================================================
create table BULTO
(
    BUL_ID         VARCHAR2(20)           not null,
    VUE_CODIGO     CHAR(4)                null    ,
    MANEMB_ID      NUMBER(30)             null    ,
    VUEHIS_ID      NUMBER(30)             null    ,
    BUL_NRO        NUMBER(4)              null    ,
    BUL_FECHA      DATE                   null    ,
    USU_AUDIT      VARCHAR2(15)           null    ,
    USU_FAUDIT     DATE                   null    ,
    BUL_ORIGEN     VARCHAR2(4)            null    ,
    BUL_DESTINO    VARCHAR2(4)            null    ,
    constraint PK_BULTO primary key (BUL_ID)
)
/

-- ============================================================
--   Table: PAQUETE                                            
-- ============================================================
create table PAQUETE
(
    PAQ_ID         VARCHAR2(20)           not null,
    BUL_ID         VARCHAR2(20)           null    ,
    constraint PK_PAQUETE primary key (PAQ_ID)
)
/

-- ============================================================
--   Table: MANEMBXBULTO                                       
-- ============================================================
create table MANEMBXBULTO
(
    BUL_ID         VARCHAR2(20)           not null,
    BULEST_ID      VARCHAR2(3)            null    ,
    VUEHIS_ID      NUMBER(30)             not null,
    USU_AUDIT      VARCHAR2(15)           null    ,
    USU_FAUDIT     DATE                   null    ,
    constraint PK_MANEMBXBULTO primary key (BUL_ID, VUEHIS_ID)
)
/

-- ============================================================
--   Table: BULTO_ESTADO                                       
-- ============================================================
create table BULTO_ESTADO
(
    BULEST_ID      VARCHAR2(3)            not null,
    BULEST_NOMBRE  VARCHAR2(30)           not null,
    constraint PK_BULTO_ESTADO primary key (BULEST_ID)
)
/

alter table BULTO
    add constraint FK_BULTO_REF_3369_VUELO foreign key  (VUE_CODIGO)
       references VUELO (VUE_CODIGO)
/

alter table BULTO
    add constraint FK_BULTO_REF_3373_MANIFIES foreign key  (MANEMB_ID)
       references MANIFIESTO_EMBARQUE (MANEMB_ID)
/

alter table BULTO
    add constraint FK_BULTO_REF_3386_VUELO_HI foreign key  (VUEHIS_ID)
       references VUELO_HISTORIAL (VUEHIS_ID)
/

alter table PAQUETE
    add constraint FK_PAQUETE_REF_3378_BULTO foreign key  (BUL_ID)
       references BULTO (BUL_ID)
/

alter table MANEMBXBULTO
    add constraint FK_MANEMBXB_REF_3382_BULTO foreign key  (BUL_ID)
       references BULTO (BUL_ID)
/

alter table MANEMBXBULTO
    add constraint FK_MANEMBXB_REF_3394_BULTO_ES foreign key  (BULEST_ID)
       references BULTO_ESTADO (BULEST_ID)
/

