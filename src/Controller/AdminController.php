<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function categories(): Response
    {
        return $this->render('admin/categories.html.twig');
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
     * @Route("/edit_category", name="edit_category")
     */
    public function editcategory(): Response
    {
        return $this->render('admin/edit_category.html.twig');
    }
}
