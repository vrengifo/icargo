-- ============================================================
--   Database name:  MODEL_1                                   
--   DBMS name:      ORACLE Version 8                          
--   Created on:     04/01/2004  9:01                          
-- ============================================================

-- ============================================================
--   Table: VUELO                                              
-- ============================================================
create table VUELO
(
    VUE_CODIGO          CHAR(4)                not null,
    TIPVUE_CODIGO       CHAR(1)                null    ,
    VUE_RUTA            VARCHAR2(30)           not null,
    VUE_ORIGEN          CHAR(3)                null    ,
    VUE_DESTINO         CHAR(3)                null    ,
    constraint PK_VUELO primary key (VUE_CODIGO)
)
/

-- ============================================================
--   Index: RELATION_52_FK                                     
-- ============================================================
create index RELATION_52_FK on VUELO (TIPVUE_CODIGO asc)
/

-- ============================================================
--   Table: TIPOVUELO                                          
-- ============================================================
create table TIPOVUELO
(
    TIPVUE_CODIGO       CHAR(1)                not null,
    TIPVUE_DESCRIPCION  VARCHAR2(30)           not null,
    constraint PK_TIPOVUELO primary key (TIPVUE_CODIGO)
)
/

-- ============================================================
--   Table: MANIFIESTO_EMBARQUE                                
-- ============================================================
create table MANIFIESTO_EMBARQUE
(
    MANEMB_ID           NUMBER(30)             not null,
    VUEHIS_ID           NUMBER(30)             null    ,
    MANEMB_NRO          VARCHAR2(10)           null    ,
    MANEMB_FECHA        DATE                   null    ,
    MANEMB_POR          VARCHAR2(20)           null    ,
    MANEMB_ORIGEN       CHAR(3)                null    ,
    MANEMB_DESTINO      CHAR(3)                null    ,
    constraint PK_MANIFIESTO_EMBARQUE primary key (MANEMB_ID)
)
/

-- ============================================================
--   Table: MANEMB_DETALLE                                     
-- ============================================================
create table MANEMB_DETALLE
(
    MANEMBDET_ID        NUMBER(30)             not null,
    MANEMB_ID           NUMBER(30)             null    ,
    GUI_ID              NUMBER(20)             null    ,
    constraint PK_MANEMB_DETALLE primary key (MANEMBDET_ID)
)
/

-- ============================================================
--   Table: AVION                                              
-- ============================================================
create table AVION
(
    AVI_ID              VARCHAR2(7)            not null,
    AVI_MATRICULA       VARCHAR2(7)            null    ,
    AVI_PESO_MAX        NUMBER(4,2)            null    ,
    AVI_VOL_MAX         NUMBER(4)              null    ,
    constraint PK_AVION primary key (AVI_ID)
)
/

-- ============================================================
--   Table: VUELO_HISTORIAL                                    
-- ============================================================
create table VUELO_HISTORIAL
(
    VUEHIS_ID           NUMBER(30)             not null,
    AVI_ID              VARCHAR2(7)            null    ,
    VUE_CODIGO          CHAR(4)                null    ,
    VUEHIS_FECHA        DATE                   null    ,
    VUE_PESO_MAX        NUMBER(4,2)            null    ,
    VUE_VOL_MAX         NUMBER(4)              null    ,
    constraint PK_VUELO_HISTORIAL primary key (VUEHIS_ID)
)
/

-- ============================================================
--   Table: MANIFIESTO_DESEMBARQUE                             
-- ============================================================
create table MANIFIESTO_DESEMBARQUE
(
    MANDES_ID           NUMBER(30)             not null,
    MANEMB_ID           NUMBER(30)             null    ,
    VUE_CODIGO          CHAR(4)                null    ,
    MANDES_FECHAREC     DATE                   null    ,
    MANDES_POR          VARCHAR2(20)           null    ,
    MANDES_ORIGEN       CHAR(3)                null    ,
    MANDES_DESTINO      CHAR(3)                null    ,
    constraint PK_MANIFIESTO_DESEMBARQUE primary key (MANDES_ID)
)
/

-- ============================================================
--   Table: MANDES_DETALLE                                     
-- ============================================================
create table MANDES_DETALLE
(
    MANDESDET_ID        NUMBER(30)             not null,
    MANDES_ID           NUMBER(30)             null    ,
    GUI_ID              NUMBER(20)             null    ,
    MANDESDET_OK        CHAR(1)                null    ,
    constraint PK_MANDES_DETALLE primary key (MANDESDET_ID)
)
/

alter table VUELO
    add constraint FK_VUELO_RTIPVUE_V_TIPOVUEL foreign key  (TIPVUE_CODIGO)
       references TIPOVUELO (TIPVUE_CODIGO) on delete cascade
/

alter table MANIFIESTO_EMBARQUE
    add constraint FK_MANIFIES_RVUEHISXM_VUELO_HI foreign key  (VUEHIS_ID)
       references VUELO_HISTORIAL (VUEHIS_ID) on delete cascade
/

alter table MANEMB_DETALLE
    add constraint FK_MANEMB_D_RMANEMB_M_MANIFIES foreign key  (MANEMB_ID)
       references MANIFIESTO_EMBARQUE (MANEMB_ID) on delete cascade
/

alter table MANEMB_DETALLE
    add constraint FK_MANEMB_D_RGUIAXMAN_GUIA foreign key  (GUI_ID)
       references GUIA (GUI_ID) on delete cascade
/

alter table VUELO_HISTORIAL
    add constraint FK_VUELO_HI_RVUEXVUEH_VUELO foreign key  (VUE_CODIGO)
       references VUELO (VUE_CODIGO) on delete cascade
/

alter table VUELO_HISTORIAL
    add constraint FK_VUELO_HI_RAVIXVUEH_AVION foreign key  (AVI_ID)
       references AVION (AVI_ID) on delete cascade
/

alter table MANIFIESTO_DESEMBARQUE
    add constraint FK_MANIFIES_RMANEMBXM_MANIFIES foreign key  (MANEMB_ID)
       references MANIFIESTO_EMBARQUE (MANEMB_ID) on delete cascade
/

alter table MANDES_DETALLE
    add constraint FK_MANDES_D_RMANDESXM_MANIFIES foreign key  (MANDES_ID)
       references MANIFIESTO_DESEMBARQUE (MANDES_ID) on delete cascade
/

