-- ============================================================
--   Database name:  MODEL_1                                   
--   DBMS name:      ORACLE Version 8                          
--   Created on:     16/05/2005  21:22                         
-- ============================================================

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

