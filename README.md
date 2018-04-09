# Alteração de produto

Exemplo básico da alteração de produto via API na plataforma Wapstore, quando se tem apenas o SKU e o novo nome do produto.

A API de produtos da Wapstore exige alguns campos obrigatórios, e como nesse exemplo temos apenas o nome o SKU, precisaremos buscar as informações do produto em `/produto/consultar`, ajustar os dados e executar a alteração em `/produto/alterar`.

O arquivo *send.php* é responsável pelo envio das requisições para a API da Wapstore.

O arquivo *alterar-produto.php* possui três constantes que devem ser alteradas de acordo com os dados da plataforma Wapstore:

```php
 define('TOKEN','abcdefghijklmnopq12312'); //CHAVE PÚBLICA DA API
 define('PASS','abcdefghijklmnopq12312');  //CHAVE PRIVADA DA API
 define('URLBASE','https://www.wapstore.com.br/api/v1/'.TOKEN.'/json'); //ALTERE APENAS O DOMÍNIO
```

Para obter as credenciais (TOKEN e PASS) basta acessar o painel da Wapstore, navegar até `configurações > integrações > API` e criar uma nova integração.
