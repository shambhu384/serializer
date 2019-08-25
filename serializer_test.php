<?php

require 'vendor/autoload.php';

use App\Model\Person;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;



$defaultContext = [
   AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
       return $object->getPid();
   },
   AbstractNormalizer::CIRCULAR_REFERENCE_LIMIT => 1,
];


$normalizers = [
   new GetSetMethodNormalizer(null, null, null, null, null, $defaultContext),
];
$encoders = [
   new XmlEncoder(),
   new JsonEncoder(),
];

$person = new Person('1', 'scott', 'dev@dev.org', new DateTime('2014-02-21'));

/* A circular reference has been detected when serializing the object of class "App\Model\Person" (configured limit: 1) */

$person->addFriend(new Person('1', 'Shambhu', 'shambhu@dev.org', new DateTime('2014-02-23')));

$serializer = new Serializer($normalizers, $encoders);
$serialized = $serializer->serialize($person, 'json');
echo $serialized;
