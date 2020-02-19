-- ============================================================
--   Database name:  MODEL_1                                   
--   DBMS name:      ORACLE Version 8                          
--   Created on:     21/12/2003  23:34                         
-- ============================================================

-- ============================================================
--   Table: EQUIPO                                             
-- ============================================================
create table EQUIPO
(
    EQU_CODIGO               CHAR(5)                not null,
    EQU_DESCRIPCION          VARCHAR2(30)           null    ,
    EQU_KILOS                NUMBER(12,2)           null    ,
    EQU_LIBRAS               NUMBER(12,2)           null    ,
    EQU_PAX_FC               INTEGER                null    ,
    EQU_PAX_BC               INTEGER                null    ,
    EQU_PAX_EC               INTEGER                null    ,
    constraint PK_EQUIPO primary key (EQU_CODIGO)
)
/

-- ============================================================
--   Table: MONEDA                                             
-- ============================================================
create table MONEDA
(
    MON_CODIGO               CHAR(3)                not null,
    MON_DESCRIPCION          VARCHAR2(30)           not null,
    constraint PK_MONEDA primary key (MON_CODIGO)
)
/

-- ============================================================
--   Table: PAIS                                               
-- ============================================================
create table PAIS
(
    PAI_CODIGO               CHAR(3)                not null,
    PAI_DESCRIPCION          VARCHAR2(30)           null    ,
    constraint PK_PAIS primary key (PAI_CODIGO)
)
/

-- ============================================================
--   Table: TIPOVUELO                                          
-- ============================================================
create table TIPOVUELO
(
    TIPVUE_CODIGO            CHAR(1)                not null,
    TIPVUE_DESCRIPCION       VARCHAR2(30)           not null,
    constraint PK_TIPOVUELO primary key (TIPVUE_CODIGO)
)
/

-- ============================================================
--   Table: TIPOAGENCIA                                        
-- ============================================================
create table TIPOAGENCIA
(
    TIPAGE_CODIGO            CHAR(1)                not null,
    TIPAGE_DESCRIPCION       VARCHAR2(30)           null    ,
    constraint PK_TIPOAGENCIA primary key (TIPAGE_CODIGO)
)
/

-- ============================================================
--   Table: TIPO_PRODUCTO                                      
-- ============================================================
create table TIPO_PRODUCTO
(
    TIPRO_ID                 CHAR(3)                not null,
    TIPRO_DESCRIPCION        VARCHAR2(20)           not null,
    constraint PK_TIPO_PRODUCTO primary key (TIPRO_ID)
)
/

-- ============================================================
--   Table: TARJETA                                            
-- ============================================================
create table TARJETA
(
    TAR_ID                   VARCHAR2(5)            not null,
    TAR_DESCRIPCION          VARCHAR2(20)           null    ,
    constraint PK_TARJETA primary key (TAR_ID)
)
/

-- ============================================================
--   Table: TIPO_GUIA                                          
-- ============================================================
create table TIPO_GUIA
(
    TIPGUI_ID                CHAR(2)                not null,
    TIPGUI_DESCRIPCION       VARCHAR2(20)           null    ,
    constraint PK_TIPO_GUIA primary key (TIPGUI_ID)
)
/

-- ============================================================
--   Table: FORMA_PAGO                                         
-- ============================================================
create table FORMA_PAGO
(
    FORPAG_ID                VARCHAR2(5)            not null,
    FORPAG_DESCRIPCION       VARCHAR2(30)           null    ,
    constraint PK_FORMA_PAGO primary key (FORPAG_ID)
)
/

-- ============================================================
--   Table: ENTREGADO_EN                                       
-- ============================================================
create table ENTREGADO_EN
(
    ENT_ID                   CHAR(2)                not null,
    ENT_DESCRIPCION          VARCHAR2(20)           null    ,
    constraint PK_ENTREGADO_EN primary key (ENT_ID)
)
/

-- ============================================================
--   Table: REPORTE_VENTA                                      
-- ============================================================
create table REPORTE_VENTA
(
    REPVEN_ID                NUMBER(20)             not null,
    REPVEN_NRO               VARCHAR2(10)           null    ,
    REPVEN_FECHA             DATE                   null    ,
    REPVEN_POR               VARCHAR2(15)           null    ,
    REPVEN_UAUDIT            VARCHAR2(15)           null    ,
    REPVEN_FAUDIT            DATE                   null    ,
    REPVEN_TOTAL_CASH        NUMBER(14,2)           null    ,
    REPVEN_TOTAL_COLLECT     NUMBER(14,2)           null    ,
    REPVEN_TOTAL_CREDITO     NUMBER(14,2)           null    ,
    REPVEN_TOTAL             NUMBER(15,2)           null    ,
    constraint PK_REPORTE_VENTA primary key (REPVEN_ID)
)
/

