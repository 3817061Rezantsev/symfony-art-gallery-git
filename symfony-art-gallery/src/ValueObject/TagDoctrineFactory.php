<?php

namespace Sergo\ArtGallery\ValueObject;

use Doctrine\ORM\EntityManagerInterface;
use Sergo\ArtGallery\Entity\TagFactoryInterface;

class TagDoctrineFactory implements TagFactoryInterface
{
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create(string $name): Tag
    {
        $tag = $this->em->getRepository(Tag::class)->findOneBy(['name' => $name]);
        if ($tag === null) {
            $this->add($name);
        }
        return $tag;
    }

    public function add(string $name): void
    {
        $tag = new Tag($name);
        $this->em->persist($tag);
        // $this->em->flush();
    }
}
