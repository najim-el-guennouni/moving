<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $this->loadMainCategoriesData($manager);
        $this->loadELectronicsData($manager);
    }

    private function loadMainCategoriesData($manager)
    {
        foreach ($this->getMainCategoriesData() as [$name]) {
            $category = new Category();
            $category->setName($name);
            $manager->persist($category);
        }
        $manager->flush();
    }
    private function loadELectronicsData($manager)
    {
        foreach ($this->getMainCategoriesData() as [$name]) {
            $category = new Category();
            $category->setName($name);
            $manager->persist($category);
        }
        $manager->flush();
    }
    }


    private function getMainCategoriesData()
    {
        return [
            ['ELectronics', 1],
            ['Toys', 2],
            ['Books', 3],
            ['Movies', 4]
        ];
    }
    private function getELectronicsData()
    {
        return [
            ['Cameras', 5],
            ['Computers', 6],
            ['cell phone', 7]
        ];
    }
}
