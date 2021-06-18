<?php

namespace App\Controller;
use sngrl\PhpFirebaseCloudMessaging\Client;
use sngrl\PhpFirebaseCloudMessaging\Message;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Device;
use sngrl\PhpFirebaseCloudMessaging\Notification;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Messaging\CloudMessage;

use App\Entity\Instructions;
use App\Entity\Policy;
use App\Entity\Terms;
use App\Repository\DevicesRepository;
use App\Repository\InstructionsRepository;
use App\Repository\PolicyRepository;
use App\Repository\TermsRepository;
use App\Repository\TermsRepository as TermsRepositoryAlias;
use App\Repository\UserRepository;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Kreait\Firebase\Messaging;

class UserController extends AbstractController
{

    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    /**
     * @Route("/admin-send-email-all-app-users", name="admin-send-email-all-app-users", methods={"POST"})
     * @param Request $request
     * @param Messaging $messaging
     * @param DevicesRepository $repository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Spatie\PdfToImage\Exceptions\PdfDoesNotExist
     * @throws \Spatie\PdfToImage\Exceptions\InvalidFormat
     * @throws TransportExceptionInterface
     */
    public function adminsendemailallAppusers(Request $request,DevicesRepository $repository)
    {

        $sub = $request->request->get("emailSubject");
        $text = $request->request->get("emailBody");
        $users =$repository->getAllToken();
        $result = array();
        foreach ($users as $object) {
            $result[] = $object['tokenDevice'];
            $server_key = 'AAAApXYBjzo:APA91bE2jmbgxfiO8uGUtNnTlgpiS4wY-5IK2QBFpAT4WhzhsHvFpPgnPloR6dybbwAmnaY57Ceoq_bWJJfWNExPzlhL1RXJc_dnb6nxEclNRnA_tKBA5ufjQsekvmbMZE0dNTZKYSby';
            $client = new Client();
            $client->setApiKey($server_key);

            $message = new Message();
            $message->setPriority('high');
            $message->addRecipient(new Device($object['tokenDevice']));
            $message
                ->setNotification(new Notification($sub,$text))
                ->setData(['key' => 'value'])
            ;

            $response = $client->send($message);
        }

        return $this->json(
            "ok"
        );

    }


    /**
     * @Route("/admin-send-email-all-web-users", name="admin-send-email-all-web-users", methods={"POST"})
     * @param Request $request
     * @param UserRepository $repository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Spatie\PdfToImage\Exceptions\PdfDoesNotExist
     * @throws \Spatie\PdfToImage\Exceptions\InvalidFormat
     * @throws TransportExceptionInterface
     */
    public function adminsendemailallwebusers(Request $request,UserRepository $repository)
    {
        $ans='';
        $sub = $request->request->get("emailSubject");
        $text = $request->request->get("emailBody");
        $users =$repository->getUsersEmail();

        $result = array();
        foreach ($users as $object) {
            $result[] = $object['email'];

        }

        $result=implode(",", $result);

        $from = "luctio@Outlook.de";
        $to=$result;
        $subject="$sub";
        $message=$text;
        $headers="From:".$from;
        mail($to, $subject, $message, $headers);
        $ans='ok';



        return $this->json(
            $ans
        );

    }
    /**
     * @Route("/contact-message", name="contact-message", methods={"POST"})
     * @param Request $request
     * @param UserRepository $repository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Spatie\PdfToImage\Exceptions\PdfDoesNotExist
     * @throws \Spatie\PdfToImage\Exceptions\InvalidFormat
     * @throws TransportExceptionInterface
     */
    public function sendEmail(Request $request)
    {
        $name = $request->request->get("name");
        $email = $request->request->get("email");
        $text = $request->request->get("message");
        $from = "luctio@Outlook.de";
        $to="gbcoding999@gmail.com";
        $subject="Luctio Contact from ".$email." ".$name."";
        $message=$text;
        $headers="From:".$from;
        mail($to, $subject, $message, $headers);
        return $this->json(
            'true'
        );

    }

/////write-to-support  - form data - post(userId, bodyMessage)
//
    /**
     * @Route("/write-to-support", name="write-to-support", methods={"POST"})
     * @param Request $request
     * @param UserRepository $repository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Spatie\PdfToImage\Exceptions\PdfDoesNotExist
     * @throws \Spatie\PdfToImage\Exceptions\InvalidFormat
     * @throws TransportExceptionInterface
     */
    public function sendEmailSup(Request $request,UserRepository $repository)
    {
        $ans='';
        $sub = $request->request->get("userId");
        $text = $request->request->get("bodyMessage");

        $from = "luctio@Outlook.de";
        $to="gbcoding999@gmail.com";
        $subject="Luctio Support from ".$sub."";
        $message=$text;
        $headers="From:".$from;
        mail($to, $subject, $message, $headers);
        $ans='ok';



        return $this->json(
            $ans
        );

    }
    /**
     * @Route("/login", name="api_login", methods={"POST"})
     */
    public function login()
    {

    }

