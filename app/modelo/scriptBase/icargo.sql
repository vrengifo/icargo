-- ============================================================
--   Database name:  ICARGO                                    
--   DBMS name:      ORACLE Version 8                          
--   Created on:     28/02/2006  20:07                         
-- ============================================================

-- ============================================================
--   Table: PARAMETRO                                          
-- ============================================================
create table PARAMETRO
(
    MON_CODIGO             CHAR(3)                null    ,
    USU_ADMIN              VARCHAR2(15)           null    ,
    PESO_UNIDAD            VARCHAR2(15)           null    
)
/

-- ============================================================
--   Table: MANEMBXBULTOQUEDA                                  
-- ============================================================
create table MANEMBXBULTOQUEDA
(
    MANEMB_ID              NUMBER(30)             not null,
    BUL_ID                 VARCHAR2(20)           not null,
    BUL_FECHA              DATE                   not null,
    BUL_ORIGEN             VARCHAR2(7)            not null,
    BUL_DESTINO            VARCHAR2(7)            not null,
    USU_AUDIT              VARCHAR2(15)           null    ,
    USU_FAUDIT             DATE                   null    ,
    constraint PK_MANEMBXBULTOQUEDA primary key (MANEMB_ID, BUL_ID, BUL_FECHA, BUL_ORIGEN, BUL_DESTINO)
)
/

-- ============================================================
--   Table: MANEMBXBULTOREAL                                   
-- ============================================================
create table MANEMBXBULTOREAL
(
    MANEMB_ID              NUMBER(30)             not null,
    BUL_ID                 VARCHAR2(20)           not null,
    BUL_FECHA              DATE                   not null,
    BUL_ORIGEN             VARCHAR2(7)            not null,
    BUL_DESTINO            VARCHAR2(7)            not null,
    USU_AUDIT              VARCHAR2(15)           null    ,
    USU_FAUDIT             DATE                   null    ,
    constraint PK_MANEMBXBULTOREAL primary key (MANEMB_ID, BUL_ID, BUL_FECHA, BUL_ORIGEN, BUL_DESTINO)
)
/

-- ============================================================
--   Table: MONEDA                                             
-- ============================================================
create table MONEDA
(
    MON_CODIGO             CHAR(3)                not null,
    MON_DESCRIPCION        VARCHAR2(30)           not null,
    constraint PK_MONEDA primary key (MON_CODIGO)
)
/

-- ============================================================
--   Table: CLIENTE                                            
-- ============================================================
create table CLIENTE
(
    CLI_CODIGO             VARCHAR2(20)           not null,
    CLI_NOMBRE             VARCHAR2(250)          not null,
    CLI_CIRUC              VARCHAR2(30)           null    ,
    CLI_CONTACTO           VARCHAR2(250)          not null,
    CLI_DIRECCION          VARCHAR2(300)          null    ,
    CLI_TELEFONO           VARCHAR2(25)           not null,
    CLI_FAX                VARCHAR2(25)           null    ,
    CLI_EMAIL              VARCHAR2(100)          null    ,
    CLI_CONVENIO           CHAR(1)                null    ,
    CLI_OBSERVACION        CLOB                   null    ,
    CLI_PORCENTAJEDESC     NUMBER(4,2)            null    ,
    constraint PK_CLIENTE primary key (CLI_CODIGO)
)
/

-- ============================================================
--   Table: PAIS                                               
-- ============================================================
create table PAIS
(
    PAI_CODIGO             CHAR(3)                not null,
    PAI_DESCRIPCION        VARCHAR2(30)           null    ,
    constraint PK_PAIS primary key (PAI_CODIGO)
)
/

-- ============================================================
--   Table: KILOEQUIVALENCIA                                   
-- ============================================================
create table KILOEQUIVALENCIA
(
    KILEQU_DESDE           NUMBER(12,2)           not null,
    KILEQU_HASTA           NUMBER(12,2)           not null,
    KILEQU_PRECIO          NUMBER(12,2)           not null,
    USU_AUDIT              VARCHAR2(15)           null    ,
    USU_FAUDIT             DATE                   null    ,
    constraint PK_KILEQU primary key (KILEQU_DESDE, KILEQU_HASTA)
)
/

