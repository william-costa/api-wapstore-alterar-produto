<?php
/**
 * Função responsável por enviar requisições para a API da Wapstore 2.0
 * @method send
 * @param  string $method   Método HTTP (GET,POST)
 * @param  string $resource Recuso a ser consumido. Ex.: 'produto/consultar'
 * @param  array  $params   Parâmetros da URL. Ex.: ['sku'=>'4657']
 * @param  array  $request  Dados do corpo da requisição
 * @return array
 */
function send($method,$resource,$params = [],$request = []){
  //MONTAGEM DO ENDPOINT COMPLETO = URL BASE + RECURSO + PARAMETROS
  $endpoint = URLBASE.'/'.ltrim($resource,'/').'?';
  if(is_array($params) and !empty($params)){
    foreach($params as $key=>$value){
      $endpoint .= $key.'='.$value.'&';
    }
  }
  $endpoint .= 'rand='.rand(11111,9999);

  //CONFIGURAÇÃO INICIAL DO cURL
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_VERBOSE, true);
  curl_setopt($curl, CURLOPT_HEADER, true);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

  //HEADERS BÁSICOS DA REQUISIÇÃO
  $headers = [
    "Accept: application/json",
    "Cache-Control: no-cache",
    "Content-Type: application/json"
  ];

  if(DEBUG) echo '<b>'.$method.'</b> '.$endpoint.'<br><br>';

  //CONFIGURAÇÃO ESPECIFICA DO cURL PARA OS MÉTODOS GET E POST
  switch ($method) {
    case 'GET':
      curl_setopt($curl, CURLOPT_URL, $endpoint);
      curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    break;
    case 'POST':
      $request = json_encode($request, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
      $request = preg_replace("/\"(\d+\.\d{2})\"/","$1",$request);
      if(DEBUG) echo '[REQUEST] '.$request.'<br><br>';
      $headers[] = 'Content-Length: ' . strlen($request);
      curl_setopt($curl, CURLOPT_URL, $endpoint);
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
      curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    break;
  }


  //TRATAMENTO DO RETORNO
  $response           = curl_exec($curl);
  $info               = curl_getinfo($curl);
  $headerSize         = $info['header_size'];
  $tempoRequisicao    = $info['total_time'];
  $responseHeader     = substr($response, 0, $headerSize);
  $body               = substr($response, $headerSize);

  if(DEBUG) echo '[RESPONSE] '.$body.'<br><br><hr>';

  curl_close($curl);

  return json_decode($body,true);
}
