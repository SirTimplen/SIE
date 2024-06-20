create table valoracion
(
    id         int auto_increment
        primary key,
    id_comida  int          not null,
    id_usuario int          not null,
    valoracion int          not null,
    Comentario varchar(255) null,
    constraint comidia
        foreign key (id_comida) references comidas (id),
    constraint usuaat
        foreign key (id_usuario) references usuario (id)
            on update cascade on delete cascade
);

create index comidia_idx
    on valoracion (id_comida);

create index usuaat_idx
    on valoracion (id_usuario);

