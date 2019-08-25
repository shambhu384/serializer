<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Model\Person;
use App\Service\PhotoService;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PersonNormalizer implements NormalizerInterface
{
    /**
     * @var GetSetMethodNormalizer
     */
    private $normalizer;
    /**
     * @var PhotoService
     */
    private $photoService;
    public function __construct(GetSetMethodNormalizer $normalizer, PhotoService $photoService)
    {
        $this->normalizer = $normalizer;
        $this->photoService = $photoService;
    }
    public function normalize($object, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        if (!is_array($data)) {
            return $data;
        }

        $data['photo'] = $this->photoService->getPhotoUrl($object->getPhoto());

        if (!is_array($context['groups'])) {
            $context['groups'] = [$context['groups']];
        }

        if (in_array('public', $context['groups'])) {
            $data['age'] = $this->calculateAge($object);
        }

        return $data;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Person;
    }

    private function calculateAge(Person $person): int
    {
        $today = new \DateTime();
        return $today->diff($person->getBirthDate())->y;
    }
}
