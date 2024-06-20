create table cliente
(
    id           int auto_increment
        primary key,
    id_usuario   int                        not null,
    id_datosPago int                        null,
    nombre       varchar(45) default 'User' null,
    constraint pag
        foreign key (id_datosPago) references datospago (id),
    constraint usu
        foreign key (id_usuario) references usuario (id)
            on update cascade on delete cascade
)
    collate = utf8mb4_general_ci;

create index pag_idx
    on cliente (id_datosPago);

create index usu_idx
    on cliente (id_usuario);

