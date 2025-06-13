--01) função 01
CREATE OR REPLACE FUNCTION fn_total_compras_cliente(cliente_id_param INT)
RETURNS DECIMAL(10, 2) AS $$
DECLARE
    total_compras DECIMAL(10, 2);
BEGIN
    SELECT SUM(total)
    INTO total_compras
    FROM venda
    WHERE cliente_id = cliente_id_param;

    RETURN COALESCE(total_compras, 0);
END;
$$ LANGUAGE plpgsql;

--02) função 02

CREATE OR REPLACE FUNCTION fn_estoque_atual_produto(produto_id_param INT)
RETURNS INT AS $$
DECLARE
    estoque_atual INT;
BEGIN
    SELECT qtde
    INTO estoque_atual
    FROM produto
    WHERE id = produto_id_param;

    RETURN estoque_atual;
END;
$$ LANGUAGE plpgsql;

-- Procedure

CREATE OR REPLACE PROCEDURE sp_registrar_venda(
    cliente_id_param INT,
    produto_id_param INT,
    qtde_vendida_param INT,
    responsavel_id_param INT
)
LANGUAGE plpgsql
AS $$
DECLARE
    venda_id_novo INT;
    preco_produto DECIMAL(10, 2);
    estoque_atual INT;
BEGIN

    estoque_atual := fn_estoque_atual_produto(produto_id_param);
    IF estoque_atual < qtde_vendida_param THEN
        RAISE EXCEPTION 'Estoque insuficiente para o produto ID %', produto_id_param;
    END IF;


    SELECT preco INTO preco_produto FROM produto WHERE id = produto_id_param;


    INSERT INTO venda (data_hora, total, cliente_id)
    VALUES (NOW(), preco_produto * qtde_vendida_param, cliente_id_param)
    RETURNING id INTO venda_id_novo;


    INSERT INTO venda_item (venda_id, produto_id, valor, qtde)
    VALUES (venda_id_novo, produto_id_param, preco_produto, qtde_vendida_param);

    UPDATE produto
    SET qtde = qtde - qtde_vendida_param
    WHERE id = produto_id_param;


END;
$$;

--trigger 

CREATE OR REPLACE FUNCTION tg_log_movimento_estoque()
RETURNS TRIGGER AS $$
DECLARE
    tipo_mov VARCHAR(7);
    qtde_movimentada INT;
BEGIN
    IF TG_OP = 'UPDATE' THEN
        qtde_movimentada := NEW.qtde - OLD.qtde;

        IF qtde_movimentada < 0 THEN
            tipo_mov := 'SAIDA';
            qtde_movimentada := qtde_movimentada * -1; 
        ELSE
            tipo_mov := 'ENTRADA';
        END IF;

        INSERT INTO movimento_estoque(produto_id, qtde, tipo_movimento, responsavel_id)
        VALUES(NEW.id, qtde_movimentada, tipo_mov, NULL); 
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER tr_movimento_estoque
AFTER UPDATE ON produto
FOR EACH ROW
WHEN (OLD.qtde IS DISTINCT FROM NEW.qtde)
EXECUTE FUNCTION tg_log_movimento_estoque();

--view 01

CREATE OR REPLACE VIEW vw_relatorio_vendas AS
SELECT
    v.id AS venda_id,
    v.data_hora,
    c.nome AS cliente_nome,
    c.cpf AS cliente_cpf,
    p.nome AS produto_nome,
    vi.qtde,
    vi.valor AS valor_unitario,
    v.total AS valor_total_venda
FROM venda v
JOIN cliente c ON v.cliente_id = c.id
JOIN venda_item vi ON v.id = vi.venda_id
JOIN produto p ON vi.produto_id = p.id;

--view 02

CREATE OR REPLACE VIEW vw_produtos_detalhes AS
SELECT
    p.id AS produto_id,
    p.codigo,
    p.nome AS produto_nome,
    pc.nome AS categoria,
    p.preco,
    p.qtde AS estoque_atual
FROM produto p
JOIN produto_categoria pc ON p.categoria_id = pc.id;

-- view 03

CREATE OR REPLACE VIEW vw_usuarios_detalhes AS
SELECT
    u.id AS usuario_id,
    u.nome AS usuario_nome,
    u.email,
    s.nome AS setor,
    tu.nome AS tipo_usuario
FROM usuario u
JOIN setor s ON u.setor_id = s.id
JOIN tipo_usuario tu ON u.tipo_usuario_id = tu.id;

-- indices

-- Índice na tabela de cliente para buscas rápidas por nome.
CREATE INDEX idx_cliente_nome ON cliente(nome);

-- Índice na tabela de produto para otimizar a busca por nome.
CREATE INDEX idx_produto_nome ON produto(nome);

-- Índice na tabela de venda para acelerar consultas por data.
CREATE INDEX idx_venda_data_hora ON venda(data_hora);

-- Índice no CPF do cliente, pois é um campo de busca comum e único.
CREATE UNIQUE INDEX idx_cliente_cpf ON cliente(cpf);

-- Índice no CNPJ do fornecedor, que também é um campo de busca comum e único.
CREATE UNIQUE INDEX idx_fornecedor_cnpj ON fornecedor(cnpj);

