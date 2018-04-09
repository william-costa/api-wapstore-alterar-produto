<?php

/**
 * Modelo de requisições de consulta e atualização de dados de um produto apenas com o SKU
 */

/**
 * Debug das requisições
 */
define('DEBUG',true);

/***************************************************************************/

/**
 * Credenciais disponíveis em WapStore > Configurações > Integrações > API
 * E URL base da API na loja
 */
 define('TOKEN','abcdefghijklmnopq12312'); //CHAVE PÚBLICA DA API
 define('PASS','abcdefghijklmnopq12312');  //CHAVE PRIVADA DA API
 define('URLBASE','https://www.wapstore.com.br/api/v1/'.TOKEN.'/json'); //ALTERE APENAS O DOMÍNIO

include __DIR__.'/send.php';

/***************************************************************************/


/**
 *  Exemplo utilizando o produto "1" com o sku "skuteste"
 *  O novo nome é uma concatenação com um número randomico para demonstrar que a atualização acontece corretamente
 */
$sku = 'skuteste';
$novoNome = "Parafusadeira à Bateria GSR 18-2-LI Plus Professional - Bosch ".rand(1000,9999);

//REQUISIÇÃO DE CONSUTLA DO PRODUTO COM O SKU DESEJADO
$produto = send('GET','/produto/consultar',['sku'=>$sku]);

//VALIDAÇÃO SE O PRODUTO RETORNOU CORRETAMENTE
if(isset($produto[0]['sku'])){
  $produto = $produto[0];

  //COMEÇANDO A REQUISIÇÃO DE ATUALIZAÇÃO E UTILIZANDO O JSON RECEBIDO COMO BASE
  $requestAtualizacao = $produto;
  $requestAtualizacao['pass'] = PASS;

  //ATRIBUTOS ÚNICOS
  if(isset($requestAtualizacao['atributos']['unico']) and !empty($requestAtualizacao['atributos']['unico'])){
    $atributos = $requestAtualizacao['atributos']['unico'];
    $requestAtualizacao['atributos'] = [];
    foreach($atributos as $key=>$value){
      $atributoValor = [];
      $atributoValor = $value['valores'][0]['id'];
      $requestAtualizacao['atributos']['unico'][$key] = $atributoValor;
    }
  }

  $requestAtualizacao['marca'] = $requestAtualizacao['marca']['id'];

  //LIMPEZA DOS DADOS QUE NÃO PODEM SER ENVIADOS NA ALTERAÇÃO
  $limpar = ['id','liberado','dataAtualizacao','produtosVinculados','avaliacao','filtros','caracteristicas','categoria','categoriasVinculadas','categoriasSecundarias','imagens','url','idProdutoLider','produtoLider'];
  foreach($limpar as $key){
    unset($requestAtualizacao[$key]);
  }

  //APLICANDO O NOVO NOME
  $requestAtualizacao['nome'] = $novoNome;

  //REQUISIÇÃO DE CONSUTLA DO PRODUTO COM O SKU DESEJADO
  $produto = send('POST','/produto/alterar',['sku'=>$sku],$requestAtualizacao);

  echo 'Finalizado';
}else{
  echo 'Produto não identificado';
}
