<?php


namespace App\Controller;


use App\Entity\MetallColor;
use App\Repository\MetallColorRepository;
use App\Repository\MetallRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MetallColorController extends AbstractController
{
    /**
     * @Route("/get_color_metal_inf", name="get_color_metal_inf",methods={"GET"})
     * @param Request $request
     * @param MetallColorRepository $repository
     * @param EntityManagerInterface $om
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getColorMetalInf (Request $request, MetallColorRepository $repository, EntityManagerInterface $om)
    {


        $device = $repository->getAllColor();

        return $this->json([$device,
            'message' => 'done!',
        ]);
    }
    /**
     * @Route("/add_color", name="add_color",methods={"GET"})
     * @param Request $request
     * @param MetallColorRepository $repository
     * @param EntityManagerInterface $om
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addDevice(Request $request, MetallColorRepository $repository, EntityManagerInterface $om)
    {
        $device = new MetallColor();

        $token = $request->query->get("token_device");

        $setting = $request->query->get("setting");

        $type=$request->query->get("type");

        $device->setName($token);
        $device->setPrice($setting);
        $device->setVeith($type);
        $om->persist($device);
        $om->flush();
        return $this->json([
            'message' => 'done!',
        ]);
    }


}