-- ============================================================
--   Table: USUARIO                                            
-- ============================================================
create table USUARIO
(
    USU_CODIGO             VARCHAR2(15)           not null,
    USU_CLAVE              VARCHAR2(15)           not null,
    USU_NOMBRE             VARCHAR2(100)          null    ,
    USU_EMAIL              VARCHAR2(250)          null    ,
    constraint PK_USUARIO primary key (USU_CODIGO)
)
/

-- ============================================================
--   Table: TARJETA                                            
-- ============================================================
create table TARJETA
(
    TAR_ID                 VARCHAR2(5)            not null,
    TAR_DESCRIPCION        VARCHAR2(20)           null    ,
    constraint PK_TARJETA primary key (TAR_ID)
)
/

-- ============================================================
--   Table: FORMA_PAGO                                         
-- ============================================================
create table FORMA_PAGO
(
    FORPAG_ID              VARCHAR2(5)            not null,
    FORPAG_DESCRIPCION     VARCHAR2(30)           null    ,
    constraint PK_FORMA_PAGO primary key (FORPAG_ID)
)
/

-- ============================================================
--   Table: REPORTE_VENTA                                      
-- ============================================================
create table REPORTE_VENTA
(
    REPVEN_ID              NUMBER(20)             not null,
    REPVEN_NRO             VARCHAR2(10)           null    ,
    REPVEN_FECHA           DATE                   null    ,
    REPVEN_POR             VARCHAR2(15)           null    ,
    REPVEN_UAUDIT          VARCHAR2(15)           null    ,
    REPVEN_FAUDIT          DATE                   null    ,
    REPVEN_TOTAL_CASH      NUMBER(14,2)           null    ,
    REPVEN_TOTAL_COLLECT   NUMBER(14,2)           null    ,
    REPVEN_TOTAL_CREDITO   NUMBER(14,2)           null    ,
    REPVEN_TOTAL           NUMBER(15,2)           null    ,
    constraint PK_REPORTE_VENTA primary key (REPVEN_ID)
)
/

-- ============================================================
--   Table: APLICACION                                         
-- ============================================================
create table APLICACION
(
    ID_APLICACION          INTEGER                not null,
    NOMBRE_APLICACION      VARCHAR2(100)          null    ,
    FILE_APLICACION        VARCHAR2(100)          null    ,
    IMAGEN_APLICACION      VARCHAR2(100)          null    ,
    constraint PK_APLICACION primary key (ID_APLICACION)
)
/

-- ============================================================
--   Table: STOCK_TIPO                                         
-- ============================================================
create table STOCK_TIPO
(
    STOTIP_ID              VARCHAR2(3)            not null,
    STOTIP_NOMBRE          VARCHAR2(30)           not null,
    constraint PK_STOCK_TIPO primary key (STOTIP_ID)
)
/

-- ============================================================
--   Table: TIPO_CARGA                                         
-- ============================================================
create table TIPO_CARGA
(
    TIPCAR_ID              number(3)              not null,
    TIPCAR_DESCRIPCION     VARCHAR2(100)          not null,
    constraint PK_TIPO_CARGA primary key (TIPCAR_ID)
)
/

-- ============================================================
--   Table: IMPDESCARGO                                        
-- ============================================================
create table IMPDESCARGO
(
    IDC_ID                 NUMBER(10)             not null,
    IDC_NOMBRE             VARCHAR2(100)          null    ,
    IDC_VALOR              NUMBER(10,2)           null    ,
    IDC_OBSERVACION        CLOB                   null    ,
    constraint PK_IDCXEST primary key (IDC_ID)
)
/

