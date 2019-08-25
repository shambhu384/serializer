<?php
declare(strict_types=1);

namespace App\Model;

class Photo
{
   /**
    * @var string
    */
   private $pid;
   /**
    * @var int
    */
   private $width;
   /**
    * @var int
    */
   private $height;
   /**
    * @var string
    */
   private $filename;

   public function __construct(int $width, int $height, string $filename)
   {
       $this->pid = uniqid();
       $this->width = $width;
       $this->height = $height;
       $this->filename = $filename;
   }

   public function getPid(): string
   {
       return $this->pid;
   }

   public function getWidth(): int
   {
       return $this->width;
   }

   public function getHeight(): int
   {
       return $this->height;
   }

   public function getFilename(): string
   {
       return $this->filename;
   }
}

