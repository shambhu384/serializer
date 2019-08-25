<?php

require 'vendor/autoload.php';

use App\Model\Person;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

$normalizers = [
   new GetSetMethodNormalizer(),
];
$encoders = [
   new XmlEncoder(),
   new JsonEncoder(),
];

$person = new Person('1', 'scott', 'dev@dev.org', new DateTime('2014-02-21'));

$serializer = new Serializer($normalizers, $encoders);
$serialized = $serializer->serialize($person, 'json');
echo $serialized;
