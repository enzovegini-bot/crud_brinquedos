CREATE DATABASE IF NOT EXISTS gestao_brinquedos;
USE gestao_brinquedos;

CREATE TABLE IF NOT EXISTS brinquedos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    faixa_etaria VARCHAR(30) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    quantidade_estoque INT NOT NULL DEFAULT 0
);

INSERT INTO brinquedos (nome, categoria, faixa_etaria, preco, quantidade_estoque) VALUES
('Bola de Futebol', 'Esportes', '5+', 39.90, 20),
('Quebra-Cabeça 100 peças', 'Educativo', '6+', 24.50, 15),
('Boneca Articulada', 'Bonecas', '3+', 59.90, 10);