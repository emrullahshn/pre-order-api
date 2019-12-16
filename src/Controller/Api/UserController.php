<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Library\Utils;
use App\Service\Redis\CacheServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class DocumentController
 * @package App\Controller
 */
class UserController extends AbstractFOSRestController
{
    /**
     * @Rest\Post(path="/login", name="user_login")
     *
     *
     * @SWG\Tag(name="Login")
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Login",
     *     required=true,
     *     format="application/json",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="email", type="string", example="test@test.com", description="User email"),
     *         @SWG\Property(property="password", type="string", example="test", description="User password")
     *     )
     * )
     *
     * * @SWG\Response(
     *     response="200",
     *     description="Login success.",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(
     *              @SWG\Property(property="apiToken", type="string")
     *          )
     *      )
     * )
     *
     * @SWG\Response(
     *     response="404",
     *     description="User not found!",
     *     @SWG\Schema(
     *          type="object",
     *          properties={
     *              @SWG\Property(property="errorMessage", type="string")
     *          }
     *     )
     * )
     *
     * @SWG\Response(
     *     response="403",
     *     description="Check user credentials!",
     *     @SWG\Schema(
     *          type="object",
     *          properties={
     *              @SWG\Property(property="message", type="string")
     *          }
     *     )
     * )
     *
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param CacheServiceInterface $redisService
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     * @throws Exception
     */
    public function login(Request $request, EntityManagerInterface $entityManager, CacheServiceInterface $redisService, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $params = json_decode($request->getContent(), true);

        $email = $params['email'];
        $password = $params['password'];

        /**
         * @var User $user
         */
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user === null){
            $view = $this->view(['errorMessage' => 'User not found!'], 404);

            return $this->handleView($view);
        }

        $isPasswordValid = $passwordEncoder->isPasswordValid($user, $password);

        if ($isPasswordValid === false) {
            $view = $this->view(['errorMessage' => 'Check user credentials!'], 403);

            return $this->handleView($view);
        }

        $apiToken = Utils::generateToken();
        $redisService->set($apiToken, $user->getId());
        $entityManager->flush();

        $view = $this->view(['apiToken' => $apiToken], 200);

        return $this->handleView($view);
    }
}