    /**
     * @Route("/change-user-password", name="/change-user-password", methods={"POST"})
     * @param Request $request
     * @param UserRepository $repository
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateUserPass(Request $request, UserRepository $repository,UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $id = $request->request->get("userId");
        $pass =$passwordEncoder->encodePassword($user, $request->request->get("newPassword"));
        $status = $repository->updateUserPass($id, $pass);
        if ($status == 1) {
            $return = 'ok';
        } else {
            $return = 'not ok';
        }
        return $this->json(
            $return
        );
    }
    /**
     * @Route("/change-user-email", name="change-user-email", methods={"POST"})
     * @param Request $request
     * @param UserRepository $repository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateUserEmail(Request $request, UserRepository $repository)
    {
        $id = $request->request->get("userId");
        $email = $request->request->get("newEmail");
        $status = $repository->updateUserEmail($id, $email);
        if ($status == 1) {
            $return = 'ok';
        } else {
            $return = 'not ok';
        }
        return $this->json(
            $return
        );
    }

    /**
     * @Route("/edit-user", name="edit-user", methods={"POST"})
     * @param Request $request
     * @param UserRepository $repository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateUser(Request $request, UserRepository $repository)
    {
        $id = $request->request->get("id");
        $email = $request->request->get("email");
        $name = $request->request->get("name");
        $surname = $request->request->get("surname");
        $legalForm = $request->request->get("legalForm");
        $place = $request->request->get("place");
        $company = $request->request->get("company");
        $street = $request->request->get("street");
        $postcodeCity = $request->request->get("postcodeCity");
        $phone = $request->request->get("phone");
        $undertakerID = $request->request->get("undertakerID");
        $repository->updateUser($id, $email, $name, $surname, $legalForm, $place, $company, $street, $postcodeCity, $phone, $undertakerID);

        return $this->json(
            'ok'
        );
    }


    /**
     * @Route("/get-candidates", name="get-candidates", methods={"GET"})
     * @param Request $request
     * @param UserRepository $repository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function getCandidates(Request $request, UserRepository $repository)
    {

        $allAnnouncement = $repository->findAllUserCan();
        $result = array();
        foreach ($allAnnouncement as $object) {
            $result[] = (array)$object;
        }


        return $this->json(
            $result
        );

    }

    /**
     * @Route("/unlock-candidate", name="unlock-candidate", methods={"GET"})
     * @param Request $request
     * @param UserRepository $repository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function unlockCandidate(Request $request, UserRepository $repository,Swift_Mailer $mailer)
    {
        $id = $request->query->get("candidate_id");
        $email = $request->query->get("email");
        if ($id) {
            $repository->updateRole($id);

            $from = "luctio@Outlook.de";
            $to=$email;
            $subject="Luctio";
            $message="Sehr geehrte Damen und Herren,Ihr Zugang für unser Traueranzeigen-Portal (http:www.luct.io) wurde freigeschaltet und Sie können nun Traueranzeigen auf unserer Plattform veröffentlichen.Mit freundlichen Grüßen.Ihr Luctio-Team";
            $headers="From:".$from;
            mail($to, $subject, $message, $headers);
            return $this->json(
                'ok'
            );

        }
        return;
    }

    /**
     * @Route("/deny-candidate", name="deny-candidate", methods={"POST"})
     * @param Request $request
     * @param UserRepository $repository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function denyCandidate(Request $request, UserRepository $repository)
    {
        $email = $request->request->get("candidate_id");
        $body = $request->request->get("bodyMessage");
        $from = "luctio@Outlook.de";
        $to=$email;
        $subject="Luctio";
        $message="$body";
        $headers="From:".$from;
        mail($to, $subject, $message, $headers);
        if ($email) {
            $repository->denyCandidate($email);
            return $this->json(
                'ok'
            );

        }
        return;
    }

    /**
     * @Route("/registration", name="api_registration", methods={"POST"})
     * @param EntityManagerInterface $om
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function register(EntityManagerInterface $om, UserPasswordEncoderInterface $passwordEncoder, Request $request, \Swift_Mailer $mailer)
    {
        $user = new User();
        $pass= md5(uniqid(rand(),true));
        $encodedPassword = $passwordEncoder->encodePassword($user, $pass);
        $email = $request->request->get("email");
        $name = $request->request->get("name");
        $surname = $request->request->get("surname");
        $legalForm = $request->request->get("legalForm");
        $place = $request->request->get("place");
        $company = $request->request->get("company");
        $street = $request->request->get("street");
        $postcodeCity = $request->request->get("postcodeCity");
        $phone = $request->request->get("phone");
        $undertakerID = $request->request->get("undertakerID");
        $registerData = date('Y-m-d');
        $errors = [];

        if (!$email) {
            $errors[] = 'Email field is empty';
        }
        if (!$name) {
            $errors[] = 'Name field is empty';
        }
        if (!$surname) {
            $errors[] = 'Surname field is empty';
        }
        if (!$company) {
            $errors[] = 'Company field is empty';
        }
        if (!$legalForm) {
            $errors[] = 'LegalForm field is empty';
        }
        if (!$place) {
            $errors[] = 'Place field is empty';
        }
        if (!$street) {
            $errors[] = 'Street field is empty';
        }
        if (!$postcodeCity) {
            $errors[] = 'PostcodeCity field is empty';
        }
        if (!$phone) {
            $errors[] = 'Phone field is empty';
        }
        if (!$undertakerID) {
            $errors[] = 'UndertakerID field is empty';
        }
        if (!$errors) {

            $user->setEmail($email);
            $user->setName($name);
            $user->setSurname($surname);
            $user->setLegalForm($legalForm);
            $user->setPlace($place);
            $user->setUndertakerID($undertakerID);
            $user->setStreet($street);
            $user->setPostcodeCity($postcodeCity);
            $user->setCompany($company);
            $user->setPhone($phone);
            $user->setPassword($encodedPassword);
            $user->setDeactivate(0);
            $user->setRequestDate($registerData);
            $user->setRegisterDate($registerData);
            $user->setRoles('candidate');
            try {
                $om->persist($user);
                $om->flush();


                $from = "luctio@Outlook.de";
                $to=$email;
                $subject="Luctio";
                $message="Sehr geehrte Damen und Herren,vielen Dank das Sie sich bei uns im Portal registriert haben.
                          Die Freischaltung Ihres Accounts dauert in der Regel 1 – 3 Werktage. Sobald Ihr Zugang überprüft und freigeschaltet wurde, erhalten Sie von uns eine Bestätigungsmail.
                          Mit freundlichen Grüßen.anbei finden Sie Ihr Passwort : $pass
                          
                          Ihr Luctio-Team
                          ";
                $headers="From:".$from;
                mail($to, $subject, $message, $headers);
                return $this->json([
                    'massage' => 'User successful added'
                ]);
            } catch (UniqueConstraintViolationException $e) {
                $errors[] = "The email provided already has an account!";
            } catch (\Exception $e) {
                $errors[] = "Unable to save new user at this time.";
            }
        }
        return $this->json([
            'message' => $errors
        ], 404);
    }

    /**
     * @Route("/profile", name="api_profile")
     */
    public function profile()
    {
        return $this->json([
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/userForPhone", name="usertForPhone")
     * @param Request $request
     * @param UserRepository $repository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function profileForPhone(UserRepository $repository, Request $request)
    {
        $id = $request->query->get("id");
        $OneUser = $repository->findOneUser($id);
        $result = array();
        foreach ($OneUser as $object) {
            $result[] = (array)$object;
        }
        return $this->json(
            $result
        );
    }

    /**
     * @Route("/get-users", name="get-users",methods={"GET"})
     * @param Request $request
     * @param UserRepository $repository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getUsers(UserRepository $repository, Request $request)
    {
        $filter = $request->query->get("filter");
        if (!$filter) {
            $OneUser = $repository->findUsers();
            $result = array();
            foreach ($OneUser as $object) {
                $result[] = (array)$object;
            }
        } elseif ($filter == 'deactive') {
            $OneUser = $repository->findDeactiveUsers();
            $result = array();
            foreach ($OneUser as $object) {
                $result[] = (array)$object;
            }
        } elseif ($filter == 'active') {
            $OneUser = $repository->findActiveUsers();
            $result = array();
            foreach ($OneUser as $object) {
                $result[] = (array)$object;
            }
        }
        return $this->json(
            $result
        );
    }

    /**
     * @Route("/check-is-admin", name="check-is-admin")
     * @param Request $request
     * @param UserRepository $repository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function checkIsAdmin(UserRepository $repository, Request $request)
    {
        $id = $request->query->get("user_id");

        $status = $repository->checkIsAdmin($id);
        if (!$status) {
            $result = false;
        } else {
            $result = true;
        }

        return $this->json(
            $result
        );
    }

    /**
     * @Route("/deactivate-user", name="deactivate-user")
     * @param Request $request
     * @param UserRepository $repository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deactivateUser(UserRepository $repository, Request $request,\Swift_Mailer $mailer)
    {
        $status = true;
        $id = $request->query->get("userId");
        $email= $request->query->get("email");
        $repository->deactivateUser($id, $status);

        $from = "luctio@Outlook.de";
        $to=$email;
        $subject="Luctio";
        $message="Sehr geehrte Damen und Herren,Ihr Zugang zu unserem Traueranzeigen-Portal (http:www.luct.io) wurde deaktiviert.
                  Falls ein Irrtum vorliegt bitten wir Sie den Support zu kontaktieren.Mit freundlichen Grüßen.
                  
                  Ihr Luctio-Team";
        $headers="From:".$from;
        mail($to, $subject, $message, $headers);
        return $this->json(
            'ok'
        );
    }

    /**
     * @Route("/reactivate-user", name="reactivate-user")
     * @param Request $request
     * @param UserRepository $repository
     */
    public function reactivateUser(UserRepository $repository, Request $request)
    {
        $status = false;
        $id = $request->query->get("userId");
        $repository->deactivateUser($id, $status);
        return $this->json(
            'ok'
        );
    }

    /**
     * @Route("/delete-user", name="delete-user", methods={"GET"})
     * @param Request $request
     * @param UserRepository $repository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function deleteUser(Request $request, UserRepository $repository)
    {
        $email = $request->query->get("userId");
        if ($email) {
            $repository->denyCandidate($email);
            return $this->json(
                'ok'
            );
        }
        return $this->json(
            '', 400
        );
    }

    /**
     * @Route("/upload-terms-of-use ", name="upload-terms-of-use ", methods={"POST"})
     * @param Request $request
     * @param TermsRepository $repository
     * @param EntityManagerInterface $om
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function uploadTermsOfUse(EntityManagerInterface $om,Request $request,TermsRepository $repository)
    {
        $user = new Terms();
        $pdf_name = uniqid() . '.pdf';
        move_uploaded_file($_FILES['filePDF']['tmp_name'], '../public/terms/' . $pdf_name);
        $user->setName($pdf_name);
        $om->persist($user);
        $om->flush();
        return $this->json(
            $pdf_name

        );
    }

    /**
     * @Route("/upload-privacy-policy", name="/upload-privacy-policy ", methods={"POST"})
     * @param Request $request
     * @param PolicyRepository $repository
     * @param EntityManagerInterface $om
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function uploadPrivacyPolicy(EntityManagerInterface $om,Request $request,PolicyRepository $repository)
    {
        $user = new Policy();
        $pdf_name = uniqid() . '.pdf';
        move_uploaded_file($_FILES['filePDF']['tmp_name'], '../public/privacyPolicy/' . $pdf_name);
        $user->setName($pdf_name);
        $om->persist($user);
        $om->flush();
        return $this->json(
            $pdf_name
        );
    }
    /**
     * @Route("/get-privacy-policy", name="/get-privacy-policy ", methods={"POST"})
     * @param Request $request
     * @param PolicyRepository $repository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getPrivacyPolicy(Request $request,PolicyRepository $repository)
    {
        $name=$repository->findPolicy();
        $result = array();
        foreach ($name as $object) {
            $result[] = (array)$object;
        }
        return $this->json(
            $result
        );
    }

    /**
     * @Route("/get-terms-of-use", name="get-terms-of-use ", methods={"POST"})
     * @param Request $request
     * @param TermsRepository $repository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getTermsOfUse(Request $request,TermsRepository $repository)
    {

        $name=$repository->findTerms();
        $result = array();
        foreach ($name as $obj) {
            $result[] = (array)$obj;
        }
        return $this->json(
            $result
        );
    }

    /**
     * @Route("/get-instructions", name="get-instructions", methods={"POST"})
     * @param Request $request
     * @param InstructionsRepository $repository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getInstructions(Request $request,InstructionsRepository $repository)
    {

        $name=$repository->findInstructions();
        $result = array();
        foreach ($name as $obj) {
            $result[] = (array)$obj;
        }
        return $this->json(
            $result
        );
    }
    /**
     * @Route("/upload-instructions", name="upload-instructions", methods={"POST"})
     * @param Request $request
     * @param InstructionsRepository $repository
     * @param EntityManagerInterface $om
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function uploadInstructions(EntityManagerInterface $om,Request $request,InstructionsRepository $repository)
    {
        $user = new Instructions();
        $pdf_name = uniqid() . '.pdf';
        move_uploaded_file($_FILES['filePDF']['tmp_name'], '../public/instructions/' . $pdf_name);
        $user->setName($pdf_name);
        $om->persist($user);
        $om->flush();
        return $this->json(
            $pdf_name
        );
    }

    /**
     * @Route("/forgot_passw", name="forgot_passw", methods={"GET"})
     * @param Request $request
     * @param Swift_Mailer $mailer
     * @param UserRepository $repository
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Spatie\PdfToImage\Exceptions\PdfDoesNotExist
     * @throws \Spatie\PdfToImage\Exceptions\InvalidFormat
     * @throws TransportExceptionInterface
     */
    public function forgotPassw(Request $request,UserPasswordEncoderInterface $passwordEncoder,UserRepository $repository)
    {
        $user = new User();
        $pass= md5(uniqid(rand(),true));
        $encodedPassword = $passwordEncoder->encodePassword($user, $pass);
        $email = $request->query->get("email");
        $status = $repository->fogotUserPass($email,$encodedPassword);
        if ($status == 1) {
            $from = "luctio@Outlook.de";
            $to=$email;
            $subject="Luctio Forgot password from";
            $message="Anbei finden Sie Ihr neues Passwort:".$pass." ";
            $headers="From:".$from;
            mail($to, $subject, $message, $headers);
            $return = 'ok';
        } else {
            $return = 'not ok';
        }
        return $this->json(
            $return
        );


    }
}
