<?php

namespace App\Controller;

use App\Entity\Category;
use App\Utils\CategoryTreeAdminList;
use Doctrine\Persistence\ManagerRegistry;
use App\Utils\CategoryTreeAdminOptionList;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_main")
     */
    public function index(): Response
    {
        return $this->render('admin/my_profile.html.twig');
    }
    /**
     * @Route("/categories", name="categories")
     */
    public function categories(CategoryTreeAdminList $categories)
    {
        $categories->getCategoryList($categories->buildTree());
        // dump($categories);
        return $this->render('admin/categories.html.twig', [
            'categories' => $categories->categorylist
        ]);
    }
    /**
     * @Route("/videos", name="videos")
     */
    public function videos(): Response
    {
        return $this->render('admin/videos.html.twig');
    }
    /**
     * @Route("/upload_video", name="upload_video")
     */
    public function uploadvideo(): Response
    {
        return $this->render('admin/upload_video.html.twig');
    }
    /**
     * @Route("/users", name="users")
     */
    public function users(): Response
    {
        return $this->render('admin/users.html.twig');
    }
    /**
     * @Route("/edit_category/{id}", name="edit_category")
     */
    public function editcategory(Category $category): Response
    {
        return $this->render('admin/edit_category.html.twig', [
            'category' => $category

        ]);
    }
    /**
     * @Route("/delete-category/{id}", name="delete_category")
     */
    public function deleteCategory(Category $category, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($category);
        $entityManager->flush();
        return $this->redirectToRoute('categories');
    }
    public function getAllCategories(CategoryTreeAdminOptionList $categories, $editCategory = null)
    {
        $categories->getCategoryList($categories->buildTree());
        return $this->render('admin/_all_categories.html.twig', [
            'categories' => $categories,
            'editCategory' => $editCategory
        ]);
    }
}