-- ============================================================
--   Table: APLICACION                                         
-- ============================================================
create table APLICACION
(
    ID_APLICACION            INTEGER                not null,
    NOMBRE_APLICACION        VARCHAR2(100)          null    ,
    FILE_APLICACION          VARCHAR2(100)          null    ,
    IMAGEN_APLICACION        VARCHAR2(100)          null    ,
    constraint PK_APLICACION primary key (ID_APLICACION)
)
/

-- ============================================================
--   Table: CIUDAD                                             
-- ============================================================
create table CIUDAD
(
    CIU_CODIGO               CHAR(3)                not null,
    PAI_CODIGO               CHAR(3)                null    ,
    EST_ID                   NUMBER(20)             null    ,
    CIU_DESCRIPCION          VARCHAR2(30)           not null,
    constraint PK_CIUDAD primary key (CIU_CODIGO)
)
/

-- ============================================================
--   Index: RELATION_12_FK                                     
-- ============================================================
create index RELATION_12_FK on CIUDAD (EST_ID asc)
/

-- ============================================================
--   Index: RELATION_25_FK                                     
-- ============================================================
create index RELATION_25_FK on CIUDAD (PAI_CODIGO asc)
/

-- ============================================================
--   Table: ESTACION                                           
-- ============================================================
create table ESTACION
(
    EST_ID                   NUMBER(20)             not null,
    CIU_CODIGO               CHAR(3)                not null,
    EST_CODIGO               VARCHAR2(7)            not null,
    MON_CODIGO               CHAR(3)                null    ,
    EST_NOMBRE               VARCHAR2(30)           null    ,
    EST_RUC                  VARCHAR2(13)           null    ,
    EST_AUTSRI               VARCHAR2(20)           null    ,
    EST_DIRECCION            VARCHAR2(250)          null    ,
    EST_TELEFONO             VARCHAR2(250)          null    ,
    EST_FAX                  VARCHAR2(100)          null    ,
    EST_MAIL                 VARCHAR2(200)          null    ,
    constraint PK_ESTACION primary key (EST_ID)
)
/

-- ============================================================
--   Index: RELATION_122_FK                                    
-- ============================================================
create index RELATION_122_FK on ESTACION (CIU_CODIGO asc)
/

-- ============================================================
--   Index: RELATION_13_FK                                     
-- ============================================================
create index RELATION_13_FK on ESTACION (MON_CODIGO asc)
/

-- ============================================================
--   Table: CLIENTE                                            
-- ============================================================
create table CLIENTE
(
    CLI_CODIGO               VARCHAR2(20)           not null,
    EST_ID                   NUMBER(20)             null    ,
    CLI_DESCRIPCION          VARCHAR2(30)           not null,
    CLI_CONTACTO             VARCHAR2(100)          not null,
    CLI_DIRECCION            VARCHAR2(100)          null    ,
    CLI_TELEFONO             VARCHAR2(25)           not null,
    CLI_FAX                  VARCHAR2(25)           null    ,
    CLI_EMAIL                VARCHAR2(100)          null    ,
    constraint PK_CLIENTE primary key (CLI_CODIGO)
)
/

-- ============================================================
--   Index: RELATION_19_FK                                     
-- ============================================================
create index RELATION_19_FK on CLIENTE (EST_ID asc)
/

-- ============================================================
--   Table: USUARIO                                            
-- ============================================================
create table USUARIO
(
    USU_CODIGO               VARCHAR2(15)           not null,
    USU_USU_CODIGO           VARCHAR2(15)           null    ,
    EST_ID                   NUMBER(20)             null    ,
    USU_CLAVE                VARCHAR2(15)           not null,
    USU_NOMBRE               VARCHAR2(100)          null    ,
    constraint PK_USUARIO primary key (USU_CODIGO)
)
/

-- ============================================================
--   Index: RELATION_81_FK                                     
-- ============================================================
create index RELATION_81_FK on USUARIO (EST_ID asc)
/

-- ============================================================
--   Index: RELATION_84_FK                                     
-- ============================================================
create index RELATION_84_FK on USUARIO (USU_USU_CODIGO asc)
/

