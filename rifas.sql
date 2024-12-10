CREATE TABLE rifas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_rifa INT NOT NULL,
    nome VARCHAR(255) DEFAULT '',
    telefone VARCHAR(20) DEFAULT '',
    situacao_pagamento VARCHAR(20) DEFAULT ''
);
