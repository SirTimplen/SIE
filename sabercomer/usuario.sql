create table usuario
(
    id         int          not null
        primary key,
    email      varchar(100) not null,
    contraseña varchar(100) not null,
    fecha_alta datetime     not null,
    fecha_baja datetime     null
)
    collate = utf8mb4_general_ci;