-- ============================================================
--   Table: GUIA                                               
-- ============================================================
create table GUIA
(
    GUI_ID                   NUMBER(20)             not null,
    GUI_NRO                  VARCHAR2(10)           not null,
    TIPGUI_ID                CHAR(2)                null    ,
    TIPRO_ID                 CHAR(3)                null    ,
    GUI_FECHA                DATE                   null    ,
    GUI_PIEZAS               NUMBER(2)              null    ,
    GUI_PESO                 NUMBER(3,2)            null    ,
    GUI_VOLUMEN              NUMBER(2)              null    ,
    GUI_VDECLARADO           NUMBER(12,2)           null    ,
    GUI_DESCRIPCION          VARCHAR2(250)          null    ,
    GUI_CONDOCUMENTO         CHAR(1)                null    ,
    GUI_OBSERVACION          VARCHAR2(250)          null    ,
    GUI_OBS_SINDINENIJOYA    CHAR(1)                null    ,
    GUI_OBS_SUJETOESPACIO    CHAR(1)                null    ,
    GUI_OBS_CUENTARIESGOREM  CHAR(1)                null    ,
    ENT_ID                   CHAR(2)                null    ,
    CLI_CODIGO               VARCHAR2(20)           null    ,
    REPVEN_ID                INTEGER                null    ,
    USU_CODIGO               VARCHAR2(15)           not null,
    EST_ORIGEN               NUMBER(20)             null    ,
    EST_DESTINO              NUMBER(20)             null    ,
    GUI_REMITENTE            VARCHAR2(30)           null    ,
    GUI_CED_REMITENTE        VARCHAR2(13)           null    ,
    GUI_DIR_REMITENTE        VARCHAR2(60)           null    ,
    GUI_TELF_REMITENTE       VARCHAR2(20)           null    ,
    GUI_DESTINATARIO         VARCHAR2(30)           null    ,
    GUI_CED_DESTINATARIO     VARCHAR2(13)           null    ,
    GUI_DIR_DESTINATARIO     VARCHAR2(60)           null    ,
    GUI_TELF_DESTINATARIO    VARCHAR2(20)           null    ,
    GUI_FLETECARGA           NUMBER(10,2)           null    ,
    GUI_ENTREGADOMICILIO     NUMBER(10,2)           null    ,
    GUI_TRANSADICIONAL       NUMBER(10,2)           null    ,
    GUI_SERVADICIONAL        NUMBER(10,2)           null    ,
    GUI_CORRESPONDENCIA      NUMBER(10,2)           null    ,
    GUI_SEGUROS              NUMBER(10,2)           null    ,
    GUI_EMBALAJE             NUMBER(10,2)           null    ,
    GUI_SUBTOTAL             NUMBER(12,2)           null    ,
    GUI_DESCUENTO            NUMBER(12,2)           null    ,
    GUI_IVA                  NUMBER(12,2)           null    ,
    GUI_TOTAL                NUMBER(14,2)           null    ,
    GUI_UAUDIT               VARCHAR2(15)           null    ,
    GUI_FAUDIT               DATE                   null    ,
    constraint PK_GUIA primary key (GUI_ID)
)
/

-- ============================================================
--   Index: RELATION_199_FK                                    
-- ============================================================
create index RELATION_199_FK on GUIA (TIPRO_ID asc)
/

-- ============================================================
--   Index: RELATION_345_FK                                    
-- ============================================================
create index RELATION_345_FK on GUIA (ENT_ID asc)
/

-- ============================================================
--   Index: RELATION_352_FK                                    
-- ============================================================
create index RELATION_352_FK on GUIA (TIPGUI_ID asc)
/

-- ============================================================
--   Index: RELATION_353_FK                                    
-- ============================================================
create index RELATION_353_FK on GUIA (CLI_CODIGO asc)
/

-- ============================================================
--   Index: ESTORIGEN_GUIA_FK                                  
-- ============================================================
create index ESTORIGEN_GUIA_FK on GUIA (EST_ORIGEN asc)
/

-- ============================================================
--   Index: ESTACIONDESTINO_GUIA_FK                            
-- ============================================================
create index ESTACIONDESTINO_GUIA_FK on GUIA (EST_DESTINO asc)
/

-- ============================================================
--   Index: RELATION_412_FK                                    
-- ============================================================
create index RELATION_412_FK on GUIA (REPVEN_ID asc)
/

-- ============================================================
--   Index: USUARIOXGUIA_FK                                    
-- ============================================================
create index USUARIOXGUIA_FK on GUIA (USU_CODIGO asc)
/

-- ============================================================
--   Table: TIPOCAMBIO                                         
-- ============================================================
create table TIPOCAMBIO
(
    TIPCAM_FECHA             DATE                   not null,
    MON_CODIGO               CHAR(3)                null    ,
    EST_ID                   NUMBER(20)             not null,
    TIPCAM_VALOR             NUMBER(12,4)           null    ,
    constraint PK_TIPOCAMBIO primary key (TIPCAM_FECHA, EST_ID)
)
/

-- ============================================================
--   Index: RELATION_36_FK                                     
-- ============================================================
create index RELATION_36_FK on TIPOCAMBIO (EST_ID asc)
/

