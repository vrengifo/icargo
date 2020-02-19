-- ============================================================
--   Database name:  ICARGO                                    
--   DBMS name:      ORACLE Version 8                          
--   Created on:     15/03/2006  4:24                          
-- ============================================================

-- ============================================================
--   Table: BULTO                                              
-- ============================================================
create table BULTO
(
    BUL_REF      VARCHAR2(20)           not null,
    BUL_ID       VARCHAR2(20)           not null,
    BUL_FECHA    DATE                   not null,
    BUL_ORIGEN   VARCHAR2(7)            not null,
    BUL_DESTINO  VARCHAR2(7)            not null,
    USU_AUDIT    VARCHAR2(15)           null    ,
    USU_FAUDIT   DATE                   null    ,
    constraint PK_BULTO primary key (BUL_REF)
)
/

-- ============================================================
--   Table: MANEMBXBULTO                                       
-- ============================================================
create table MANEMBXBULTO
(
    MANEMB_ID    NUMBER(30)             not null,
    BUL_REF      VARCHAR2(20)           not null,
    USU_AUDIT    VARCHAR2(15)           null    ,
    USU_FAUDIT   DATE                   null    ,
    constraint PK_MANEMBXBULTO primary key (MANEMB_ID, BUL_REF)
)
/

-- ============================================================
--   Table: MANDESXBULTO                                       
-- ============================================================
create table MANDESXBULTO
(
    MANEMB_ID    NUMBER(30)             not null,
    BUL_REF      VARCHAR2(20)           not null,
    USU_AUDIT    VARCHAR2(15)           null    ,
    USU_FAUDIT   DATE                   null    ,
    constraint PK_MANDESXBULTO primary key (MANEMB_ID, BUL_REF)
)
/

-- ============================================================
--   Table: DES_PAQUETE                                        
-- ============================================================
create table DES_PAQUETE
(
    MANEMB_ID    NUMBER(30)             not null,
    DETDOC_REF   VARCHAR2(250)          not null,
    BUL_REF      VARCHAR2(20)           not null,
    UBICACION    VARCHAR2(200)          null    ,
    USU_AUDIT    VARCHAR2(15)           null    ,
    USU_FAUDIT   DATE                   null    ,
    constraint PK_DES_PAQUETE primary key (MANEMB_ID, DETDOC_REF, BUL_REF)
)
/

-- ============================================================
--   Table: DETALLE_BULTO                                      
-- ============================================================
create table DETALLE_BULTO
(
    DETDOC_REF   varchar2(250)          not null,
    BUL_REF      VARCHAR2(20)           not null,
    USU_AUDIT    VARCHAR2(15)           null    ,
    USU_FAUDIT   DATE                   null    ,
    constraint PK_DETBUL primary key (DETDOC_REF, BUL_REF)
)
/

-- ============================================================
--   Table: MANEMBXBULTOQUEDA                                  
-- ============================================================
create table MANEMBXBULTOQUEDA
(
    MANEMB_ID    NUMBER(30)             not null,
    BUL_REF      VARCHAR2(20)           not null,
    USU_AUDIT    VARCHAR2(15)           null    ,
    USU_FAUDIT   DATE                   null    ,
    constraint PK_MANEMBXBULTOQUEDA primary key (MANEMB_ID, BUL_REF)
)
/

-- ============================================================
--   Table: MANEMBXBULTOREAL                                   
-- ============================================================
create table MANEMBXBULTOREAL
(
    MANEMB_ID    NUMBER(30)             not null,
    BUL_REF      VARCHAR2(20)           not null,
    USU_AUDIT    VARCHAR2(15)           null    ,
    USU_FAUDIT   DATE                   null    ,
    constraint PK_MANEMBXBULTOREAL primary key (MANEMB_ID, BUL_REF)
)
/

alter table BULTO
    add constraint FK_BULTO_BULXEST1_ESTACION foreign key  (BUL_ORIGEN)
       references ESTACION (EST_CODIGO)
/

alter table BULTO
    add constraint FK_BULTO_BULXEST2_ESTACION foreign key  (BUL_DESTINO)
       references ESTACION (EST_CODIGO)
/

alter table MANEMBXBULTO
    add constraint FK_MANEMBXB_MANEMBXBU_BULTO foreign key  (BUL_REF)
       references BULTO (BUL_REF)
/

alter table MANEMBXBULTO
    add constraint FK_MANEMBXB_MANEMBXBU_MANIFIES foreign key  (MANEMB_ID)
       references MANIFIESTO_EMBARQUE (MANEMB_ID)
/

alter table MANDESXBULTO
    add constraint FK_MANDESXB_MANDESXBU_MANIFIES foreign key  (MANEMB_ID)
       references MANIFIESTO_DESEMBARQUE (MANEMB_ID)
/

alter table MANDESXBULTO
    add constraint FK_MANDESXB_MANDESXBU_BULTO foreign key  (BUL_REF)
       references BULTO (BUL_REF)
/

alter table DES_PAQUETE
    add constraint FK_DES_PAQU_DESPAQ_MA_MANDESXB foreign key  (MANEMB_ID, BUL_REF)
       references MANDESXBULTO (MANEMB_ID, BUL_REF)
/

alter table DETALLE_BULTO
    add constraint FK_DETALLE__DETDOCXDE_DETALLED foreign key  (DETDOC_REF)
       references DETALLEDOCUMENTO (DETDOC_REF)
/

alter table DETALLE_BULTO
    add constraint FK_DETALLE__DETBULXBU_BULTO foreign key  (BUL_REF)
       references BULTO (BUL_REF)
/

