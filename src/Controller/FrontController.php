<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Video;
use App\Form\UserType;
use App\Entity\Category;
use App\Entity\Comment;
use App\Repository\VideoRepository;
use App\Utils\CategoryTreeFrontPage;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="main_page")
     */
    public function index()
    {
        return $this->render('front/index.html.twig');
    }

    /**
     * @Route("/video-list/category/{categoryname},{id}/{}page",defaults={"page":"1"}, name="video_list")
     */
    public function videoList($id, $page, CategoryTreeFrontPage $categories, ManagerRegistry $doctrine)
    {
        $categories->getCategoryListAndParent($id);
        // $ids = $categories->getChildIds($id);
        // array_push($ids, $id);
        $videos = $doctrine->getRepository(Video::class)
            ->findAll();

        // dump($categories);
        return $this->render('front/video_list.html.twig', [
            'subcategories' => $categories,
            'videos' => $videos,
        ]);
    }

    /**
     * @Route("/video-details/{video}", name="video_details")
     */
    public function videoDetails($video, VideoRepository $repo)
    {
        dump($repo->videoDetails($video));
        return $this->render(
            'front/video_details.html.twig',
            [
                'video' => $repo->videoDetails($video),
            ]
        );
    }

    /**
     * @Route("/search-results", methods={"POST"}, name="search_results")
     */
    public function searchResults()
    {
        return $this->render('front/search_results.html.twig');
    }

    /**
     * @Route("/pricing", name="pricing")
     */
    public function pricing()
    {
        return $this->render('front/pricing.html.twig');
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $password_encoder)
    {
        $entityManager = $doctrine->getManager();

        $user = new User;
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $user->setName($request->request->get('user')['name']);
            $user->setLastName($request->request->get('user')['lastName']);
            $user->setEmail($request->request->get('user')['email']);
            $password = $password_encoder->hashPassword(
                $user,
                $request->request->get('user')['password']['first']
            );
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);
            $this->loginUserAutomatically($user, $password);
            $entityManager->persist($user);
            $entityManager->flush();
            // return $this->redirectToRoute('admin_main');
        }

        return $this->render('front/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
    private function loginUserAutomatically($user, $password)
    {
        $token = new UsernamePasswordToken(
            $user,
            $password,
            'main',
            $user->getRoles()
        );
        $this->get('security.token_storage')->setToken($token);
        $this->get('session')->set('_security_main', serialize($token));
        return $this->redirectToRoute('admin_main');
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $helper)
    {
        return $this->render('front/login.html.twig', [
            'error' => $helper->getLastAuthenticationError()
        ]);
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        throw new \Exception('This should never be reached!');
    }

    /**
     * @Route("/payment", name="payment")
     */
    public function payment()
    {
        return $this->render('front/payment.html.twig');
    }
    /**
     * @Route("/new-comment/{video}", methods={"POST"}, name="new_comment")
     */
    public function newComment(Video $video, Request $request, ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        if (!empty(trim($request->request->get('comment')))) {

            // $video = $this->getDoctrine()->getRepository(Video::class)->find($video_id);

            $comment = new Comment();
            $comment->setContent($request->request->get('comment'));
            $comment->setUser($this->getUser());
            $comment->setVideo($video);

            $em = $doctrine->getManager();
            $em->persist($comment);
            $em->flush();
        }

        return $this->redirectToRoute('video_details', ['video' => $video->getId()]);
    }
    public function mainCategories(ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $categories = $entityManager->getRepository(Category::class)->findBy(['parent' => null], ['name' => 'ASC']);
        return $this->render('front/_main_categories.html.twig', [
            'categories' => $categories

        ]);
    }
}
