create table carrito
(
    id_comida  int auto_increment,
    id_usuario int not null,
    cantidad   int null,
    primary key (id_comida, id_usuario)
)
    collate = utf8mb4_general_ci;

create index usuario_idx
    on carrito (id_usuario);

