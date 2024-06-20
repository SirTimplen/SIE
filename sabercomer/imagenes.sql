create table imagenes
(
    IDImagen int auto_increment
        primary key,
    IDComida int      not null,
    imagen   longblob not null,
    constraint com
        foreign key (IDComida) references comidas (id)
            on update cascade on delete cascade
)
    collate = utf8mb3_bin;

create index com_idx
    on imagenes (IDComida);

