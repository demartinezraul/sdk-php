<?php

namespace Test\DictKey;
use \Exception;
use StarkBank\DictKey;

class TestDictKey
{
  public function get()
  {
    $pixKey = "tony@starkbank.com";
    $dictKey = DictKey::get($pixKey);
    
    if (is_null($dictKey->id) || $dictKey->id != $pixKey) {
      throw new Exception("failed");
    }    
  }

  public function query()
  {
    $payments = iterator_to_array(DictKey::query(["limit" => 1, "type" => "evp", "status" => "registered"]));

    if (count($payments) != 1) {
      throw new Exception("failed");
    }
    if (is_null($payments[0]->id)) {
      throw new Exception("failed");
    }
  }
}

echo "\n\nDictKey";

$test = new TestDictKey();

echo "\n\t - get";
$test->get();
echo " - OK";
