create table pedido_stock
(
    id        int auto_increment
        primary key,
    id_comida int                                                                  not null,
    cantidad  int                                                                  not null,
    estado    enum ('Pedido', 'Transporte', 'Recibido') collate utf8mb4_general_ci not null,
    fecha     datetime                                                             not null,
    constraint comidas_de_stock_ibfk_2
        foreign key (id_comida) references comidas (id)
);

create index id_comida
    on pedido_stock (id_comida);

