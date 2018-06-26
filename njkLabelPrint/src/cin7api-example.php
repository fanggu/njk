<?php

$request = new HttpRequest();
$request->setUrl('https://api.cin7.com/api/v1/Products');
$request->setMethod(HTTP_METH_GET);

$request->setQueryData(array(
  'fields' => 'id,code,styleCode,ModifiedDate,name,ProductOptions',
  'where' => 'modifieddate%3E%272018-06-25T00%3A00%3A00Z%27',
  'order' => 'ASC',
  'page' => '2',
  'rows' => '250'
));

$request->setHeaders(array(
  'Cache-Control' => 'no-cache',
  'Authorization' => 'Basic bmprZ3JvdXBOWjo4N2I1MDczODllNTc0OGZmYjJjZGE1YzBlMmJlZGM1Ng=='
));

try {
  $response = $request->send();

  echo $response->getBody();
} catch (HttpException $ex) {
  echo $ex;
}
