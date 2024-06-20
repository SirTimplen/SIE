create table mensajes
(
    id           int      not null
        primary key,
    id_remitente int      not null,
    id_receptor  int      not null,
    mensaje      text     not null,
    fecha        datetime not null,
    constraint usuarmen
        foreign key (id_remitente) references usuario (id)
            on update cascade on delete cascade,
    constraint usurrecep
        foreign key (id_receptor) references usuario (id)
            on update cascade on delete cascade
)
    collate = utf8mb4_general_ci;

create index usuarmen_idx
    on mensajes (id_remitente);

create index usurrecep_idx
    on mensajes (id_receptor);

