CREATE TABLE IF NOT EXISTS tb_usuarios(
    id SERIAL NOT NULL UNIQUE,
    email VARCHAR(90) PRIMARY KEY,
    nome VARCHAR(200) NOT NULL,
    senha VARCHAR NOT NULL,
    permissao INT NOT NULL DEFAULT 6,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

------- FUNÇÃO
    CREATE OR REPLACE FUNCTION update_field_updateat_usuarios()
    RETURNS TRIGGER AS $$
    BEGIN 
        NEW.updated_at = NOW();
        RETURN NEW;
    END;
    $$ LANGUAGE plpgsql;

------ TRIGGER 
    CREATE TRIGGER update_updateat_usuarios
    BEFORE UPDATE ON tb_usuarios
    FOR EACH ROW
    EXECUTE PROCEDURE update_field_updateat_usuarios();

    -- INSERTS PARA GARANTIR USUARIO INICIAIS NA TABELAS.
    INSERT INTO tb_usuarios (email, nome, senha) VALUES ('fulano@teste.com', 'Fulano Silva', '1234'), ('funalo.junior@teste.com', 'fulano jr', '123456');


-- TABELA DE POSTS DE NOTICIAS
CREATE TABLE IF NOT EXISTS tb_noticias(
    id SERIAL NOT NULL UNIQUE,
	autor_id INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    conteudo TEXT NOT NULL,
    imagem VARCHAR NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (id),
	FOREIGN KEY (autor_id) REFERENCES tb_usuarios(id)
);


------- FUNÇÃO
    CREATE OR REPLACE FUNCTION update_field_updateat_noticias()
    RETURNS TRIGGER AS $$
    BEGIN 
        NEW.updated_at = NOW();
        RETURN NEW;
    END;
    $$ LANGUAGE plpgsql;

------ TRIGGER 
    CREATE TRIGGER update_updateat_noticias
    BEFORE UPDATE ON tb_noticias
    FOR EACH ROW
    EXECUTE PROCEDURE update_field_updateat_noticias();
