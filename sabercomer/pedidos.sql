create table pedidos
(
    id                int auto_increment
        primary key,
    ID_Cliente        int                                                not null,
    Fecha_pedido      datetime                                           not null,
    Fecha_entrega     datetime                                           not null,
    Domicilio_entrega varchar(255)                                       not null,
    Precio_total      decimal(10, 2)                                     not null,
    Estado            enum ('En preparación', 'En reparto', 'Entregado') not null,
    constraint clientpedido
        foreign key (ID_Cliente) references cliente (id)
)
    collate = utf8mb4_general_ci;

