create table comidas
(
    id               int auto_increment
        primary key,
    Nombre           varchar(100)                             not null,
    Precio           decimal(10, 2)                           not null,
    Peso             decimal(10, 2)                           not null,
    Calorías         int                                      null,
    Ingredientes     text                                     null,
    Valoración_media decimal(3, 2)                            null,
    Tipo             enum ('Entrante', 'Principal', 'Postre') not null,
    Stock            int default 0                            not null
)
    collate = utf8mb4_general_ci;

