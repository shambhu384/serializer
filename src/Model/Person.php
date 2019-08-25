<?php

declare(strict_types=1);

namespace App\Model;

use DateTimeInterface;
use Symfony\Component\Serializer\Annotation\Groups;

class Person
{
   /**
    * @var string
    *
    * @Groups({"public", "private"})
    */
   private $pid;
   /**
    * @var string
    *
    * @Groups({"public", "private"})
    */
   private $name;
   /**
    * @var string
    *
    * @Groups({"private"})
    */
   private $email;
   /**
    * @var DateTimeInterface
    *
    * @Groups({"private"})
    */
   private $birthDate;
   /**
    * @var Person[]
    *
    * @Groups({"public"})
    */
   private $friends;
   /**
    * @var Photo|null
    *
    * @Groups({"public", "private"})
    */
   private $photo;

   public function __construct(string $pid, string $name, string $email, DateTimeInterface $birthDate)
   {
       $this->pid = $pid;
       $this->name = $name;
       $this->email = $email;
       $this->birthDate = $birthDate;
       $this->friends = [];
   }

   public function addFriend(Person $friend): void
   {
       if (!in_array($friend, $this->friends)) {
           $this->friends[] = $friend;
           $friend->addFriend($this);
       }
   }

   public function getPid(): string
   {
       return $this->pid;
   }

   public function getName(): string
   {
       return $this->name;
   }

   public function getEmail(): string
   {
       return $this->email;
   }

   public function getBirthDate(): DateTimeInterface
   {
       return $this->birthDate;
   }

   /**
    * @return Person[]
    */
   public function getFriends(): array
   {
       return $this->friends;
   }

   public function getPhoto(): ?Photo
   {
       return $this->photo;
   }

   public function setPhoto(?Photo $photo): void
   {
       $this->photo = $photo;
   }
}

