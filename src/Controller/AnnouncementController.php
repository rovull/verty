<?php

namespace App\Controller;

use App\Entity\Announcement;
use App\Repository\AnnouncementRepository;
use App\Repository\DevicesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Mpdf\Mpdf;
use sngrl\PhpFirebaseCloudMessaging\Client;
use sngrl\PhpFirebaseCloudMessaging\Message;
use sngrl\PhpFirebaseCloudMessaging\Notification;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Device;
use Spatie\PdfToImage\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

use Xthiago\PDFVersionConverter\Converter\GhostscriptConverter;
use Xthiago\PDFVersionConverter\Converter\GhostscriptConverterCommand;
use Xthiago\PDFVersionConverter\Guesser\RegexGuesser;

class AnnouncementController extends AbstractController
{
    /**
     * @Route("/announcement", name="announcement")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/AnnouncementController.php',
        ]);
    }

    /**
     * @Route("/edit_announcement", name="edit_announcement", methods={"POST"})
     * @param AnnouncementRepository $repository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Spatie\PdfToImage\Exceptions\PdfDoesNotExist
     * @throws \Spatie\PdfToImage\Exceptions\InvalidFormat
     * @throws \Exception
     */
    public function updateAnnouncement(AnnouncementRepository $repository, Request $request)
    {
        $id = $request->request->get("id");
        $appeal = $request->request->get("appeal");
        $title = $request->request->get("title");
        $name = $request->request->get("name");
        $surname = $request->request->get("surname");
        $releaseDate = $request->request->get("releaseDate");
        $birthDate = $request->request->get("birthDate");
        $deathDate = $request->request->get("deathDate");
        $cemeteryAddress = $request->request->get("cemeteryAddress");
        $emailAddresses = $request->request->get("emailAddresses");
        $pdf_file = $request->files->get("pdfFile");
        $city = $request->request->get("cemeteryCity");
        $ceremonyDate = $request->request->get("ceremonyDate");
        $ceremonyTime = $request->request->get("ceremonyTime");
        $previewImage = $request->request->get("previewImage");
        $textToSpeech = $request->request->get('textToSpeech');
        if (!$pdf_file and !$previewImage) {
            $repository->updateAnnouncementwithour($id, $ceremonyDate, $appeal, $title, $name, $surname, $releaseDate, $birthDate, $deathDate, $cemeteryAddress, $emailAddresses, $city, $ceremonyTime, $textToSpeech);
        } else {
            $pdf_name = uniqid() . '.pdf';
            $pdf_file = '../public/PdfFile/' . $pdf_name;
            move_uploaded_file($_FILES['pdfFile']['tmp_name'], $pdf_file);
            $pdf_file = str_replace('..', '', $pdf_file);
            define('UPLOAD_DIR', '../public/PNGFile/');
            $img = $previewImage;
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = UPLOAD_DIR . uniqid() . '.png';
            $success = file_put_contents($file, $data);
            $previewImage = str_replace('..', '', $file);
            $repository->updateAnnouncement($id, $textToSpeech, $ceremonyDate, $previewImage, $appeal, $title, $name, $surname, $releaseDate, $birthDate, $deathDate, $cemeteryAddress, $emailAddresses, $pdf_file, $city, $ceremonyTime);
        }

        $update = $repository->findById($id);

        return $this->json(
            $update
        );

    }


