-- IMPORTANTE: realizar esses inserts apenas após os inserts de estados e de cidades

INSERT INTO endereco (cidade_id, cep, rua, bairro, numero, complemento) VALUES (1, '01001000', 'Rua XV de Novembro', 'Centro', 100, 'Apto 101');
INSERT INTO endereco (cidade_id, cep, rua, bairro, numero) VALUES (1, '01002000', 'Rua da Consolação', 'Bela Vista', 250);
INSERT INTO endereco (cidade_id, cep, rua, bairro, numero) VALUES (2, '13010000', 'Avenida Brasil', 'Jardim Chapadão', 300);
INSERT INTO endereco (cidade_id, cep, rua, bairro, numero, complemento) VALUES (2, '13020000', 'Rua Barão de Jaguara', 'Centro', 120, 'Fundos');
INSERT INTO endereco (cidade_id, cep, rua, bairro, numero, complemento) VALUES (3, '20040000', 'Avenida Atlântica', 'Copacabana', 50, 'Cobertura');
INSERT INTO endereco (cidade_id, cep, rua, bairro, numero) VALUES (3, '20050000', 'Rua das Laranjeiras', 'Laranjeiras', 430);
INSERT INTO endereco (cidade_id, cep, rua, bairro, numero, complemento) VALUES (4, '24020000', 'Rua Moreira César', 'Icaraí', 80, 'Loja 2');
INSERT INTO endereco (cidade_id, cep, rua, bairro, numero, complemento) VALUES (5, '30140000', 'Avenida Afonso Pena', 'Funcionários', 950, 'Apto 402');
INSERT INTO endereco (cidade_id, cep, rua, bairro, numero) VALUES (5, '30150000', 'Rua da Bahia', 'Centro', 600);
INSERT INTO endereco (cidade_id, cep, rua, bairro, numero, complemento) VALUES (6, '38400000', 'Avenida Rondon Pacheco', 'Saraiva', 1100, 'Bloco C');

INSERT INTO cliente (nome, cpf, fone, email, endereco_id) VALUES
('João Silva', '12345678901', '11999999999', 'joao@example.com', 1),
('Maria Souza', '23456789012', '11988888888', 'maria@example.com', 2),
('Carlos Lima', '34567890123', '11977777777', 'carlos@example.com', 3),
('Ana Costa', '45678901234', '11966666666', 'ana@example.com', 4),
('Pedro Rocha', '56789012345', '11955555555', 'pedro@example.com', 5),
('Julia Alves', '67890123456', '11944444444', 'julia@example.com', 6),
('Lucas Martins', '78901234567', '11933333333', 'lucas@example.com', 7),
('Fernanda Dias', '89012345678', '11922222222', 'fernanda@example.com', 8),
('Marcos Teixeira', '90123456789', '11911111111', 'marcos@example.com', 9),
('Patrícia Ramos', '01234567890', '11900000000', 'patricia@example.com', 10);

INSERT INTO setor (nome) VALUES ('Vendas'), ('Compras'), ('TI');

INSERT INTO tipo_usuario (nome) VALUES ('Administrador'), ('Operador');

INSERT INTO usuario (nome, cpf, fone, email, setor_id, tipo_usuario_id) VALUES
('Carlos Souza', '12312312312', '11999999990', 'carlos@empresa.com', 1, 1),
('Joana Mendes', '22312312312', '11999999991', 'joana@empresa.com', 2, 2),
('Ricardo Alves', '32312312312', '11999999992', 'ricardo@empresa.com', 3, 1),
('Camila Lima', '42312312312', '11999999993', 'camila@empresa.com', 1, 2),
('Paulo Dias', '52312312312', '11999999994', 'paulo@empresa.com', 2, 1),
('Luciana Braga', '62312312312', '11999999995', 'luciana@empresa.com', 3, 2),
('Tiago Nunes', '72312312312', '11999999996', 'tiago@empresa.com', 1, 1),
('Sandra Reis', '82312312312', '11999999997', 'sandra@empresa.com', 2, 2),
('Diego Pinto', '92312312312', '11999999998', 'diego@empresa.com', 3, 1),
('Beatriz Gomes', '10312312312', '11999999999', 'beatriz@empresa.com', 1, 2);

