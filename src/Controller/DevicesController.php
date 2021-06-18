<?php
namespace App\Controller;
use App\Entity\Devices;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DevicesRepository;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Apple\ApnPush\Model\Notification;
use Apple\ApnPush\Model\DeviceToken;
use Apple\ApnPush\Model\Receiver;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging;

class DevicesController extends AbstractController
{
    /**
     * @Route("/add_device", name="add_device",methods={"GET"})
     * @param Request $request
     * @param DevicesRepository $repository
     * @param EntityManagerInterface $om
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addDevice(Request $request, DevicesRepository $repository, EntityManagerInterface $om)
    {
        $device = new Devices();
        $token = $request->query->get("token_device");
        $setting = $request->query->get("setting");
        $type=$request->query->get("type");
        $device->setTokenDevice($token);
        $device->setSetting($setting);
        $device->setType($type);
        $om->persist($device);
        $om->flush();
        return $this->json([
            'message' => 'done!',
        ]);
    }
    /**
     * @Route("/get_setting", name="/get_setting",methods={"GET"})
     * @param Request $request
     * @param DevicesRepository $repository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getSetting(Request $request, DevicesRepository $repository)
    {

        $token = $request->query->get("token_device");
        $setting = $request->query->get("setting");
        $data=$repository->getSetting($token,$setting);
        return $this->json([
            'message' => $data,
        ]);
    }

    /**
     * @Route("/send_aplle", name="/send_aplle",methods={"GET"})
     * @param Request $request
     * @param DevicesRepository $repository
     * @param Messaging $messaging
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function sendAndroid(Request $request, DevicesRepository $repository, Messaging $messaging)
    {

        $token = $request->query->get("token_device");
        $setting = $request->query->get("setting");


        $message = CloudMessage::fromArray([
            'token' => $token,
            'notification' => [/* Notification data as array */], // optional
            'data' => [/* data array */], // optional
        ]);

        try {
            $messaging->send($message);
        } catch (MessagingException $e) {
        } catch (FirebaseException $e) {
        }

        return $this->json([
            'message' => ''
        ]);
    }


}