<?php

require 'vendor/autoload.php';

use App\Model\Person;
use App\Model\Photo;
use App\Serializer\PersonNormalizer;
use App\Service\PhotoService;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

$defaultContext = [
   AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
       return $object->getPid();
   },
   AbstractNormalizer::CIRCULAR_REFERENCE_LIMIT => 1,
];

AnnotationRegistry::registerLoader('class_exists');

$classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

$getSetMethodNormalizer = new GetSetMethodNormalizer($classMetadataFactory, null, null, null, null, $defaultContext);

$normalizers = [
    new DateTimeNormalizer,
    new PersonNormalizer($getSetMethodNormalizer, new PhotoService()),
    new PropertyNormalizer, // addFriend checks and add friend
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
// public or private groups
//$serialized = $serializer->serialize($person, 'xml', ['groups' => 'public']);
//echo $serialized;

$serialized = <<<JSON
{
   "name": "Jane",
   "email": "jane@example.com",
   "birthDate": "1988-06-15T00:00:00+00:00",
    "friends": [
        {
        "pid": "0d95cddc-d32b-49b6-92e0-6d9569e188fs",
        "name": "Jane",
        "email": "jane@example.com",
        "birthDate": "1988-06-15T00:00:00+00:00"
        }
    ]
}
JSON;

$context = [
   AbstractNormalizer::DEFAULT_CONSTRUCTOR_ARGUMENTS => [
       Person::class => [
           'pid' => '0d95cddc-d32b-49b6-92e0-6d9569e188fa',
       ]
   ]
];

//$object = $serializer->deserialize($serialized, Person::class, 'json', $context);

// Needs property access component to set addFriend method


$serialized = <<<JSON
{
   "name": "Johnny",
   "email": "john-new-email@example.com"
}
JSON;
$context = [
    AbstractNormalizer::OBJECT_TO_POPULATE => $friend1
];


$object = $serializer->deserialize($serialized, Person::class, 'json', $context);

print_r($object);
