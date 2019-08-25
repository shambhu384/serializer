<?php

require 'vendor/autoload.php';

use App\Model\Person;
use App\Model\Photo;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use App\Serializer\PersonNormalizer;
use App\Service\PhotoService;


$defaultContext = [
   AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
       return $object->getPid();
   },
   AbstractNormalizer::CIRCULAR_REFERENCE_LIMIT => 1,
];


$getSetMethodNormalizer = new GetSetMethodNormalizer(null, null, null, null, null, $defaultContext);

$normalizers = [
    new DateTimeNormalizer,
    new PersonNormalizer($getSetMethodNormalizer, new PhotoService()),
    $getSetMethodNormalizer
];
$encoders = [
   new XmlEncoder(),
   new JsonEncoder(),
];

// let's faker libraray
$faker = Faker\Factory::create();

$person = new Person($faker->uuid, $faker->name, $faker->email, $faker->dateTime);
$person->setPhoto(new Photo(640, 480, $faker->imageUrl));

$friend1 =  new Person($faker->uuid, $faker->name, $faker->email, $faker->dateTime);
$friend1->setPhoto(new Photo(640, 480, $faker->imageUrl));
$person->addFriend($friend1);

$friend2 =  new Person($faker->uuid, $faker->name, $faker->email, $faker->dateTime);
$friend2->setPhoto(new Photo(640, 480, $faker->imageUrl));

$friend3 =  new Person($faker->uuid, $faker->name, $faker->email, $faker->dateTime);
$friend3->setPhoto(new Photo(640, 480, $faker->imageUrl));
$friend3->addFriend($friend2);
$person->addFriend($friend3);


$serializer = new Serializer($normalizers, $encoders);
$serialized = $serializer->serialize($person, 'json');
echo $serialized;
