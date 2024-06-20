create table datospago
(
    id              int auto_increment
        primary key,
    Número_tarjeta  varchar(21)  null,
    CVV             varchar(4)   null,
    Fecha_caducidad date         null,
    Domicilio       varchar(255) null,
    Teléfono        varchar(15)  null
)
    collate = utf8mb4_general_ci;