INSERT INTO produto_categoria (nome) VALUES ('Informática'), ('Eletrônicos'), ('Escritório');

INSERT INTO produto (codigo, nome, preco, qtde, categoria_id) VALUES
(1001, 'Mouse', 49.90, 100, 1),
(1002, 'Teclado', 89.90, 80, 1),
(1003, 'Monitor', 599.00, 30, 2),
(1004, 'Notebook', 2499.00, 20, 2),
(1005, 'Impressora', 399.00, 15, 3),
(1006, 'Cadeira', 299.00, 40, 3),
(1007, 'Caneta', 2.50, 500, 3),
(1008, 'Headset', 149.00, 50, 1),
(1009, 'Webcam', 199.00, 25, 2),
(1010, 'Estabilizador', 120.00, 35, 2);

INSERT INTO venda (data_hora, total, cliente_id) VALUES
(NOW(), 599.00, 1),
(NOW(), 89.90, 2),
(NOW(), 299.00, 3),
(NOW(), 49.90, 4),
(NOW(), 2499.00, 5),
(NOW(), 199.00, 6),
(NOW(), 149.00, 7),
(NOW(), 120.00, 8),
(NOW(), 399.00, 9),
(NOW(), 2.50, 10);

INSERT INTO venda_item (venda_id, produto_id, valor, qtde) VALUES
(1, 3, 599.00, 1),
(2, 2, 89.90, 1),
(3, 6, 299.00, 1),
(4, 1, 49.90, 1),
(5, 4, 2499.00, 1),
(6, 9, 199.00, 1),
(7, 8, 149.00, 1),
(8, 10, 120.00, 1),
(9, 5, 399.00, 1),
(10, 7, 2.50, 1);

INSERT INTO fornecedor (nome, cnpj) VALUES
('TechDistribuidora', '12345678000100'),
('OfficeMax', '23456789000111'),
('Eletronix', '34567890000122'),
('CompTech', '45678901000133'),
('Papelaria Global', '56789012000144'),
('Infoparts', '67890123000155'),
('Audio&Video Ltda', '78901234000166'),
('Conecte Informática', '89012345000177'),
('MegaDistrib', '90123456000188'),
('Estabilize Tech', '01234567000199');

INSERT INTO pedido (data_hora, total, responsavel_id, fornecedor_id) VALUES
(NOW(), 1000.00, 1, 1),
(NOW(), 800.00, 2, 2),
(NOW(), 1500.00, 3, 3),
(NOW(), 1200.00, 4, 4),
(NOW(), 500.00, 5, 5),
(NOW(), 700.00, 6, 6),
(NOW(), 300.00, 7, 7),
(NOW(), 950.00, 8, 8),
(NOW(), 1100.00, 9, 9),
(NOW(), 400.00, 10, 10);

INSERT INTO pedido_item (pedido_id, produto_id, valor, qtde) VALUES
(1, 1, 45.00, 10),
(2, 2, 80.00, 10),
(3, 3, 500.00, 3),
(4, 4, 2000.00, 1),
(5, 5, 350.00, 2),
(6, 6, 250.00, 3),
(7, 7, 2.00, 100),
(8, 8, 120.00, 5),
(9, 9, 180.00, 4),
(10, 10, 100.00, 4);

INSERT INTO movimento_estoque (produto_id, qtde, tipo_movimento, responsavel_id, movimento_origem_id) VALUES
(1, 10, 'ENTRADA', 1, 1),
(2, 10, 'ENTRADA', 2, 2),
(3, 3, 'ENTRADA', 3, 3),
(4, 1, 'ENTRADA', 4, 4),
(5, 2, 'ENTRADA', 5, 5),
(3, 1, 'SAIDA', 1, 1),
(2, 1, 'SAIDA', 2, 2),
(6, 1, 'SAIDA', 3, 3),
(1, 1, 'SAIDA', 4, 4),
(4, 1, 'SAIDA', 5, 5);