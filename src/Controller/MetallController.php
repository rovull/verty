<?php
namespace App\Controller;

use App\Entity\Metall;
use App\Repository\MetallRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MetallController extends AbstractController
{
    /**
     * @Route("/get_black_metal_inf", name="getblackmetalinf",methods={"GET"})
     * @param Request $request
     * @param MetallRepository $repository
     * @param EntityManagerInterface $om
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getBlackMetalInf (Request $request, MetallRepository $repository, EntityManagerInterface $om)
    {


        $device = $repository->getAllBlack();

        return $this->json([$device,
            'message' => 'done!',
        ]);
    }
    /**
     * @Route("/save-black-metal-inf", name="save-black-metal-inf",methods={"GET"})
     * @param Request $request
     * @param MetallRepository $repository
     * @param EntityManagerInterface $om
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addDevice(Request $request, MetallRepository $repository, EntityManagerInterface $om)
    {
        dump($request);
        $device = new Metall();

//        $token = $request->query->get("token_device");
//
//        $setting = $request->query->get("setting");
//
//        $type=$request->query->get("type");
//
//        $device->setName($token);
//        $device->setPrice($setting);
//        $device->setVeith($type);
//        $om->persist($device);
//        $om->flush();
        return $this->json([
            'message' => 'done!',
        ]);
    }

}