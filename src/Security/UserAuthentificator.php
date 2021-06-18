<?php

namespace App\Security;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserAuthentificator extends AbstractGuardAuthenticator
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request)
    {

        return $request->get("_route") === "api_login" && $request->isMethod("POST");
    }

    public function getCredentials(Request $request)
    {
        return [
            'email' => $request->request->get("email"),
            'password' => $request->request->get("password")
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials['email']);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'error' => $exception->getMessageKey()
        ], 400);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $user = $token->getUser();
      
        if($user->getDeactivate()==1){
            return new JsonResponse([
                'error' => 'Deactivate'
            ],400);
        }elseif($user->getRoles()[0]=='candidate'){
            return new JsonResponse([
                'error' => 'Candidate'
            ],400);
        }else{
            return new JsonResponse([
                'result' => [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'company' => $user->getCompany(),
                    'street' => $user->getStreet(),
                    'postcodeCity' => $user->getPostcodeCity(),
                    'phone' => $user->getPhone(),
                    'undertakerID' => $user->getUndertakerID(),
                    'surname' => $user->getSurname(),
                    'place' => $user->getPlace(),
                    'legalForm' => $user->getLegalForm(),
                    'email' => $user->getEmail(),
                    'request_date'=>$user->getRequestDate()
                ]
            ]);
        }
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse([
            'error' => 'Access Denied'
        ]);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}