-- ============================================================
--   Table: CIUDAD                                             
-- ============================================================
create table CIUDAD
(
    CIU_CODIGO             CHAR(3)                not null,
    PAI_CODIGO             CHAR(3)                null    ,
    CIU_DESCRIPCION        VARCHAR2(30)           not null,
    constraint PK_CIUDAD primary key (CIU_CODIGO)
)
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
    EST_CODIGO             VARCHAR2(7)            not null,
    CIU_CODIGO             CHAR(3)                not null,
    MON_CODIGO             CHAR(3)                null    ,
    EST_NOMBRE             VARCHAR2(30)           null    ,
    EST_RUC                VARCHAR2(13)           null    ,
    EST_AUTSRI             VARCHAR2(30)           null    ,
    constraint PK_ESTACION primary key (EST_CODIGO)
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
--   Table: OFICINA                                            
-- ============================================================
create table OFICINA
(
    OFI_ID                 VARCHAR2(10)           not null,
    EST_CODIGO             VARCHAR2(7)            null    ,
    OFI_NOMBRE             VARCHAR2(100)          null    ,
    OFI_DIRECCION          VARCHAR2(250)          null    ,
    OFI_TELF               VARCHAR2(250)          null    ,
    OFI_ENTREGA            CHAR(1)                null    ,
    OFI_RECEPCION          CHAR(1)                null    ,
    OFI_PRINCIPAL          CHAR(1)                null    ,
    constraint PK_OFICINA primary key (OFI_ID)
)
/

-- ============================================================
--   Table: BULTO                                              
-- ============================================================
create table BULTO
(
    BUL_ID                 VARCHAR2(20)           not null,
    BUL_FECHA              DATE                   not null,
    BUL_ORIGEN             VARCHAR2(7)            not null,
    BUL_DESTINO            VARCHAR2(7)            not null,
    USU_AUDIT              VARCHAR2(15)           null    ,
    USU_FAUDIT             DATE                   null    ,
    constraint PK_BULTO primary key (BUL_ID, BUL_FECHA, BUL_ORIGEN, BUL_DESTINO)
)
/

-- ============================================================
--   Table: DOCUMENTO                                          
-- ============================================================
create table DOCUMENTO
(
    STO_ID                 NUMBER(10)             not null,
    OFI_ID                 VARCHAR2(10)           not null,
    STOTIP_ID              VARCHAR2(3)            not null,
    STO_NRO                VARCHAR2(10)           not null,
    TIPCAR_ID              number(3)              null    ,
    CLI_CODIGO             VARCHAR2(20)           null    ,
    REPVEN_ID              NUMBER(20)             null    ,
    DOC_ORIGEN             VARCHAR2(7)            not null,
    DOC_DESTINO            VARCHAR2(7)            not null,
    DOC_FECHAREC           DATE                   null    ,
    DOC_NROPIEZAS          NUMBER(3)              null    ,
    DOC_PESO               NUMBER(10,2)           null    ,
    DOC_VOLUMEN            NUMBER(10,2)           null    ,
    DOC_VALORDECLARADO     NUMBER(10,2)           null    ,
    DOC_DESCRIPCION        VARCHAR2(500)          null    ,
    DOC_SOBREDOCUMENTO     CHAR(1)                null    ,
    DOC_OBSERVACION        CLOB                   null    ,
    DOC_DESTNOMBRE         VARCHAR2(250)          null    ,
    DOC_DESTCIRUC          VARCHAR2(100)          null    ,
    DOC_DESTTELF           VARCHAR2(200)          null    ,
    DOC_SUBTOTAL           NUMBER(12,2)           null    ,
    DOC_TOTAL              NUMBER(14,2)           null    ,
    USU_AUDIT              VARCHAR2(15)           null    ,
    USU_FAUDIT             DATE                   null    ,
    constraint PK_DOCUMENTO primary key (STO_ID, OFI_ID, STOTIP_ID, STO_NRO)
)
/

-- ============================================================
--   Table: MANIFIESTO_EMBARQUE                                
-- ============================================================
create table MANIFIESTO_EMBARQUE
(
    MANEMB_ID              NUMBER(30)             not null,
    MANEMB_NRO             VARCHAR2(15)           null    ,
    MANEMB_VUELO           VARCHAR2(20)           null    ,
    MANEMB_FECHA           DATE                   null    ,
    MANEMB_POR             VARCHAR2(15)           null    ,
    MANEMB_ORIGEN          VARCHAR2(7)            null    ,
    MANEMB_DESTINO         VARCHAR2(7)            null    ,
    constraint PK_MANIFIESTO_EMBARQUE primary key (MANEMB_ID)
)
/

