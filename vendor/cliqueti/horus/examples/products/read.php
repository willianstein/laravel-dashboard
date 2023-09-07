<?php

use CliqueTI\Horus\Products;

require __DIR__.'/../../vendor/autoload.php';

$filters = [
    'DAT_EDICAO'    => null, //Informar SIM para que a pesquisa retorne a DATA da última edição cadastrada
    'EDICAO'        => null, //Informar SIM para que a pesquisa retorne a descrição da última edição cadastrada

    'C1_ID'         => null, //C1_ID, C2_ID, C3_ID, C4_ID, C5_ID Preencher neste parâmetro o código do Tipo de
    'C2_ID'         => null, //Característica do Produto que deseja recuperar. Normalmente usada quando alguma
    'C3_ID'         => null, //informação importante do produto estiver definida como uma característica e não
    'C4_ID'         => null, //por um atributo comum.
    'C5_ID'         => null,

    'AUTORES_GERAIS'        => null, //Informar SIM para que a pesquisa retorne um texto simples com a relação de todos os autores envolvidos neste produto, separados por pipe ( | ). Exemplo: José da Silva | Manoel Cardoso | Henrique de Souza
    'AUTORES_GERAIS_TIPO'   => null, //Informar SIM para que a pesquisa retorne um texto simples com a relação de todos os autores envolvidos neste produto, separados por pipe ( | ). Além de vincular a classificação do Tipo do autor. Exemplo: Autor: José da Silva | Autor: Manoel Cardoso | Tradutor: Henrique de Souza
    'DESCONTO_PDV'          => null, //Preencher neste parâmetro o código usado no PDV do ERP(cod_pdv) onde foram configurados todos os descontos praticados na loja.
    'PE_EMPRESA'            => null, //PRAZO DE ENTREGA - Preencher com o código da empresa configurado o prazo de entrega padrão do Fornecedor que atende a Editora do produto. Necessário informar em conjunto o parâmetro PE_FILIAL
    'PE_FILIAL'             => null, //PRAZO DE ENTREGA - Preencher com o código da filial configurado o prazo de entrega padrão do Fornecedor que atende a Editora do produto. Necessário informar em conjunto o parâmetro PE_EMPRESA

    'OFFSET' => 1,
    'LIMIT' => 10,
    'SITUACAO_ITEM' => 'IN'
];

$products = (new Products(
    'http://seu_local/Horus/api/TServerB2B',
    'seu-usuario',
    'sua-senha'
))->search($filters);

if($products->error()){
    var_dump($products->error());
    die();
}

var_dump($products->response());