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
     * @Route("/get-color-metal-inf", name="get_color_metal_inf",methods={"GET"})
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
     * @Route("/save-color-metal-inf", name="save-color-metal-inf",methods={"POST"})
     * @param Request $request
     * @param MetallColorRepository $repository
     * @param EntityManagerInterface $om
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function saveСolor(Request $request, MetallColorRepository $repository, EntityManagerInterface $om)
    {

        $data=$request->request->get('data');
        $data=json_decode($data);
        $repository->dell();
        foreach ($data as $rov) {
            $device = new MetallColor();
            $device->setName($rov->kind);
            $device->setPrice($rov->price);
            $device->setVeith($rov->weight);
            $om->persist($device);
            $om->flush();
        }
        return $this->json([
            'message' => 'done!',
        ]);
    }


}