-- ============================================================
--   Table: STOCKXOFICINA                                      
-- ============================================================
create table STOCKXOFICINA
(
    STO_ID                 NUMBER(10)             not null,
    OFI_ID                 VARCHAR2(10)           not null,
    STOTIP_ID              VARCHAR2(3)            not null,
    STO_FECHA              DATE                   null    ,
    STO_FECHAEXP           DATE                   null    ,
    STOOFI_INI             VARCHAR2(10)           null    ,
    STOOFI_FIN             VARCHAR2(10)           null    ,
    STOOFI_ACTUAL          VARCHAR2(10)           null    ,
    USU_AUDIT              VARCHAR2(15)           null    ,
    USU_FAUDIT             DATE                   null    ,
    constraint PK_STOCKXOFICINA primary key (STO_ID, OFI_ID, STOTIP_ID)
)
/

-- ============================================================
--   Table: TERMINAL                                           
-- ============================================================
create table TERMINAL
(
    TER_ID                 VARCHAR2(25)           not null,
    OFI_ID                 VARCHAR2(10)           not null,
    TER_NOMBRE             VARCHAR2(250)          not null,
    TER_IP                 VARCHAR2(30)           null    ,
    TER_PTO                NUMBER(4)              null    ,
    constraint PK_TERMINAL primary key (TER_ID, OFI_ID)
)
pctfree 10
pctused INITRANS
/

-- ============================================================
--   Table: STOCK_HISTORIAL                                    
-- ============================================================
create table STOCK_HISTORIAL
(
    STO_ID                 NUMBER(10)             not null,
    OFI_ID                 VARCHAR2(10)           not null,
    STOTIP_ID              VARCHAR2(3)            not null,
    STO_NRO                VARCHAR2(10)           not null,
    STO_FECHA              DATE                   null    ,
    STO_OBSERVACION        CLOB                   null    ,
    USU_AUDIT              VARCHAR2(15)           null    ,
    USU_FAUDIT             DATE                   null    ,
    constraint PK_STOCK_HISTORIAL primary key (STO_ID, OFI_ID, STOTIP_ID, STO_NRO)
)
/

-- ============================================================
--   Table: MANDESXBULTO                                       
-- ============================================================
create table MANDESXBULTO
(
    MANEMB_ID              NUMBER(30)             not null,
    BUL_ID                 VARCHAR2(20)           not null,
    BUL_FECHA              DATE                   not null,
    BUL_ORIGEN             VARCHAR2(7)            not null,
    BUL_DESTINO            VARCHAR2(7)            not null,
    USU_AUDIT              VARCHAR2(15)           null    ,
    USU_FAUDIT             DATE                   null    ,
    constraint PK_MANDESXBULTO primary key (MANEMB_ID, BUL_ID, BUL_FECHA, BUL_ORIGEN, BUL_DESTINO)
)
/

-- ============================================================
--   Table: DETALLEDOCUMENTO                                   
-- ============================================================
create table DETALLEDOCUMENTO
(
    DETDOC_REF             varchar2(250)          not null,
    STO_ID                 NUMBER(10)             null    ,
    OFI_ID                 VARCHAR2(10)           null    ,
    STOTIP_ID              VARCHAR2(3)            null    ,
    STO_NRO                VARCHAR2(10)           null    ,
    constraint PK_DETDOC primary key (DETDOC_REF)
)
/

-- ============================================================
--   Table: MANIFIESTO_DESEMBARQUE                             
-- ============================================================
create table MANIFIESTO_DESEMBARQUE
(
    MANEMB_ID              NUMBER(30)             not null,
    MANDES_FECHAREC        DATE                   null    ,
    MANDES_POR             VARCHAR2(15)           null    ,
    constraint PK_MANIFIESTO_DESEMBARQUE primary key (MANEMB_ID)
)
/

-- ============================================================
--   Table: TIPOCAMBIO                                         
-- ============================================================
create table TIPOCAMBIO
(
    MON_CODIGO             CHAR(3)                not null,
    EST_CODIGO             VARCHAR2(7)            not null,
    TIPCAM_FECHA           DATE                   not null,
    TIPCAM_VALOR           NUMBER(12,4)           null    ,
    constraint PK_TIPOCAMBIO primary key (MON_CODIGO, EST_CODIGO)
)
/

