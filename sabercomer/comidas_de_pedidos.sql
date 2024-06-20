create table comidas_de_pedidos
(
    id_pedido int not null,
    id_comida int not null,
    cantidad  int null,
    primary key (id_pedido, id_comida),
    constraint comidas_de_pedidos_ibfk_1
        foreign key (id_pedido) references pedidos (id),
    constraint comidas_de_pedidos_ibfk_2
        foreign key (id_comida) references comidas (id)
);

create index id_comida
    on comidas_de_pedidos (id_comida);

