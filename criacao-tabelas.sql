create table estado (
	id		serial 				not null primary key,
	nome	varchar(255) 	not null,
	uf		char(2)				not null
);

create table cidade (
	id				serial 				not null primary key,
	nome			varchar(255) 	not null,
	estado_id	int 					not null,
	foreign key (estado_id) references estado(id)
);

create table endereco (
	id					serial 				not null primary key,
	cidade_id		int						not null,
	cep					char(8)				not null,
	rua					varchar(255)	not null,
	bairro			varchar(255)	not null,
	numero			int						not null,
	complemento varchar(255),
	foreign key (cidade_id) references cidade(id)
);

create table cliente (
	id 					serial 			not null primary key,
	nome 				varchar(50),
	cpf 				char(11)		not null,
	fone 				char(11),
	email				varchar(255),
	endereco_id	int,
	foreign key (endereco_id) references endereco(id)
);

create table setor (
	id		serial		not null primary key,
	nome	varchar(50)
);

create table tipo_usuario (
	id		serial 		not null primary key,
	nome	varchar(50)
);

create table usuario (
	id 							serial 			not null primary key,
	nome 						varchar(50),
	cpf 						char(11)		not null,
	fone 						char(11),
	email						varchar(255),
	setor_id				int					not null,
	tipo_usuario_id	int					not null,
	foreign key (setor_id) references setor(id),
	foreign key (tipo_usuario_id) references tipo_usuario(id)
);

create table produto_categoria (
	id		serial 			not null primary key,
	nome	varchar(50)
);

create table produto (
	id 						serial 			not null primary key,
	codigo				int,
	nome					varchar(255),
	preco					decimal(10, 2),
	qtde					int,
	categoria_id 	int 				not null,
	foreign key (categoria_id) references produto_categoria(id)
);

create table venda (
	id					serial 					not null primary key,
	data_hora		timestamp				not null default current_timestamp,
	total				decimal(10,2),
	cliente_id	int							not null,
	foreign key (cliente_id) references cliente(id)
);

create table venda_item (
	venda_id		int		not null,
	produto_id	int		not null,
	valor				decimal(10,2),
	qtde				int,
	primary key (venda_id, produto_id),
	foreign key (venda_id) references venda(id),
	foreign key (produto_id) references produto(id)
);

create table fornecedor (
	id		serial				not null primary key,
	nome	varchar(50),
	cnpj	varchar(14) 	not null
);

create table pedido (
	id							serial					not null primary key,
	data_hora				timestamp				not null default current_timestamp,
	total						decimal(10, 2),
	responsavel_id	int							not null,
	fornecedor_id		int							not null,
	foreign key (responsavel_id) references usuario(id),
	foreign key (fornecedor_id) references fornecedor(id)
);

create table pedido_item (
	pedido_id			int		not null,
	produto_id		int		not null,
	valor					decimal(10,2),
	qtde					int,
	primary key (pedido_id, produto_id),
	foreign key (pedido_id) references pedido(id),
	foreign key (produto_id) references produto(id)
);

create table movimento_estoque (
	id									serial			not null primary key,
	produto_id					int					not null,
	qtde								int,
	tipo_movimento 			varchar(7)	not null,	-- ENTRADA / SAIDA
	responsavel_id			int					not null,	-- funcionario responsavel pela movimentação
	movimento_origem_id	int					not null,	-- referencia pedido se for ENTRADA, venda se for SAIDA
	foreign key (responsavel_id) references usuario(id)
);