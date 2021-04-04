<?php
// src/Command/ListChecklisteBirdCommand.php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\Lkpzoochar;
use App\Entity\Zoology;
use App\Entity\User;
use App\Entity\LkpzoospeciesAves;
use App\Entity\Lkppristupnost;
use App\Repository\LkpzoocharRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Validator\Constraints\DateTime;
use App\Repository\ZoologyRepository;

class ListChecklisteBirdCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'eBird:importdenhistoric';

    private $container;

    public function __construct(ContainerInterface $container)
    {

    		$this->container = $container;
    		parent::__construct();
    }
    protected function configure()
    {
      	$this->setDescription('Podľa aktuálneho dátumu importuje včerajšie dáta z eBird zo Slovenska v mene užívateľa eBird pomocou API historic observation on date a dáta si potiahne Aves');
        $this->addArgument('den',InputArgument::OPTIONAL, 'Ktorý deň spracovať? formát (YYYY-MM-DD)', '2006-01-01');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
     $cData                 = '';
     $aktCharakteristika = 'Z'; //todo doriesiť
     $checklistUzImportovany  = '';
     $em                 = $this->container->get('doctrine')->getManager();

     if($input->getArgument('den') <> '2006-01-01'):
       $spracujaktivityzaden = $input->getArgument('den'); 
       $spracujaktivityzadendt = new \DateTime($spracujaktivityzaden);
     else:
       //POZOR toto je default:    
       $spracujaktivityzaden = date('Y-m-d', strtotime("-2 days"));
       $spracujaktivityzadendt = new \DateTime($spracujaktivityzaden);
     endif;

     $rokmesden = explode("-",$spracujaktivityzaden);

     $aktRok    = $rokmesden[0];
     $aktMesiac = $rokmesden[1];
     $aktDen    = $rokmesden[2];
     $neimportovatDoAvesu = false;

     //ziskaj kody krajov
     $eBirdUrlkraje = 'https://api.ebird.org/v2/ref/region/list/subnational1/SK.json?key=82n4s8p912m2';
     $cAkraje       = curl_init($eBirdUrlkraje);
     curl_setopt($cAkraje, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($cAkraje, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json', 'Accept: application/json', 'X-eBirdApiToken: 82n4s8p912m2'
));
    $jsonkraje = curl_exec($cAkraje);
    curl_close($cAkraje);
    $aKraje = json_decode($jsonkraje, true);

    //test 
    //$output->writeln(var_dump($aKraje));
    
    $aPomKodyKrajov = array();
    $aPomKeysKraje = array_keys($aKraje);

    for($ik = 0; $ik < count($aKraje); $ik++ ){
     foreach($aKraje[$aPomKeysKraje[$ik]] as $key => $value ) {

       //test vsetko
       //$output->writeln($key.' : '.$value);

       if($key == 'code'):
         $aPomKodyKrajov[] = $value;
       endif;
     }
    }

    //dáta importujeme po krajoch, aby sme nedosiahli limit 200 zapisov a aby sme urcite videli v zozname vsetky zapisy
    foreach($aPomKodyKrajov as $keykraj => $krajkodvalue):

      $eBirdUrl = 'https://api.ebird.org/v2/data/obs/'.$krajkodvalue.'/historic/'.$aktRok.'/'.$aktMesiac.'/'.$aktDen.'?detail=simple&key=82n4s8p912m2&maxResults=200';


       $cA = curl_init($eBirdUrl);
       curl_setopt($cA, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($cA, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json', 'Accept: application/json', 'X-eBirdApiToken: 82n4s8p912m2'
));
      $json4 = curl_exec($cA);
      curl_close($cA);
      $output->writeln("získaj dáta predchádzajúci deň $spracujaktivityzaden kraj $krajkodvalue \n");

      $aData = json_decode($json4, true);

      $aPomKeys = array_keys($aData);
      for($i = 0; $i < count($aData); $i++ ){

        $output->writeln("Loop v kraji $krajkodvalue č $i, každý cyklus spracuje jeden konkrétny checklist");

        //definovania premennych a v dalsich cykloch ich vyprazdnenie, subId znovu definovať ako pole pre dalsieho poz 
        $subId = "";

          //napln dátami o jednom konkrétnom checkliste z jeho popisu zo zoznamu checklistov za den,  
          foreach($aData[$aPomKeys[$i]] as $key => $value ) {

          if($key == 'subId')           $subId     = $value;

        }
         //zo získaného popisu checklistu vypíš id číslo checklistu 
         $output->writeln("Hľadám Checklist $subId či je nový.");

         //kontrola na zabezpečenie preskočenia importo tohoto presneho checklistu
         $checklistUzImportovany = $em
                    ->getRepository(Zoology::class)
                    ->najdiChecklist($subId);
         if(count($checklistUzImportovany) > 0) continue; //prejdi na dalsi cyklus
         
         //zo získaného popisu checklistu vypíš id číslo checklistu 
         $output->writeln("Nový Checklist: $subId - ešte nebol importovaný.. importuj");
       

         $command = $this->getApplication()->find('eBird:importchecklist');
         $arguments = [
                  'checklist' => $subId
              ];
         $importInput = new ArrayInput($arguments); 
         $returnCode = $command->run($importInput, $output);
         
      } //for   $aData v určitom kraji

    endforeach;

       $output->writeln($spracujaktivityzaden.' akt rok: '.$aktRok.' akt mesiac: '.$aktMesiac.' akt den'.$aktDen);

       return Command::SUCCESS;
    }
}
?>