-- ============================================================
--   Index: RELATION_36_FK                                     
-- ============================================================
create index RELATION_36_FK on TIPOCAMBIO (EST_CODIGO asc)
/

-- ============================================================
--   Index: RELATION_41_FK                                     
-- ============================================================
create index RELATION_41_FK on TIPOCAMBIO (MON_CODIGO asc)
/

-- ============================================================
--   Table: FORMA_PAGOXDOC                                     
-- ============================================================
create table FORMA_PAGOXDOC
(
    FORPAG_ID              VARCHAR2(5)            not null,
    TAR_ID                 VARCHAR2(5)            null    ,
    STO_ID                 NUMBER(10)             not null,
    OFI_ID                 VARCHAR2(10)           not null,
    STOTIP_ID              VARCHAR2(3)            not null,
    STO_NRO                VARCHAR2(10)           not null,
    FPG_FECHA              DATE                   null    ,
    FPG_VALOR              NUMBER(20,2)           null    ,
    TARJETA_NRO            VARCHAR2(30)           null    ,
    TARJETA_NRO_DOCUMENTO  VARCHAR2(20)           null    ,
    constraint PK_FORMA_PAGOXDOC primary key (FORPAG_ID, STO_ID, OFI_ID, STOTIP_ID, STO_NRO)
)
/

