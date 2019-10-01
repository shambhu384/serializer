<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Photo;

/**
* Simplified for our purposes. In real application it would use some filesystem abstraction, router, etc.
*/
class PhotoService
{
   public function getPhotoUrl(?Photo $photo): string
   {
       if (null === $photo) {
           return 'default.png';
       }
      echo "done";
       return $photo->getFilename();
   }
}
