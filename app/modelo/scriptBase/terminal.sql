-- ============================================================
--   Database name:  ICARGO                                    
--   DBMS name:      ORACLE Version 8                          
--   Created on:     12/03/2006  19:30                         
-- ============================================================

-- ============================================================
--   Table: TERMINAL                                           
-- ============================================================
create table TERMINAL
(
    TER_ID      VARCHAR2(25)           not null,
    OFI_ID      VARCHAR2(10)           not null,
    TER_NOMBRE  VARCHAR2(250)          not null,
    TER_IP      VARCHAR2(30)           null    ,
    TER_PTO     NUMBER(4)              null    ,
    constraint PK_TERMINAL primary key (TER_ID, OFI_ID)
)
pctfree 10
pctused INITRANS
/

alter table TERMINAL
    add constraint FK_TER_OFI foreign key  (OFI_ID)
       references OFICINA (OFI_ID)
/