-- ============================================================
--   Table: SUBAPLICACION                                      
-- ============================================================
create table SUBAPLICACION
(
    ID_SUBAPLICACION       INTEGER                not null,
    ID_APLICACION          INTEGER                null    ,
    NOMBRE_SUBAPLICACION   VARCHAR2(100)          null    ,
    FILE_SUBAPLICACION     VARCHAR2(250)          null    ,
    IMAGEN_SUBAPLICACION   VARCHAR2(250)          null    ,
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
    USU_CODIGO             VARCHAR2(15)           null    ,
    ID_APLICACION          INTEGER                null    
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

-- ============================================================
--   Table: MANEMB_DETALLE                                     
-- ============================================================
create table MANEMB_DETALLE
(
    MANEMB_ID              NUMBER(30)             not null,
    STO_ID                 NUMBER(10)             not null,
    OFI_ID                 VARCHAR2(10)           not null,
    STOTIP_ID              VARCHAR2(3)            not null,
    STO_NRO                VARCHAR2(10)           not null,
    constraint PK_MANEMB_DETALLE primary key (MANEMB_ID, STO_ID, OFI_ID, STOTIP_ID, STO_NRO)
)
/

-- ============================================================
--   Table: MANEMBXBULTO                                       
-- ============================================================
create table MANEMBXBULTO
(
    MANEMB_ID              NUMBER(30)             not null,
    BUL_ID                 VARCHAR2(20)           not null,
    BUL_FECHA              DATE                   not null,
    BUL_ORIGEN             VARCHAR2(7)            not null,
    BUL_DESTINO            VARCHAR2(7)            not null,
    USU_AUDIT              VARCHAR2(15)           null    ,
    USU_FAUDIT             DATE                   null    ,
    constraint PK_MANEMBXBULTO primary key (MANEMB_ID, BUL_ID, BUL_FECHA, BUL_ORIGEN, BUL_DESTINO)
)
/

-- ============================================================
--   Table: DES_PAQUETE                                        
-- ============================================================
create table DES_PAQUETE
(
    MANEMB_ID              NUMBER(30)             not null,
    BUL_ID                 VARCHAR2(20)           not null,
    BUL_FECHA              DATE                   not null,
    BUL_ORIGEN             VARCHAR2(7)            not null,
    BUL_DESTINO            VARCHAR2(7)            not null,
    DETDOC_REF             VARCHAR2(250)          not null,
    UBICACION              VARCHAR2(200)          null    ,
    USU_AUDIT              VARCHAR2(15)           null    ,
    USU_FAUDIT             DATE                   null    ,
    constraint PK_DES_PAQUETE primary key (MANEMB_ID, BUL_ID, BUL_FECHA, BUL_ORIGEN, BUL_DESTINO, DETDOC_REF)
)
/

-- ============================================================
--   Table: USUARIOXOFICINA                                    
-- ============================================================
create table USUARIOXOFICINA
(
    USU_CODIGO             VARCHAR2(15)           not null,
    OFI_ID                 VARCHAR2(10)           not null,
    USUXOFI_ADMIN          CHAR(1)                null    ,
    constraint PK_USUARIOXOFICINA primary key (USU_CODIGO, OFI_ID)
)
/

-- ============================================================
--   Table: IMPRESIONXTERMINAL                                 
-- ============================================================
create table IMPRESIONXTERMINAL
(
    STOTIP_ID              VARCHAR2(3)            not null,
    TER_ID                 VARCHAR2(25)           not null,
    OFI_ID                 VARCHAR2(10)           not null,
    TER_ID_IMP             VARCHAR2(25)           not null,
    OFI_ID_IMP             VARCHAR2(10)           not null,
    constraint PK_IMPRESIONXTERMINAL primary key (STOTIP_ID, TER_ID, OFI_ID, TER_ID_IMP, OFI_ID_IMP)
)
/

-- ============================================================
--   Table: IMPDESCARXSTOTIP                                   
-- ============================================================
create table IMPDESCARXSTOTIP
(
    IDC_ID                 NUMBER(10)             not null,
    STOTIP_ID              VARCHAR2(3)            not null,
    IDCXTIP_ORDEN          NUMBER(2)              null    ,
    constraint PK_IMPDESCARXSTOTIP primary key (IDC_ID, STOTIP_ID)
)
/

-- ============================================================
--   Table: IMPDESCARXEST                                      
-- ============================================================
create table IMPDESCARXEST
(
    IDC_ID                 NUMBER(10)             not null,
    EST_CODIGO             VARCHAR2(7)            not null,
    IDC_VALOR              NUMBER(10,2)           null    ,
    constraint PK_IMPDESCARXEST primary key (IDC_ID, EST_CODIGO)
)
/

-- ============================================================
--   Table: IMPDESCARXDOC                                      
-- ============================================================
create table IMPDESCARXDOC
(
    IDC_ID                 NUMBER(10)             not null,
    STO_ID                 NUMBER(10)             not null,
    OFI_ID                 VARCHAR2(10)           not null,
    STOTIP_ID              VARCHAR2(3)            not null,
    STO_NRO                VARCHAR2(10)           not null,
    IDC_VALOR              NUMBER(10,2)           null    ,
    IDCXDOC_VALOR          NUMBER(12,2)           null    ,
    constraint PK_IMPDESCARXDOC primary key (IDC_ID, STO_ID, OFI_ID, STOTIP_ID, STO_NRO)
)
/

-- ============================================================
--   Table: DETALLE_BULTO                                      
-- ============================================================
create table DETALLE_BULTO
(
    DETDOC_REF             varchar2(250)          not null,
    BUL_ID                 VARCHAR2(20)           not null,
    BUL_FECHA              DATE                   not null,
    BUL_ORIGEN             VARCHAR2(7)            not null,
    BUL_DESTINO            VARCHAR2(7)            not null,
    constraint PK_DETBUL primary key (DETDOC_REF, BUL_ID, BUL_FECHA, BUL_ORIGEN, BUL_DESTINO)
)
/

-- ============================================================
--   Table: KILOEQUIVALENCIAXEST                               
-- ============================================================
create table KILOEQUIVALENCIAXEST
(
    KILEQU_DESDE           NUMBER(12,2)           not null,
    KILEQU_HASTA           NUMBER(12,2)           not null,
    EST_CODIGO             VARCHAR2(7)            not null,
    KILEQU_PRECIO          NUMBER(12,2)           not null,
    USU_AUDIT              VARCHAR2(15)           null    ,
    USU_FAUDIT             DATE                   null    ,
    constraint PK_KILEQUXEST primary key (KILEQU_DESDE, KILEQU_HASTA, EST_CODIGO)
)
/

alter table OFICINA
    add constraint FK_OFICINA_OFIXEST_ESTACION foreign key  (EST_CODIGO)
       references ESTACION (EST_CODIGO)
/

alter table BULTO
    add constraint FK_BULTO_BULXEST1_ESTACION foreign key  (BUL_ORIGEN)
       references ESTACION (EST_CODIGO)
/

alter table BULTO
    add constraint FK_BULTO_BULXEST2_ESTACION foreign key  (BUL_DESTINO)
       references ESTACION (EST_CODIGO)
/

alter table DOCUMENTO
    add constraint FK_DOCUMENT_DOCXSTOHI_STOCK_HI foreign key  (STO_ID, OFI_ID, STOTIP_ID, STO_NRO)
       references STOCK_HISTORIAL (STO_ID, OFI_ID, STOTIP_ID, STO_NRO)
/

alter table DOCUMENTO
    add constraint FK_DOCUMENT_DOCXTIPCA_TIPO_CAR foreign key  (TIPCAR_ID)
       references TIPO_CARGA (TIPCAR_ID)
/

alter table DOCUMENTO
    add constraint FK_DOCUMENT_CLIXDOC_CLIENTE foreign key  (CLI_CODIGO)
       references CLIENTE (CLI_CODIGO)
/

alter table DOCUMENTO
    add constraint FK_DOCUMENT_DOCXRV_REPORTE_ foreign key  (REPVEN_ID)
       references REPORTE_VENTA (REPVEN_ID)
/

alter table DOCUMENTO
    add constraint FK_DOCUMENT_DOCXEST1_ESTACION foreign key  (DOC_ORIGEN)
       references ESTACION (EST_CODIGO)
/

alter table DOCUMENTO
    add constraint FK_DOCUMENT_DOCXEST2_ESTACION foreign key  (DOC_DESTINO)
       references ESTACION (EST_CODIGO)
/

alter table MANIFIESTO_EMBARQUE
/

alter table MANIFIESTO_EMBARQUE
/

alter table STOCKXOFICINA
    add constraint FK_STOCKXOF_STOXOFI_O_OFICINA foreign key  (OFI_ID)
       references OFICINA (OFI_ID)
/

alter table STOCKXOFICINA
    add constraint FK_STOCKXOF_STOOFI_ST_STOCK_TI foreign key  (STOTIP_ID)
       references STOCK_TIPO (STOTIP_ID)
/

alter table TERMINAL
    add constraint FK_TER_OFI foreign key  (OFI_ID)
       references OFICINA (OFI_ID)
/

alter table STOCK_HISTORIAL
    add constraint FK_STOCK_HI_STOHIS_ST_STOCKXOF foreign key  (STO_ID, OFI_ID, STOTIP_ID)
       references STOCKXOFICINA (STO_ID, OFI_ID, STOTIP_ID)
/

alter table MANDESXBULTO
    add constraint FK_MANDESXB_MANDESXBU_MANIFIES foreign key  (MANEMB_ID)
       references MANIFIESTO_DESEMBARQUE (MANEMB_ID)
/

alter table MANDESXBULTO
    add constraint FK_MANDESXB_MANDESXBU_BULTO foreign key  (BUL_ID, BUL_FECHA, BUL_ORIGEN, BUL_DESTINO)
       references BULTO (BUL_ID, BUL_FECHA, BUL_ORIGEN, BUL_DESTINO)
/

alter table DETALLEDOCUMENTO
    add constraint FK_DETALLED_DETDOC_DO_DOCUMENT foreign key  (STO_ID, OFI_ID, STOTIP_ID, STO_NRO)
       references DOCUMENTO (STO_ID, OFI_ID, STOTIP_ID, STO_NRO)
/

alter table MANIFIESTO_DESEMBARQUE
    add constraint FK_MANIFIES_RMANEMBXM_MANIFIES foreign key  (MANEMB_ID)
       references MANIFIESTO_EMBARQUE (MANEMB_ID) on delete cascade
/

alter table FORMA_PAGOXDOC
    add constraint FK_FORMA_PA_FORMAPAG__DOCUMENT foreign key  (STO_ID, OFI_ID, STOTIP_ID, STO_NRO)
       references DOCUMENTO (STO_ID, OFI_ID, STOTIP_ID, STO_NRO)
/

alter table MANEMB_DETALLE
    add constraint FK_MANEMB_D_RMANEMB_M_MANIFIES foreign key  (MANEMB_ID)
       references MANIFIESTO_EMBARQUE (MANEMB_ID) on delete cascade
/

alter table MANEMB_DETALLE
    add constraint FK_MANEMB_D_DOCXMANEM_DOCUMENT foreign key  (STO_ID, OFI_ID, STOTIP_ID, STO_NRO)
       references DOCUMENTO (STO_ID, OFI_ID, STOTIP_ID, STO_NRO)
/

alter table MANEMBXBULTO
    add constraint FK_MANEMBXB_MANEMBXBU_BULTO foreign key  (BUL_ID, BUL_FECHA, BUL_ORIGEN)
       references BULTO (BUL_ID, BUL_FECHA, BUL_ORIGEN)
/

alter table MANEMBXBULTO
    add constraint FK_MANEMBXB_MANEMBXBU_MANIFIES foreign key  (MANEMB_ID)
       references MANIFIESTO_EMBARQUE (MANEMB_ID)
/

alter table DES_PAQUETE
    add constraint FK_DES_PAQU_DESPAQ_MA_MANDESXB foreign key  (MANEMB_ID, BUL_ID)
       references MANDESXBULTO (MANEMB_ID, BUL_ID)
/

alter table USUARIOXOFICINA
    add constraint FK_USUARIOX_USUXOFI_U_USUARIO foreign key  (USU_CODIGO)
       references USUARIO (USU_CODIGO)
/

alter table USUARIOXOFICINA
    add constraint FK_USUARIOX_USUXOFI_O_OFICINA foreign key  (OFI_ID)
       references OFICINA (OFI_ID)
/

alter table IMPRESIONXTERMINAL
    add constraint FK_IMPRESIO_IMPXTER_S_STOCK_TI foreign key  (STOTIP_ID)
       references STOCK_TIPO (STOTIP_ID)
/

alter table IMPRESIONXTERMINAL
    add constraint FK_IMPTERXTER_1 foreign key  (TER_ID, OFI_ID)
       references TERMINAL (TER_ID, OFI_ID)
/

alter table IMPRESIONXTERMINAL
    add constraint FK_IMPTERXTER_2 foreign key  (TER_ID_IMP, OFI_ID_IMP)
       references TERMINAL (TER_ID, OFI_ID)
/

alter table IMPDESCARXSTOTIP
    add constraint FK_IDCXST_IDC foreign key  (IDC_ID)
       references IMPDESCARGO (IDC_ID)
/

alter table IMPDESCARXSTOTIP
    add constraint FK_IMPDESCA_IMPDESCAR_STOCK_TI foreign key  (STOTIP_ID)
       references STOCK_TIPO (STOTIP_ID)
/

alter table IMPDESCARXEST
    add constraint FK_IDCE_IDC foreign key  (IDC_ID)
       references IMPDESCARGO (IDC_ID)
/

alter table IMPDESCARXEST
    add constraint FK_IMPDESCA_IMPDESCAR_ESTACION foreign key  (EST_CODIGO)
       references ESTACION (EST_CODIGO)
/

alter table IMPDESCARXDOC
    add constraint FK_IDCD_IDC foreign key  (IDC_ID)
       references IMPDESCARGO (IDC_ID)
/

alter table IMPDESCARXDOC
    add constraint FK_IMPDESCA_IMPDESCAR_DOCUMENT foreign key  (STO_ID, OFI_ID, STOTIP_ID, STO_NRO)
       references DOCUMENTO (STO_ID, OFI_ID, STOTIP_ID, STO_NRO)
/

alter table DETALLE_BULTO
    add constraint FK_DETALLE__DETDOCXDE_DETALLED foreign key  (DETDOC_REF)
       references DETALLEDOCUMENTO (DETDOC_REF)
/

alter table DETALLE_BULTO
    add constraint FK_DETALLE__DETBULXBU_BULTO foreign key  (BUL_ID)
       references BULTO (BUL_ID)
/

alter table KILOEQUIVALENCIAXEST
    add constraint FK_KILOEQUI_KILEQUXES_KILOEQUI foreign key  (KILEQU_DESDE, KILEQU_HASTA)
       references KILOEQUIVALENCIA (KILEQU_DESDE, KILEQU_HASTA)
/

alter table KILOEQUIVALENCIAXEST
    add constraint FK_KILOEQUI_KILEQUXES_ESTACION foreign key  (EST_CODIGO)
       references ESTACION (EST_CODIGO)
/

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