-- ============================================================
--   Index: RELATION_41_FK                                     
-- ============================================================
create index RELATION_41_FK on TIPOCAMBIO (MON_CODIGO asc)
/

-- ============================================================
--   Table: VUELO                                              
-- ============================================================
create table VUELO
(
    VUE_CODIGO               CHAR(4)                not null,
    TIPVUE_CODIGO            CHAR(1)                null    ,
    VUE_RUTA                 VARCHAR2(30)           not null,
    constraint PK_VUELO primary key (VUE_CODIGO)
)
/

-- ============================================================
--   Index: RELATION_52_FK                                     
-- ============================================================
create index RELATION_52_FK on VUELO (TIPVUE_CODIGO asc)
/

-- ============================================================
--   Table: KILOEQUIVALENCIA                                   
-- ============================================================
create table KILOEQUIVALENCIA
(
    CLI_CODIGO               VARCHAR2(20)           not null,
    KILEQU_FECHA             DATE                   not null,
    MON_CODIGO               CHAR(3)                null    ,
    EST_ID                   NUMBER(20)             not null,
    KILEQU_DESDE             NUMBER(12,2)           not null,
    KILEQU_HASTA             NUMBER(12,2)           not null,
    KILEQU_PRECIO            NUMBER(12,2)           not null,
    constraint PK_KILOEQUIVALENCIA primary key (CLI_CODIGO, KILEQU_FECHA, EST_ID)
)
/

-- ============================================================
--   Index: RELATION_70_FK                                     
-- ============================================================
create index RELATION_70_FK on KILOEQUIVALENCIA (CLI_CODIGO asc)
/

-- ============================================================
--   Index: RELATION_77_FK                                     
-- ============================================================
create index RELATION_77_FK on KILOEQUIVALENCIA (MON_CODIGO asc)
/

-- ============================================================
--   Table: AGENCIA                                            
-- ============================================================
create table AGENCIA
(
    AGE_CODIGO               CHAR(8)                not null,
    TIPAGE_CODIGO            CHAR(1)                null    ,
    EST_ID                   NUMBER(20)             null    ,
    AGE_DESCRIPCION          VARCHAR2(30)           null    ,
    AGE_PORCENTAJE_COMISION  NUMBER(2,4)            null    ,
    constraint PK_AGENCIA primary key (AGE_CODIGO)
)
/

-- ============================================================
--   Index: RELATION_98_FK                                     
-- ============================================================
create index RELATION_98_FK on AGENCIA (TIPAGE_CODIGO asc)
/

-- ============================================================
--   Index: RELATION_99_FK                                     
-- ============================================================
create index RELATION_99_FK on AGENCIA (EST_ID asc)
/

-- ============================================================
--   Table: FORMA_PAGOXGUIA                                    
-- ============================================================
create table FORMA_PAGOXGUIA
(
    FORPAG_ID                VARCHAR2(5)            null    ,
    TAR_ID                   VARCHAR2(5)            null    ,
    FPG_FECHA                DATE                   null    ,
    GUI_ID                   NUMBER(20)             null    ,
    FPG_VALOR                NUMBER(20,2)           null    ,
    TARJETA_NRO              VARCHAR2(30)           null    ,
    TARJETA_NRO_DOCUMENTO    VARCHAR2(20)           null    
)
/

-- ============================================================
--   Table: SUBAPLICACION                                      
-- ============================================================
create table SUBAPLICACION
(
    ID_SUBAPLICACION         INTEGER                not null,
    ID_APLICACION            INTEGER                null    ,
    NOMBRE_SUBAPLICACION     VARCHAR2(100)          null    ,
    FILE_SUBAPLICACION       VARCHAR2(250)          null    ,
    IMAGEN_SUBAPLICACION     VARCHAR2(250)          null    ,
    constraint PK_SUBAPLICACION primary key (ID_SUBAPLICACION)
)
/

-- ============================================================
--   Index: RELATION_433_FK                                    
-- ============================================================
create index RELATION_433_FK on SUBAPLICACION (ID_APLICACION asc)
/

-- ============================================================
--   Table: USUARIO_APLICACION                                 
-- ============================================================
create table USUARIO_APLICACION
(
    USU_CODIGO               VARCHAR2(15)           null    ,
    ID_APLICACION            INTEGER                null    
)
/

-- ============================================================
--   Index: RELATION_434_FK                                    
-- ============================================================
create index RELATION_434_FK on USUARIO_APLICACION (USU_CODIGO asc)
/

-- ============================================================
--   Index: RELATION_435_FK                                    
-- ============================================================
create index RELATION_435_FK on USUARIO_APLICACION (ID_APLICACION asc)
/

