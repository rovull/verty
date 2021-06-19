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
     * @Route("/get-black-metal-inf", name="getblackmetalinf",methods={"GET"})
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
     * @Route("/save-black-metal-inf", name="saveblackmetalinf",methods={"POST"})
     * @param Request $request
     * @param MetallRepository $repository
     * @param EntityManagerInterface $om
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addDevi(Request $request, MetallRepository $repository, EntityManagerInterface $om)
    {

        $data=$request->request->get('data');
        $data=json_decode($data);

        foreach ($data as $rov) {
            $device = new Metall();
            $device->setName($rov->name);
            $device->setPrice($rov->price);
            $device->setVeith($rov->veith);
        $om->persist($device);
        $om->flush();
        }

        return $this->json([
            'message' => 'done!',
        ]);
    }

    /**
     * @Route("/dell-black", name="getblac",methods={"GET"})
     * @param Request $request
     * @param MetallRepository $repository
     * @param EntityManagerInterface $om
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getDell(Request $request, MetallRepository $repository, EntityManagerInterface $om)
    {


        $device = $repository->deleteAll();

        return $this->json([$device,
            'message' => 'done!',
        ]);
    }

}