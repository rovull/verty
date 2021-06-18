<?php


namespace App\Controller;

use App\Entity\Invoices;
use App\Repository\AnnouncementRepository;
use App\Repository\InvoicesRepository;

use App\Repository\UserRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class InvoicesController extends AbstractController
{

    /**
     * @Route("/get-all-bills", name="get-all-bills", methods={"GET"})
     * @param InvoicesRepository $repo
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Spatie\PdfToImage\Exceptions\PdfDoesNotExist
     * @throws \Spatie\PdfToImage\Exceptions\InvalidFormat
     */
    public function allInvoices(InvoicesRepository $repo, Request $request)
    {

        $appeal = $request->query->get("filter");
        if ($appeal) {
            if ($appeal == 'paid') {
                $appeal = 1;
            } else {
                $appeal = 0;
            }
            $invoices = $repo->findAllFilter($appeal);
        } elseif (!$appeal) {
            $invoices = $repo->findAll();
        }

        $result = array();
        foreach ($invoices as $object) $result[] = (array)$object;
        return $this->json(
            $result
        );


    }

    /**
     * @Route("/get-all-invoices", name="get-all-invoices", methods={"GET"})
     * @param InvoicesRepository $repo
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Spatie\PdfToImage\Exceptions\PdfDoesNotExist
     * @throws \Spatie\PdfToImage\Exceptions\InvalidFormat
     */
    public function allInvoicesByUser(InvoicesRepository $repo, Request $request)
    {
        $appeal = $request->query->get("user_id");
        $invoices = $repo->findByUser($appeal);
        $result = array();
        foreach ($invoices as $object) $result[] = (array)$object;
        return $this->json(
            $result
        );

    }
    /**
     * @Route("/edit-bill-payment", name="edit-bill-payment", methods={"GET"})
     * @param InvoicesRepository $repo
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Spatie\PdfToImage\Exceptions\PdfDoesNotExist
     * @throws \Spatie\PdfToImage\Exceptions\InvalidFormat
     */
    public function editBillPayment(InvoicesRepository $repo, Request $request)
    {
        $appeal = $request->query->get("bill_id");
        $appeal1 = $request->query->get("paid");
        if($appeal1=='true'){
            $appeal1=1;
        }else{
            $appeal1=0;
        }
        $repo->editBillPayment($appeal,$appeal1);

        return $this->json(
            'true'
        );

    }


    /**
     * @Route("/edit-bill-sum", name="edit-bill-sum", methods={"GET"})
     * @param InvoicesRepository $repo
     * @param Request $request
     * @param AnnouncementRepository $repositoryAn
     * @param UserRepository $repositoryUs
     * @param Options
     * @return Response
     * @throws \Spatie\PdfToImage\Exceptions\PdfDoesNotExist
     * @throws \Spatie\PdfToImage\Exceptions\InvalidFormat
     */
    public function updatePrice(InvoicesRepository $repo, Request $request, AnnouncementRepository $repositoryAn, UserRepository $repositoryUs)
    {
        $appeal = $request->query->get("bill_id");
        $appeal2 = $request->query->get("sum");
        if ($appeal and $appeal2) {
            $repo->updatePrice($appeal, $appeal2);
            $NewBill = $repo->findById($appeal);

            $result = array();
            foreach ($NewBill as $object) {
                $result[] = $object;

            }

            $ann = $repositoryAn->findPublichMonUp($result[0]->userId, $result[0]->requestDate);
            $user = $repositoryUs->findOneUser($result[0]->userId);


            $dompdf = new Dompdf();

            $sumpd = (count($ann) * 10) * 0.19;
            $sum = count($ann) * 10 + $sumpd;
            $listAn = '';
            $count = 390;

            $path = '../public/logo.png';
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

            foreach ($ann as $an) {

                $ti=date('d-m-Y',strtotime($an->releaseDate) );


                $listAn .= '

<div style = "position:absolute;left:5px;top:' . $count . 'px" class="cls_010" ><span class="cls_010999999" >' . $an->name . ' ' . $an->surname . ' </span ></div >
<div style = "position:absolute;left:500px;top:' . $count . 'px" class="cls_010" ><span class="cls_010999999" > ' . $ti . '</span ></div >
<div style = "position:absolute;left:664px;top:' . $count . 'px" class="cls_004" ><span class="cls_0109999991" >10 EUR</span ></div >
                ';

                $count += 17;
            }

            $html =
                '
<style type="text / css">
<!--
span.cls_002{font-family:Times,serif;font-size:25px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
span.cls_0025553434345{font-family:Times,serif;font-size:18px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
div.cls_002{font-family:Times,serif;font-size:18.1px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
span.cls_003{font-family:Times,serif;font-size:12.1px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
div.cls_003{font-family:Times,serif;font-size:12.1px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
span.cls_004{font-family:Times,serif;font-size:18px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
span.cls_01155555{font-family:Times,serif;font-size:21px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
span.cls_0044444{font-family:Times,serif;font-size:17px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
span.cls_01011111111111111{font-family:Times,serif;font-size:17px;color:rgb(0,0,0);font-weight:normal;font-style:italic;text-decoration: none}
span.cls_00466666{font-family:Times,serif;font-size:15px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
span.cls_0089999999{font-family:Times,serif;font-size:16px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
span.cls_008999999911{font-family:Times,serif;font-size:17px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
span.cls_0089999999112{font-family:Times,serif;font-size:17px;color:rgb(0,0,0);font-weight:bold;font-style:italic;text-decoration: none}
span.cls_00899999991123{font-family:Times,serif;font-size:17px;color:rgb(0,0,0);font-weight:normal;font-style:italic;text-decoration: none}
span.cls_010999999{font-family:Times,serif;font-size:16px;color:rgb(0,0,0);font-weight:normal;font-style:italic;text-decoration: none}
span.cls_0109999991{font-family:Times,serif;font-size:16px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
span.cls_01099999912{font-family:Times,serif;font-size:16px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
div.cls_004{font-family:Times,serif;font-size:10.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
span.cls_005{font-family:Times,serif;font-size:30.1px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
div.cls_005{font-family:Times,serif;font-size:30.1px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
span.cls_006{font-family:Times,serif;font-size:9.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
div.cls_006{font-family:Times,serif;font-size:9.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
span.cls_007{font-family:Times,serif;font-size:12px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: underline}
div.cls_007{font-family:Times,serif;font-size:7.0px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
span.cls_008{font-family:Times,serif;font-size:11.1px;color:rgb(0,0,0);font-weight:bold;font-style:italic;text-decoration: none}
div.cls_008{font-family:Times,serif;font-size:11.1px;color:rgb(0,0,0);font-weight:bold;font-style:italic;text-decoration: none}
span.cls_009{font-family:Times,serif;font-size:11.1px;color:rgb(0,0,0);font-weight:normal;font-style:italic;text-decoration: none}
div.cls_009{font-family:Times,serif;font-size:11.1px;color:rgb(0,0,0);font-weight:normal;font-style:italic;text-decoration: none}
span.cls_010{font-family:Times,serif;font-size:10.1px;color:rgb(0,0,0);font-weight:normal;font-style:italic;text-decoration: none}
div.cls_010{font-family:Times,serif;font-size:10.1px;color:rgb(0,0,0);font-weight:normal;font-style:italic;text-decoration: none}
span.cls_012{font-family:Times,serif;font-size:7.0px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
div.cls_012{font-family:Times,serif;font-size:7.0px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
span.cls_013{font-family:Times,serif;font-size:7.0px;color:rgb(0,0,0);font-weight:normal;font-style:italic;text-decoration: none}
div.cls_013{font-family:Times,serif;font-size:7.0px;color:rgb(0,0,0);font-weight:normal;font-style:italic;text-decoration: none}
span.cls_011{font-family:Times,serif;font-size:15.1px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
div.cls_011{font-family:Times,serif;font-size:15.1px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
span.cls_014{font-family:Times,serif;font-size:10.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: underline}
div.cls_014{font-family:Times,serif;font-size:10.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
-->
</style>
<script type="text / javascript" src="wz_jsgraphics . js"></script>
</head>
<div style="width: 2000px">
<div style="position:relative;left:0;margin-left:0px;top:0px;width:2000px;height:1000px">
<div style="position:absolute;left:359px;top:0px" class="cls_002"><span class="cls_002">Digitale Traueranzeigen APP </span><span class="cls_0025553434345">GbR</span></div>
<div style="position:absolute;left:491px;top:32px" class="cls_004"><span class="cls_004">Wasserstr. 25 - 44803 Bochum</span></div>
<div style="position:absolute;left:0px;top:20px" class="cls_005"><span class="cls_005"><img src='.$base64.'/></span></div>
<div style="position:absolute;left:546px;top:50px" class="cls_004"><span class="cls_0044444">Mail:luctio@Outlook.de</span></div>
<div style="position:absolute;left:630px;top:65px" class="cls_006"><span class="cls_0044444"> <A HREF="http://www.luct.io/">www.luct.io</A></span> </div>
<div style = "position:absolute;left:0px;top:123.08px" class="cls_007" ><span class="cls_007" > Digitale Traueranzeigen APP GbR -Wasserstr. 25 - 44803 Bochum</span ></div >
<div style = "position:absolute;left:605px;top:140px" class="cls_004" ><span class="cls_00466666" > Bankverbindung:</span ></div >
<div style = "position:absolute;left:0px;top:160px" class="cls_003" ><span class="cls_008999999911" > Bestattungsinstitut</span ></div >
<div style = "position:absolute;left:595px;top:155px" class="cls_006" ><span class="cls_0089999999" > Sparkasse Bochum </span ></div >
<div style = "position:absolute;left:472px;top:174px" class="cls_006" ><span class="cls_0089999999" > IBAN: DE74 4305 0001 0042 4362 61 </span ></div >
<div style = "position:absolute;left:0px;top:178px" class="cls_008" ><span class="cls_0089999999112" >' . $user[0]->name . ' ' . $user[0]->surname . ' </span ></div >
<div style = "position:absolute;left:530px;top:194px" class="cls_006" ><span class="cls_0089999999" > Swift-Bic.: WELADED1B0C </span ></div >
<div style = "position:absolute;left:0px;top:198px" class="cls_00899999991123" ><span class="cls_00899999991123" >' . $user[0]->street . ' </span ></div >
<div style = "position:absolute;left:554px;top:225px" class="cls_0089999999" ><span class="cls_0089999999" > Finanzamt Bochum-Mitte </span ></div >
<div style = "position:absolute;left:0px;top:236px" class="cls_009" ><span class="cls_00899999991123" >' . $user[0]->postcodeCity . ' ' . $user[0]->place . ' </span ></div >
<div style = "position:absolute;left:545px;top:245px" class="cls_006" ><span class="cls_0089999999" > Ust.-IDNr.: DE 329558602 </span ></div >
<div style = "position:absolute;left:0px;top:290px" class="cls_004" ><span class="cls_004" > Rechnungs - Nr: </span ><span class="cls_00899999991123" > ' . $NewBill[0]->invoiceId . '</span ></div >
<div style = "position:absolute;left:565px;top:290px" class="cls_0044444" ><span class="cls_004" > Bochum, </span ><span class="cls_01011111111111111" > ' . date('d-m-Y') . ' </span ><span class="cls_012" > </span><span class="cls_013" > </span ></div >
<div style = "position:absolute;left:448.32px;top:250.76px" class="cls_013" ><span class="cls_013" > </span ></div >
<div style = "position:absolute;left:0px;top:340px" class="cls_011" ><span class="cls_01155555" > Rechnung</span ></div >
' . $listAn . '
<hr style="position:absolute;left:5px;top:'. ($count + 1) .'px; width: 711px; height: 1px; background-color: black ">
<div style = "position:absolute;left:570px;top:'. ($count + 12) .'px" class="cls_004" ><span class="cls_0109999991" > 19 % MwSt</span ></div >
<div style = "position:absolute;left:660px;top:'. ($count + 12) .'px" class="cls_014" ><span class="cls_0109999991" >' .str_replace(".", ",", floatval($sumpd))  . '0 &euro;</span ></div >
<hr style="position:absolute;left:571px;top:'. ($count + 22) .'px; width: 145px; background-color: black ">
<div style = "position:absolute;left:654px;top:'. ($count + 31) .'px" class="cls_004" ><span class="cls_01099999912" >' . str_replace(".", ",", floatval($appeal2)) . ',00 &euro;</span ></div >
<hr style="position:absolute;left:634px;top:'. ($count + 40) .'px; width: 83px; background-color: black ">
</div >
</div >
';
            $dompdf->loadHtml($html);

            // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
            $dompdf->setPaper('A4', 'portrait');

            // Render the HTML as PDF
            $dompdf->render();

            // Store PDF Binary Data
            $output = $dompdf->output();

            // In this case, we want to write the file in the public directory
//        $pdf_name = date('Y').'-'.date('m.d').'-'.$count. '.pdf';
            $pdfFilepath ='../'. $result[0]->pdf;

            file_put_contents($pdfFilepath, $output);

            // e.g /var/www/project/public/mypdf.pdf


            // Write file to the desired path

            // Create a TMP file of the image with PNG format

            // Get the path of the temporal image
            $outputImagePath = tempnam(sys_get_temp_dir(), $pdfFilepath);
            // Create a PDF instance with the filepath of the PDF
            $pdf = new \Spatie\PdfToImage\Pdf($pdfFilepath);
            // Set the format of image to the output file
            $pdf->setOutputFormat('png');
            // Generate image from PDF into the TMP file
            $pdf->saveImage($outputImagePath);
            $imagedata = file_get_contents($outputImagePath);

            $pdf = base64_encode($imagedata);

            $img = $pdf;
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = '../public/PNGFile/' . $NewBill[0]->invoiceId. '.pdf.png';
            $success = file_put_contents($file, $data);

            return $this->json(
                ['invoiceId' => $appeal, 'sum' => $appeal2]
            );

        }


    }
}
