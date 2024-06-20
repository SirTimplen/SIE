create table duenio
(
    id         int auto_increment
        primary key,
    id_usuario int          not null,
    DNI        varchar(9)   not null,
    Nombre     varchar(100) not null,
    Apellidos  varchar(100) not null,
    Telefono   varchar(15)  not null,
    constraint fk_due_usu
        foreign key (id_usuario) references usuario (id)
            on update cascade on delete cascade
)
    collate = utf8mb4_general_ci;

