<?php


namespace App\Entity\Traits;


use Doctrine\ORM\Mapping as ORM;

trait NameTrait
{
    /**
     * @var string
     * @ORM\Column()
     */
    private $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