    /**
     * @Route("/publish_announcement", name="publish_announcement", methods={"POST"})
     * @param EntityManagerInterface $om
     * @param Request $request
     * @param $mailer
     * @param DevicesRepository $repository
     * @param AnnouncementRepository $repositoryAn
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function addAnnouncement(EntityManagerInterface $om, Request $request, DevicesRepository $repository, \Swift_Mailer $mailer)
    {
        $announcement = new Announcement();
        $appeal = $request->request->get("appeal");
        $title = $request->request->get("title");
        $name = $request->request->get("name");
        $surname = $request->request->get("surname");
        $namesurname = $name . ' ' . $surname;
        $releaseDate = $request->request->get("releaseDate");
        $birthDate = $request->request->get("birthDate");
        $deathDate = $request->request->get("deathDate");
        $cemeteryAddress = $request->request->get("cemeteryAddress");
        $emailAddresses = $request->request->get("emailAddresses");
        $pdf_file = $request->files->get("pdfFile");
        $user_id = $request->request->get("user_id");
        $city = $request->request->get("cemeteryCity");
        $ceremony_date = $request->request->get("ceremonyDate");
        $previewImage = $request->request->get('previewImage');
        $ceremony_time = $request->request->get('ceremonyTime');
        $textToSpeech = $request->request->get('textToSpeech');

        define('UPLOAD_DIR', '../public/PNGFile/');
        $img = $previewImage;
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $file = UPLOAD_DIR . uniqid() . '.png';
        $success = file_put_contents($file, $data);
        $pngPath = str_replace('..', '', $file);

        $name1 = '../public/PdfFile/' . uniqid() . '.pdf';
        move_uploaded_file($_FILES['pdfFile']['tmp_name'], $name1);
        $pdfPath = str_replace('..', '', $name1);

        $errors = [];
        if (!$appeal) {
            $errors = 'Appeal field is empty';
        }
        if (!$name) {
            $errors = 'Name field is empty';
        }
        if (!$surname) {
            $errors = 'Surname field is empty';
        }
        if (!$releaseDate) {
            $errors = 'ReleaseDate field is empty';
        }
        if (!$birthDate) {
            $errors = 'BirthDate field is empty';
        }
        if (!$deathDate) {
            $errors = 'DeathDate field is empty';
        }
        if (!$errors) {

            $releaseDate = date("Y-m-d", strtotime($releaseDate));
            $birthDate = date("Y-m-d", strtotime($birthDate));
            $deathDate = date("Y-m-d", strtotime($deathDate));
            $value = date('Y-m-d');
            if ($value == $releaseDate) {
                $users = $repository->getAllTokenNow();
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
                        ->setNotification(new Notification($name . ' ' . $surname, 'Anzeige wurde hochgeladen'))
                        ->setData(['key' => 'value']);

                    $response = $client->send($message);

                }
            }
            $announcement->setAppeal($appeal);
            $announcement->setTitle($title);
            $announcement->setName($name);
            $announcement->setSurname($surname);
            $announcement->setReleaseDate($releaseDate);
            $announcement->setBirthDate($birthDate);
            $announcement->setDeathDate($deathDate);
            $announcement->setCemeteryAddress($cemeteryAddress);
            $announcement->setEmailAddresses($emailAddresses);
            $announcement->setPdfFile($pdfPath);
            $announcement->setUserID($user_id);
            $announcement->setPicture($pngPath);
            $announcement->setStatus(1);
            $announcement->setTextToSpeak($textToSpeech);
            $announcement->setCity($city);
            $announcement->setCeremonyDate($ceremony_date);
            $announcement->setCeremonyTime($ceremony_time);
            $om->persist($announcement);
            $om->flush();
            if ($emailAddresses != '') {

                $from = "luctio@Outlook.de";
                $to = $emailAddresses;
                $subject = "Luctio";
                $message = "Sehr geehrte Damen und Herren,die Traueranzeige von $name $surname wurde in unserem Portal freigeschaltet.
                          Unser tiefstes Beileid.
                
                          Ihr Luctio-Team";
                $headers = "From:" . $from;
                mail($to, $subject, $message, $headers);
            }


            return $this->json([
                'massage' => 'Announcement successful added'
            ], 200);
        }
        return $this->json([
            'errors' => $errors
        ], 404);

    }

    /**
     * @Route("/all_announcement", name="all_announcement", methods={"GET"})
     * @param AnnouncementRepository $repository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Spatie\PdfToImage\Exceptions\PdfDoesNotExist
     * @throws \Spatie\PdfToImage\Exceptions\InvalidFormat
     */
    public function allAnnouncement(AnnouncementRepository $repository)
    {       $result='top';
//        $appeal2 = date('Y-m-d');
//        $allAnnouncement = $repository->findAllpubl($appeal2);
//        $result = array();
//        foreach ($allAnnouncement as $object) {
//            $result[] = (array)$object;
//        }
        return $this->json(
            $result
        );

    }

