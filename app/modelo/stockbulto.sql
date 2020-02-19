-- ============================================================
--   Database name:  MODEL_1                                   
--   DBMS name:      ORACLE Version 8                          
--   Created on:     16/05/2005  21:20                         
-- ============================================================

-- ============================================================
--   Table: TERMINAL                                           
-- ============================================================
create table TERMINAL
(
    IDTERMINAL          VARCHAR2(5)            not null,
    EST_ID              NUMBER(20)             null    ,
    TEDESCRIPCION       VARCHAR2(40)           null    ,
    TESERIAL            VARCHAR2(20)           null    ,
    TEESTADO            CHAR(1)                not null,
    IDTERMINALGUIA      VARCHAR2(5)            null    ,
    IDTERMINALFACTURA   VARCHAR2(5)            null    ,
    IDTERMINALBARRAPAQ  VARCHAR2(5)            null    ,
    IDTERMINALBARRAVAL  VARCHAR2(5)            null    ,
    IDTERMINALNORMAL    VARCHAR2(5)            null    ,
    TEEMAIL             VARCHAR2(100)          null    ,
    constraint PK_TERMINAL primary key (IDTERMINAL)
)
/

-- ============================================================
--   Table: IMPRESION                                          
-- ============================================================
create table IMPRESION
(
    IDIMPRESION         NUMBER(38)             not null,
    IDTERMINAL          VARCHAR2(5)            not null,
    IMARCHIVO           VARCHAR2(100)          not null,
    IMCOMANDO           VARCHAR2(30)           not null,
    IMTERMINALORIGEN    VARCHAR2(5)            not null,
    IMTIPO              CHAR(1)                not null,
    IMFECHA             DATE                   null    ,
    constraint PK_IMPRESION primary key (IDIMPRESION)
)
/

-- ============================================================
--   Table: STOCK_TIPO                                         
-- ============================================================
create table STOCK_TIPO
(
    STOTIP_ID           VARCHAR2(3)            not null,
    STOTIP_NOMBRE       VARCHAR2(30)           not null,
    constraint PK_STOCK_TIPO primary key (STOTIP_ID)
)
/

-- ============================================================
--   Table: STOCK_ESTACION                                     
-- ============================================================
create table STOCK_ESTACION
(
    STO_ID              NUMBER(10)             not null,
    STOTIP_ID           VARCHAR2(3)            null    ,
    EST_ID              NUMBER(20)             null    ,
    STO_DESDE           VARCHAR2(10)           null    ,
    STO_HASTA           VARCHAR2(10)           null    ,
    STO_ACTUAL          VARCHAR2(10)           null    ,
    STO_FECHA           DATE                   null    ,
    USU_AUDIT           VARCHAR2(15)           null    ,
    USU_FAUDIT          DATE                   null    ,
    constraint PK_STOCK_ESTACION primary key (STO_ID)
)
/

-- ============================================================
--   Table: STOCK_HISTORIAL                                    
-- ============================================================
create table STOCK_HISTORIAL
(
    STOTIP_ID           VARCHAR2(3)            null    ,
    STO_ID              NUMBER(10)             null    ,
    STOEST_ID           VARCHAR2(10)           null    ,
    STO_NRO             VARCHAR2(10)           null    ,
    USU_AUDIT           VARCHAR2(15)           null    ,
    USU_FAUDIT          DATE                   null    ,
    STO_FECHA           DATE                   null    
)
/

-- ============================================================
--   Table: STOCK_ESTADO                                       
-- ============================================================
create table STOCK_ESTADO
(
    STOEST_ID           VARCHAR2(10)           not null,
    STOEST_NOMBRE       VARCHAR2(30)           not null,
    constraint PK_STOCK_ESTADO primary key (STOEST_ID)
)
/

alter table TERMINAL
    add constraint FK_TERMINAL_REF_3420_ESTACION foreign key  (EST_ID)
       references ESTACION (EST_ID)
/

alter table IMPRESION
    add constraint FK_IMPRESIO_REF_51_TERMINAL foreign key  (IMTERMINALORIGEN)
       references TERMINAL (IDTERMINAL)
/

alter table IMPRESION
    add constraint FK_IMPRESIO_REF_48_TERMINAL foreign key  (IDTERMINAL)
       references TERMINAL (IDTERMINAL)
/

alter table STOCK_ESTACION
    add constraint FK_STOCK_ES_REF_3327_STOCK_TI foreign key  (STOTIP_ID)
       references STOCK_TIPO (STOTIP_ID)
/

alter table STOCK_ESTACION
    add constraint FK_STOCK_ES_REF_3331_ESTACION foreign key  (EST_ID)
       references ESTACION (EST_ID)
/

alter table STOCK_HISTORIAL
    add constraint FK_STOCK_HI_REF_3336_STOCK_TI foreign key  (STOTIP_ID)
       references STOCK_TIPO (STOTIP_ID)
/

alter table STOCK_HISTORIAL
    add constraint FK_STOCK_HI_REF_3340_STOCK_ES foreign key  (STO_ID)
       references STOCK_ESTACION (STO_ID)
/

alter table STOCK_HISTORIAL
    add constraint FK_STOCK_HI_REF_3352_STOCK_ES foreign key  (STOEST_ID)
       references STOCK_ESTADO (STOEST_ID)
/

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

-- ============================================================
--   Table: MANDESXBULTO                                       
-- ============================================================
create table MANDESXBULTO
(
    MANDES_ID   NUMBER(30)             not null,
    BUL_ID      VARCHAR2(20)           not null,
    VUEHIS_ID   NUMBER(30)             null    ,
    USU_AUDIT   VARCHAR2(15)           null    ,
    USU_FAUDIT  DATE                   null    ,
    constraint PK_MANDESXBULTO primary key (MANDES_ID, BUL_ID)
)
/

-- ============================================================
--   Table: DES_PAQUETE                                        
-- ============================================================
create table DES_PAQUETE
(
    MANDES_ID   NUMBER(30)             not null,
    BUL_ID      VARCHAR2(20)           not null,
    PAQ_ID      VARCHAR2(20)           not null,
    UBICACION   VARCHAR2(200)          null    ,
    constraint PK_DES_PAQUETE primary key (MANDES_ID, BUL_ID, PAQ_ID)
)
/

alter table MANDESXBULTO
    add constraint FK_MANDESXB_REF_3406_MANIFIES foreign key  (MANDES_ID)
       references MANIFIESTO_DESEMBARQUE (MANDES_ID)
/

alter table DES_PAQUETE
    add constraint FK_DES_PAQU_REF_3413_MANDESXB foreign key  (MANDES_ID, BUL_ID)
       references MANDESXBULTO (MANDES_ID, BUL_ID)
/

CREATE SEQUENCE IMPRESION_SEQ INCREMENT BY 1 
    START WITH 1 MAXVALUE 1.0E28 MINVALUE 1 NOCYCLE 
    CACHE 20 NOORDER;
/
create trigger IMPRESION_TRIGGER 
BEFORE INSERT ON "IMPRESION" 
FOR EACH ROW
begin select IMPRESION_SEQ.nextval into :new.IDIMPRESION from dual;
end;
/

CREATE SEQUENCE STOCK_ESTACION_SEQ INCREMENT BY 1 
    START WITH 1 MAXVALUE 1.0E28 MINVALUE 1 NOCYCLE 
    CACHE 20 NOORDER;
/
create trigger STOCK_ESTACION_TRIGGER 
BEFORE INSERT ON "STOCK_ESTACION" 
FOR EACH ROW
begin select STOCK_ESTACION_SEQ.nextval into :new.STO_ID from dual;
end;
/

