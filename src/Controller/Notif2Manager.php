<?php


namespace App\Controller;


use App\Repository\AnnouncementRepository;
use App\Repository\DevicesRepository;
use sngrl\PhpFirebaseCloudMessaging\Client;
use sngrl\PhpFirebaseCloudMessaging\Message;
use sngrl\PhpFirebaseCloudMessaging\Notification;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Device;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Notif2Manager extends Command
{
    private $repository;
    private $repositoryAn;
    protected function configure()
    {
        $this
            ->setName('notif3:send')
            ->setDescription('Send notif.');
    }

    public function __construct(DevicesRepository $repository,AnnouncementRepository $repositoryAn)
    {
        $this->repository = $repository;
        $this->repositoryAn = $repositoryAn;
        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output

     * @return int|void
     */
    public function execute(InputInterface $input,OutputInterface $output){


        $users =$this->repository->getAllTokenDay();
        $ann= $this->repositoryAn->findPublichWeek();

        $result = array();
        foreach ($users as $object) {
            $result[] = $object['tokenDevice'];
            $server_key = 'AAAApXYBjzo:APA91bE2jmbgxfiO8uGUtNnTlgpiS4wY-5IK2QBFpAT4WhzhsHvFpPgnPloR6dybbwAmnaY57Ceoq_bWJJfWNExPzlhL1RXJc_dnb6nxEclNRnA_tKBA5ufjQsekvmbMZE0dNTZKYSby';
            $client = new Client();
            $client->setApiKey($server_key);


            $message = new Message();
            $message->setPriority('high');
            $message->addRecipient(new Device($object['tokenDevice']));
            foreach ($ann as $object1) {

                $message
                    ->setNotification(new Notification($object1->name.' '.$object1->surname,'Anzeige wurde hochgeladen'))
                    ->setData(['key' => 'value'])
                ;

                $response = $client->send($message);
            }
        }
    }

}