    /**
     * @Route("/get-announcement", name="get-announcement", methods={"GET"})
     * @param AnnouncementRepository $repository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function allAnnouncementById(AnnouncementRepository $repository, Request $request)
    {

        $appeal = $request->query->get("user_id");
        $appeal1 = $request->query->get("year");
        $allAnnouncement = $repository->findAllById($appeal, $appeal1);
        $result = array();
        foreach ($allAnnouncement as $object) {
            $result[] = (array)$object;
        }


        return $this->json(
            $result
        );

    }

    /**
     * @Route("/get-bill-announcement-by-user", name="get-bill-announcement-by-user", methods={"GET"})
     * @param AnnouncementRepository $repository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function allAnnouncementBillByUser(AnnouncementRepository $repository, Request $request)
    {

        $appeal = $request->query->get("user_id");
        $appeal1 = $request->query->get("bill_date");
        $appeal1 = date('Y-m-d', strtotime($appeal1));
        $allAnnouncement = $repository->findBillById($appeal, $appeal1);
        $result = array();
        foreach ($allAnnouncement as $object) {
            $result[] = (array)$object;
        }


        return $this->json(
            $result
        );

    }

    /**
     * @Route("/get-future-announcement", name="get-future-announcement", methods={"GET"})
     * @param AnnouncementRepository $repository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function allFutureAnnouncementById(AnnouncementRepository $repository, Request $request)
    {
        $appeal = $request->query->get("user_id");

        $allAnnouncement = $repository->findFuture($appeal);

        $result = array();
        foreach ($allAnnouncement as $object) {
            $result[] = (array)$object;
        }


        return $this->json(
            $result
        );

    }

    /**
     * @Route("/get-years-obituaries", name="get-years-obituaries", methods={"GET"})
     * @param AnnouncementRepository $repository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function allPublichAnnouncementById(AnnouncementRepository $repository, Request $request)
    {
        $appeal = $request->query->get("user_id");
        $allAnnouncement = $repository->findPublich($appeal);
        $result = array();
        foreach ($allAnnouncement as $object) {
            $result[] = (array)$object;
        }


        return $this->json(
            $result
        );

    }

    /**
     * @Route("/get-all-years-announcement-admin", name="get-all-years-announcement-admin", methods={"GET"})
     * @param AnnouncementRepository $repository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getAllYearsAnnouncementAdmin(AnnouncementRepository $repository)
    {
        $allAnnouncement = $repository->getAllYearsAnnouncementAdmin();
        $result = array();
        foreach ($allAnnouncement as $object) {
            $result[] = (array)$object;
        }
        return $this->json(
            $result
        );

    }

    /**
     * @Route("/get-all-announcement-admin", name="/get-all-announcement-admin", methods={"GET"})
     * @param AnnouncementRepository $repository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getAllAnnouncementAdmin(AnnouncementRepository $repository, Request $request)
    {
        $appeal = $request->query->get("year");
        $allAnnouncement = $repository->getAllAnnouncementAdmin($appeal);

        return $this->json(
            $allAnnouncement
        );

    }

    /**
     * @Route("/deactivate-announcement-admin", name="deactivate-announcement-admin", methods={"GET"})
     * @param AnnouncementRepository $repository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deactivateAnnouncement(AnnouncementRepository $repository, Request $request)
    {
        $appeal = $request->query->get("id");

        $repository->deactivateAnnouncement($appeal);
        return $this->json(
            'ok'
        );

    }

    /**
     * @Route("/deactivate-announcement", name="deactivate-announcement", methods={"GET"})
     * @param AnnouncementRepository $repository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deactivateUserAnnouncement(AnnouncementRepository $repository, Request $request)
    {
        $appeal = $request->query->get("id");

        $repository->deactivateAnnouncement($appeal);
        return $this->json(
            'ok'
        );

    }

    /**
     * @Route("/reactivate-announcement-admin", name="reactivate-announcement-admin", methods={"GET"})
     * @param AnnouncementRepository $repository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function activateAnnouncement(AnnouncementRepository $repository, Request $request)
    {
        $appeal = $request->query->get("id");
        $repository->activateAnnouncement($appeal);
        return $this->json(
            'ok'
        );

    }

    /**
     * @Route("/get-obituaries", name="get-obituaries", methods={"GET"})
     * @param AnnouncementRepository $repository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function allYearPublichAnnouncementById(AnnouncementRepository $repository, Request $request)
    {
        $appeal = $request->query->get("user_id");
        $appeal1 = $request->query->get("year");
        $allAnnouncement = $repository->findYearPublich($appeal, $appeal1);
        $result = array();
        foreach ($allAnnouncement as $object) {
            $result[] = (array)$object;
        }


        return $this->json(
            $result
        );

    }

    /**
     * @Route("/delete-all-announcement-user", name="delete-all-announcement-user", methods={"GET"})
     * @param AnnouncementRepository $repository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteAllAnnouncementUser(AnnouncementRepository $repository, Request $request)
    {
        $appeal = $request->query->get("user_id");
        $repository->deleteUserAn($appeal);
        return $this->json(
            'ok'
        );

    }


    /**
     * @Route("/get-city", name="get-city", methods={"GET"})
     * @param AnnouncementRepository $repository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function allCity(AnnouncementRepository $repository)
    {
        $allAnnouncement = $repository->findCity();
        $result = array();
        foreach ($allAnnouncement as $object) {
            $result[] = (array)$object;
        }


        return $this->json(
            $result
        );

    }

    /**
     * @Route("/get-all-years-announcement-admin", name="get-all-years-announcement-admin", methods={"GET"})
     * @param AnnouncementRepository $repository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function allYears(AnnouncementRepository $repository)
    {
        $allAnnouncement = $repository->findYears();
        $result = array();
        foreach ($allAnnouncement as $object) {
            $result[] = (array)$object;
        }


        return $this->json(
            $result
        );

    }

    /**
     * @Route("/get-years-announcement", name="get-years-announcement", methods={"GET"})
     * @param AnnouncementRepository $repository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function allYearsById(AnnouncementRepository $repository, Request $request)
    {
        $appeal = $request->query->get("user_id");
        $allAnnouncement = $repository->findYearsById($appeal);
        $result = array();
        foreach ($allAnnouncement as $object) {
            $result[] = (array)$object;
        }

        return $this->json(
            $result
        );

    }

    /**
     * @Route("/get-filter", name="get-filter", methods={"GET"})
     * @param AnnouncementRepository $repository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function allFilter(AnnouncementRepository $repository, Request $request)
    {
        $appeal1 = $request->query->get("from");
        $appeal2 = $request->query->get("to");
        $appeal3 = $request->query->get("city");
        $appeal4 = $request->query->get("name_surname");
        $appeal5 = explode(" ", $appeal4);


        $result = [];
        if (!$appeal1 and !$appeal2 and !$appeal3 and !$appeal4) {
            $result[] = 'Filter Empty';

        } elseif (!$appeal1 and !$appeal2 and $appeal3 and !$appeal4) {
            $allAnnouncement = $repository->findFilterCity($appeal3);
            $result = array();
            foreach ($allAnnouncement as $object) {
                $result[] = (array)$object;
            }
        } elseif ($appeal1 and $appeal2 and !$appeal3 and !$appeal4) {
            $allAnnouncement = $repository->findFilterDate($appeal1, $appeal2);
            $result = array();
            foreach ($allAnnouncement as $object) {
                $result[] = (array)$object;
            }
        } elseif ($appeal1 and $appeal2 and $appeal3 and !$appeal4) {
            $allAnnouncement = $repository->findFilterDateCity($appeal1, $appeal2, $appeal3);
            foreach ($allAnnouncement as $object) {
                $result[] = (array)$object;
            }
        } elseif ($appeal1 and !$appeal2 and !$appeal3 and !$appeal4) {
            $appeal2 = date('Y-m-d');
            $allAnnouncement = $repository->findFilterDate($appeal1, $appeal2);
            foreach ($allAnnouncement as $object) {
                $result[] = (array)$object;
            }
        } elseif (!$appeal1 and $appeal2 and !$appeal3 and !$appeal4) {
            $appeal1 = date('Y-m-d', strtotime('1970-01-01'));
            $allAnnouncement = $repository->findFilterDate($appeal1, $appeal2);
            foreach ($allAnnouncement as $object) {
                $result[] = (array)$object;
            }
        } elseif (!$appeal1 and $appeal2 and $appeal3 and !$appeal4) {
            $appeal1 = date('Y-m-d', strtotime('1970-01-01'));
            $allAnnouncement = $repository->findFilterDateCity($appeal1, $appeal2, $appeal3);
            foreach ($allAnnouncement as $object) {
                $result[] = (array)$object;
            }
        } elseif ($appeal1 and !$appeal2 and $appeal3 and !$appeal4) {
            $appeal2 = date('Y-m-d');
            $allAnnouncement = $repository->findFilterDateCity($appeal1, $appeal2, $appeal3);
            foreach ($allAnnouncement as $object) {
                $result[] = (array)$object;
            }
            //dfsre
        } elseif (count($appeal5) > 1 and !$appeal1 and !$appeal2 and $appeal3 and $appeal4) {
            $allAnnouncement = $repository->findFilterCityName1($appeal3, $appeal4, $appeal5);
            foreach ($allAnnouncement as $object) {
                $result[] = (array)$object;
            }
        } elseif (!$appeal1 and !$appeal2 and $appeal3 and $appeal4) {
            $allAnnouncement = $repository->findFilterCityName($appeal3, $appeal4);
            foreach ($allAnnouncement as $object) {
                $result[] = (array)$object;
            }
        } elseif (count($appeal5) > 1 and $appeal1 and $appeal2 and !$appeal3 and $appeal4) {
            $allAnnouncement = $repository->findFilterDateName1($appeal1, $appeal2, $appeal4, $appeal5);
            foreach ($allAnnouncement as $object) {
                $result[] = (array)$object;
            }
        } elseif ($appeal1 and $appeal2 and !$appeal3 and $appeal4) {
            $allAnnouncement = $repository->findFilterDateName($appeal1, $appeal2, $appeal4);
            foreach ($allAnnouncement as $object) {
                $result[] = (array)$object;
            }
        } elseif (count($appeal5) > 1 and $appeal1 and $appeal2 and $appeal3 and $appeal4) {
            $allAnnouncement = $repository->findFilterDateCityName1($appeal1, $appeal2, $appeal3, $appeal4, $appeal5);
            foreach ($allAnnouncement as $object) {
                $result[] = (array)$object;
            }


        } elseif ($appeal1 and $appeal2 and $appeal3 and $appeal4) {
            $allAnnouncement = $repository->findFilterDateCityName($appeal1, $appeal2, $appeal3, $appeal4);
            foreach ($allAnnouncement as $object) {
                $result[] = (array)$object;
            }


        }
        elseif (count($appeal5) > 1 and !$appeal1 and !$appeal2 and !$appeal3 and $appeal4) {
            $allAnnouncement = $repository->findFilterName1($appeal4, $appeal5);
            foreach ($allAnnouncement as $object) {
                $result[] = (array)$object;
            }


        }elseif (!$appeal1 and !$appeal2 and !$appeal3 and $appeal4) {
            $allAnnouncement = $repository->findFilterName($appeal4);
            foreach ($allAnnouncement as $object) {
                $result[] = (array)$object;
            }


        }
        return $this->json(
            $result
        );
    }


    /**
     * @Route("/uploadPDF", name="/uploadPDF", methods={"POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Spatie\PdfToImage\Exceptions\InvalidFormat
     * @throws \Spatie\PdfToImage\Exceptions\PdfDoesNotExist
     */
    public function uploadPDF(Request $request)
    {
        $pdfFilePath = $request->files->get("filePDF");

        $path = $pdfFilePath->getPathname();
        // Create a TMP file of the image with PNG format
        $fileName = uniqid() . '.png';

        // Get the path of the temporal image
        $outputImagePath = tempnam(sys_get_temp_dir(), $fileName);
        // Create a PDF instance with the filepath of the PDF
        $pdf = new Pdf($path);
        // Set the format of image to the output file
        $pdf->setOutputFormat('png');
        // Generate image from PDF into the TMP file
        $pdf->saveImage($outputImagePath);
        $imagedata = file_get_contents($outputImagePath);

        $pdf = base64_encode($imagedata);
        // Return the TMP file image as response

        return $this->json([$pdf
            ]
        );

    }

    /**
     * @Route("/makeFromPdfTextToSpeech", name="/makeFromPdfTextToSpeech", methods={"POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Spatie\PdfToImage\Exceptions\InvalidFormat
     * @throws \Spatie\PdfToImage\Exceptions\PdfDoesNotExist
     * @throws \Exception
     */
    public function uploadPDFText(Request $request)
    {
        $name1 = '../public/PdfFile/' . uniqid() . '.pdf';
        move_uploaded_file($_FILES['filePDF']['tmp_name'], $name1);
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($name1);
        $text = $pdf->getText();
        $text = str_replace(array("\r\n", "\n", "\t"), ' ', $text);
        if ($text) {
            return $this->json($text

            );
        } else {
            return $this->json([
            ], 404);
        }

    }

}
