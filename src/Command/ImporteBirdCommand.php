<?php
// src/Command/ImporteBirdCommand.php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
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

class ImporteBirdCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'eBird:importden';

    private $container;

    public function __construct(ContainerInterface $container)
    {

    		$this->container = $container;
    		parent::__construct();
    }
    protected function configure()
    {
      	$this->setDescription('Podľa aktuálneho dátumu importuje včerajšie dáta z eBird zo Slovenska v mene užívateľa eBird a dáta si potiahne Aves');
        $this->addArgument('den',InputArgument::OPTIONAL, 'Ktorý deň spracovať? formát (YYYY-MM-DD)', '2006-01-01');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
	/* -- M_MV jul(?), august, sept, okt, nov, 
	-- ZIMOVANIE dec, jan, feb
	-- M_MV  marec,
	-- A0 april, maj, jun 
	A0   ma id = , lkpzoochar_comborder  = 1, id 19
	M_MV ma id = , lkpzoochar_comborder = 20, id 36 
	ZIM. ma id = , lkpzoochar_comborder = 32, id 70 

        */
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

      $eBirdUrl = 'https://api.ebird.org/v2/product/lists/'.$krajkodvalue.'/'.$aktRok.'/'.$aktMesiac.'/'.$aktDen.'?detail=full&key=82n4s8p912m2&maxResults=200';


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
        $subId = $numSpecies = $locName = $obsDatum = $latitude = $longitude = $obsTime = "";

          //napln dátami o jednom konkrétnom checkliste z jeho popisu zo zoznamu checklistov za den,  
          foreach($aData[$aPomKeys[$i]] as $key => $value ) {
          //vsetko
          //$output->writeln(var_dump($key));

          //naplnenie premennych hodnotami z eBird zaznamu, ktore treba pre Aves zaznam 
          /**/
          //if($key == 'userDisplayName') $userDisplayName = $value;
          if($key == 'numSpecies')      $numSpecies      = $value;
          if($key == 'loc'):
            $locName         = $value['name'];
            $latitude        = $value['latitude'];
            $longitude       = $value['longitude'];

          endif;
          if($key == 'obsDt')           $obsDatum     = $value;
          if($key == 'obsTime')         $obsTime   = $value;
          if($key == 'subId')           $subId     = $value;

        }
         //zo získaného popisu checklistu vypíš id číslo checklistu 
         $output->writeln("Checklist $subId (počet zistených druhov: $numSpecies) ");

         //kontrola na zabezpečenie preskočenia importo tohoto presneho checklistu
         $checklistUzImportovany = $em
                    ->getRepository(Zoology::class)
                    ->najdiChecklist($subId);
         if(count($checklistUzImportovany) > 0) continue; //prejdi na dalsi cyklus
         
        


          //ziskanie vsetkych druhov zo zaznamu "checklist"
 
           //detail checlistu
           //hlavičková časť poskytuje tieto informácie:
           //protocolId  ak = 22 kompletný zoznam, ak = P20 náhodné pozorovanie, popri inej činnosti
           //durationHrs desatinné číslo vyjadrujúci čas trvania pozorovania v hodinách, napr 1.4
           //effortDistanceKm desatinné číslo vyjadrujúce dĺžku prejdenej trasy
           //ak je vyplnené tak aj comments - poznámka k lokalite
           //
           //vnorené pole obs
           //key ako číslo jednotlivých druhových záznamov, čísluje od nuly
           //speciesCode 
           //howManyAtleast číselný počet najmenej
           //howManyAtmost číselne vyjadrený počet, najväčší počet
           //howManyStr reťazcom vyjadrený počet
           //ak je poznámka tak comments = poznámka k druhu
           //present false , ak nie je číslo ale X ako prítomný druh, v Avese na to máme len počet 1
           //
           //ak je vyplnená charakteristika tak ako vnorené pole
           //obsAux
           //auxCode reťazec, napríklad "FO"
           //value napr. "C1" todo zistiť viac o tomto
       
          $eBirdUrlchecklist = 'https://api.ebird.org/v2/product/checklist/view/'.$subId.'?key=82n4s8p912m2';
          $cAchecklist       = curl_init($eBirdUrlchecklist);
          curl_setopt($cAchecklist, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($cAchecklist, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json', 'Accept: application/json', 'X-eBirdApiToken: 82n4s8p912m2'
));
         $jsonchecklist = curl_exec($cAchecklist);
         curl_close($cAchecklist);
         $aChecklist = json_decode($jsonchecklist, true);


         //definovanie premenných a v dalsich cykloch ich vyprazdnenie
         $protocolId =  $groupId = $durationHrs= $durationMin = $effortDistanceKm = $commentsloc = $obsDt = $aPomobsDt = $userDisplayName = $allObsReported = $obsDtFrom = $obsDtTo = "";
         $aPomKeysChecklist = array_keys($aChecklist);
         for($ic = 0; $ic < count($aChecklist); $ic++ ){

                     //naplnenie premennych hodnotami z eBird zaznamu, ktore treba pre Aves zaznam 
           if($aPomKeysChecklist[$ic]  == 'protocolId') $protocolId = $aChecklist['protocolId'];
           if($aPomKeysChecklist[$ic]  == 'groupId')    $groupId    = $aChecklist['groupId'];

           if($aPomKeysChecklist[$ic]  == 'allObsReported')       $allObsReported       = $aChecklist['allObsReported'];


           if($aPomKeysChecklist[$ic]  == 'effortDistanceKm') $effortDistanceKm = $aChecklist['effortDistanceKm'];
           if($aPomKeysChecklist[$ic]  == 'userDisplayName') $userDisplayName = $aChecklist['userDisplayName'];


           if($aPomKeysChecklist[$ic]  == 'obsDt')            $obsDt = $aChecklist['obsDt'];
           //výber len dátum z hodnoty obsDt, obsahuje aj čas
           //$aPomobsDt = explode(" ", $obsDt);

           if($aPomKeysChecklist[$ic]  == 'durationHrs'):
             $durationHrs     = $aChecklist['durationHrs'];
             $durationMin     = floor($durationHrs * 60);
           endif;    
           if($obsDt and $durationMin):
             $obsDtFrom       = new \DateTime($obsDt);
             $aPomObsDtTo     = date('Y-m-d H:i', strtotime('+'.$durationMin.' minutes', strtotime($obsDt)));
//$output->writeln("$aPomObsDtTo");
             $obsDtTo         = new \DateTime($aPomObsDtTo);
 //            throw print_r($obsDtTo);
             //orig code: ('Y-m-d H:i', strtotime('+'.$durationMin.' minutes', strtotime($obsDt)));

           endif;
           $neimportovatDoAvesu = false;
           if($aPomKeysChecklist[$ic]  == 'comments'): 
             $commentsloc      = $aChecklist['comments'];
             $neimportovatDoAvesu = $this->setNoimport($commentsloc);
             $commentsloc = $this->setCommentsloc($commentsloc,$subId);
           endif;


           //testovací dump obsahu celého checklistu , je to array
           //$output->writeln(var_dump($aChecklist));

           if($aPomKeysChecklist[$ic] == 'obs'): //$aChecklist je vtedy vnorene pole v poli

             $aPomKeysObs = array_keys($aChecklist['obs']);
             //$output->writeln(" aPomKeysObs = array_keys(value);".var_dump($aPomKeysObs));

             for($iobs = 0; $iobs < count($aChecklist['obs']); $iobs++ ){

               //definovanie premenných a v dalsich cykloch ich vyprazdnenie
               $auxCode = $auxValue = $speciesCode = $howManyAtleast = $howManyAtmost = $commentsdruh = $present = $howManyStr = $aPomCount = "";
               foreach($aChecklist['obs'][$aPomKeysObs[$iobs]] as $keyobs => $valueobs ) {

                 if($keyobs == 'speciesCode')      $speciesCode       = $valueobs;

                 if($keyobs == 'howManyAtleast')   $howManyAtleast    = $valueobs;
                 if($keyobs == 'howManyAtmost')    $howManyAtmost     = $valueobs;
                 if($keyobs == 'howManyStr')       $howManyStr        = $valueobs;
                 if($keyobs == 'comments')         $commentsdruh      = $valueobs;
                 if($keyobs == 'present')          $present      = $valueobs;

                 if($keyobs == 'obsAux'):
                   $auxCode         = $valueobs[0]['auxCode'];
                   $auxValue        = $valueobs[0]['value'];
                 endif;
               }
               if($present == true):
                 $aPomCount = 1;
               else:
                 $aPomCount = $howManyAtmost;
               endif;
               
               //UN Used Nest (enter 0 if no birds seen) (Confirmed) 
               /*
                 Problém bude s nulou. eBird ju chce, ak pri hniezde neboli vtáky. 
                 Napr. nájdené hniezdo kúdeľníčky v zime. V Aves máme nulu na 
                 negatívny záznam, tj. "hľadali sme, nebolo to tam" 
                 (v kombinácii s charakterom výskytu NEGAT). 
                 Zrejme bude pri importe potrebné nulu zameniť za 1.
                */
               if ($auxcode = 'UN' and $aPomCount == 0)
                  $aPomCount = 1;


               //získanie specid podľa eBirdkódu, ak nie je nemal by zapísať 
               //checklist vôbec, dať číslo checklistu do logu s vysvetlením
               //že daný ebirdcode nie je v druhovníku, potom to musím ručne
               //daný checklist po úprave druhovníka načítať
               $oPomSpec = $em->getRepository(LkpzoospeciesAves::class)->findOneBy(['lkpzoospecies_ebirdcode' => $speciesCode]);
               if($oPomSpec):
                 $specid = $oPomSpec->getId();

                 //setAvescharakteristiku
                 $charid = $this->setAveschar($auxCode,$aktMesiac);



                 // $em->persist($xx);
                 // $em->flush();
                $output->writeln("Časť údajov k checklistu $subId získaná z prehľadu:  \npozorovateľ: $userDisplayName \ndátum: $obsDt \npozorovanie od: $obsTime \nlokalita: $locName \nlatitude: $latitude \nlongitude: $longitude \n");

               $output->writeln("Lokalitná časť checklistu $subId (poznámka je pre každý pozorovaný druh rovnaká, Aves ich spojí): \nprotokol: $protocolId (poznámka: napr. P22 znamená travel, P21 stationar, P20 incidental) \nkompletný zoznam druhov ?: $allObsReported  \ndátum a čas $obsDt \npozorovanie trvalo: $durationHrs v minútach to je $durationMin \ntrasa: $effortDistanceKm km \nkomentár k lokalite: $commentsloc");


              $output->writeln("Druhová časť záznamu,(poznámka eBird skratka Obs): \ndruh: $speciesCode \npočet min: $howManyAtleast \npočet max: $howManyAtmost \npočet vyjadrený reťazcom: $howManyStr \nbez počtu len prítomný(poznámka, vtedy je tu X):  $present \nkomentár k druhu: $commentsdruh \nhniezdna charakteristika (poznámka ak je vyplnená, upravené pre aves, ak nie je vyplnená doplníme podľa dátumu): $auxCode  (poznámka eBird má aj takúto hodnotu: $auxValue)\n");
              if($commentsloc == "") $commentsloc = $this->setCommentsloc($commentsloc,$subId);

$aPomZapisane = false;
//vynimky, ludia co uz do urcite datumu prepisali z eBirdu do Avesu rucne
if ($userDisplayName == 'Jan Dobsovic' and $spracujaktivityzaden <= '2019-12-31' ) $aPomZapisane = true;
if ($userDisplayName == 'Lukas Sekelsky' and $spracujaktivityzaden <= '2020-04-23' ) $aPomZapisane = true;
if ($userDisplayName == 'Dusan Kerestur' and $spracujaktivityzaden <= '2020-12-31' ) $aPomZapisane = true;
if($aPomZapisane == false): 
  $z = new Zoology();

  $sfGuardUserId = $this->setSfguardid($userDisplayName);
  $z->setSfGuardUserId($sfGuardUserId);

  $z->setZoologyPerson($userDisplayName);
  $z->setZoologyDate($spracujaktivityzadendt);
  $z->setZoologyLongitud($longitude);
  $z->setZoologyLatitud($latitude);
  $z->setZoologyLocality($locName);

  if($effortDistanceKm > 0) $z->setZoologyDistkm($effortDistanceKm);

  $aPomTypPoz = $this->setTyppoz($protocolId);
  $z->setZoologyTyppoz($aPomTypPoz);

  $oPomPrist = $em->getRepository(Lkppristupnost::class)->findOneBy(['lkppristupnost_pristupnost' => 'V']);
  $z->setZoologyAccessibility($oPomPrist); 
  $z->setLkpzoospeciesId($oPomSpec);
  $z->setCount($aPomCount);
  $z->setDescription($commentsdruh); //todo podmienit ak existuje
  $oPomChar = $em->getRepository(Lkpzoochar::class)->findOneBy(['id' => $charid]);
  $z->setLkpzoocharId($oPomChar);
  if($obsDtFrom) $z->setZoologyTimefrom($obsDtFrom);
  if($obsDtTo) $z->setZoologyTimeto($obsDtTo);
  $z->setZoologyCompletelistofspecies($allObsReported);
  $z->setZoologyDescription($commentsloc); //todo nemusi byt vyplnene, osetrit if exists
  if($neimportovatDoAvesu): 
    $z->setZoologyExport("Z");
  else:
    $z->setZoologyExport("N"); 
  endif;
  $em->persist($z);
  $em->flush();
else:
  $output->writeln("Záznam už je v avese pre užívateľa $userDisplayName a dátum $spracujaktivityzaden a checklist $subId.");
endif;

               else: //neexistuje v druhovniku taky druh
                 $output->writeln("Nenašiel sa tento druh: $speciesCode (checklist: $subId");
                $output->writeln("Časť údajov k checklistu $subId získaná z prehľadu:  \npozorovateľ: $userDisplayName \ndátum: $obsDt \npozorovanie od: $obsTime \nlokalita: $locName \nlatitude: $latitude \nlongitude: $longitude \n");

               $output->writeln("Lokalitná časť checklistu $subId (poznámka je pre každý pozorovaný druh rovnaká, Aves ich spojí): \nprotokol: $protocolId (poznámka: napr. P22 znamená travel, P21 stationar, P20 incidental) \nkompletný zoznam druhov ?: $allObsReported  \ndátum a čas $obsDt \npozorovanie trvalo: $durationHrs v minútach to je $durationMin \ntrasa: $effortDistanceKm km \nkomentár k lokalite: $commentsloc");


              $output->writeln("Druhová časť záznamu nenájdeného druhu,(poznámka eBird skratka Obs): \ndruh: $speciesCode \npočet min: $howManyAtleast \npočet max: $howManyAtmost \npočet vyjadrený reťazcom: $howManyStr \nbez počtu len prítomný(poznámka, vtedy je tu X):  $present \nkomentár k druhu: $commentsdruh \nhniezdna charakteristika (poznámka ak je vyplnená, upravené pre aves, ak nie je vyplnená doplníme podľa dátumu): $auxCode  (poznámka eBird má aj takúto hodnotu: $auxValue)\n");
              if($commentsloc == "") $commentsloc = $this->setCommentsloc($commentsloc,$subId);


               endif;


             }
             endif; //koniec pre výber pozorovaní - druhov v jednom checkliste

         } //koniec for  $aChecklist 
     
      } //for   $aData v určitom kraji

    endforeach;

       $output->writeln($spracujaktivityzaden.' akt rok: '.$aktRok.' akt mesiac: '.$aktMesiac.' akt den'.$aktDen);

       return Command::SUCCESS;
    }

   public function setAveschar($auxCode,$aktMesiac)
   {
     $aktCharakteristika = 36;
     if($auxCode <> ""):
       //todo vrat aves kod
       switch($auxCode):
       case 'NY': //NY je v kódoch NY, Nest with young seen or heard
         $aktCharakteristika = 6;
         break;
       case 'NE': //NE je v kódoch NE
         $aktCharakteristika = 5;
         break;
       case 'FS': //FS je v kódoch FS
         $aktCharakteristika = 4;
         break;
       case 'FR': //FY v kodoch FR
         $aktCharakteristika = 4;
         break;
       case 'FY': //CF je kódoch FY
         $aktCharakteristika = 4;
         break;
       case 'FL': //FL v kodoch rovnako FL
         $aktCharakteristika = 2;
         break;
       case 'ON': //ON v kodoch rovnako ON
         $aktCharakteristika = 3;
         break;
       case 'UN': //UN je v kodoch UN, todo! to som si nie istý!! že D11
         $aktCharakteristika = 1;
         break;
       case 'DD': //DD je v kódoch DD
         $aktCharakteristika = 31;
         break;
       case 'NB': //NB je v kódoch NB
         $aktCharakteristika = 29;
         break;
       case 'CM': //CN je v kódoch CM, carrying nest material
         $aktCharakteristika = 29;
         break;
       case 'BP': //PE je v kodoch BP, physiological evidence
         $aktCharakteristika = 28;
         break;
       case 'DN': //B je v kodoch DN, woodpecker/wren nest building 
         $aktCharakteristika = 29;
         break;
       case 'AB': //A je v kodoch AB, Agitated Behavior (Probable)
         $aktCharakteristika = 27;
         break;
       case 'VS': //N je v kódoch VS, visiting probable nest site
         $aktCharakteristika = 26;
         break;
       case 'CC': //C je v kódoch CC
         $aktCharakteristika = 25;
         break;
       case 'T7': //T je vkodoch T7, territ defense
         $aktCharakteristika = 24;
         break;
       case 'PO'://P je v kodoch PO, P Pair in Suitable Habitat (Probable)
         $aktCharakteristika = 23;
         break;
       case 'SM': //M je v kódoch SM
         $aktCharakteristika = 24;
         break;
       case 'S7': //S7 je v kódoch S7
         $aktCharakteristika = 24;
         break;
       case 'S1': //S je v kodoch S1, singing bird
         $aktCharakteristika = 22;
         break;
       case 'OS': //H v kodoch ako OS
         $aktCharakteristika = 21;
         break;
       case 'FO': //F flyin over je v kodoch: FO
         if ($aktMesiac == '04' || 
	         $aktMesiac == '05' || 
	         $aktMesiac == '06' || 
	         $aktMesiac == '07' ) $aktCharakteristika = 36; //Rudo nesuhlasil s A0 (19)
        if ($aktMesiac == '08' || 
	         $aktMesiac == '09' || 
	         $aktMesiac == '10' || 
	         $aktMesiac == '11' || 
	         $aktMesiac == '03' ) $aktCharakteristika = 36;

        if ($aktMesiac == '12' || 
	         $aktMesiac == '01' || 
	         $aktMesiac == '02' ) $aktCharakteristika = 70;

         break;
       default: 
         $aktCharakteristika = 19;
         break;
       endswitch;
     else:
     //todo vrat aves kod podla rocneho obdobia
       if ($aktMesiac == '08' || 
	         $aktMesiac == '09' || 
	         $aktMesiac == '10' || 
	         $aktMesiac == '11' || 
	         $aktMesiac == '03' ) $aktCharakteristika = 36;

       if ($aktMesiac == '12' || 
	         $aktMesiac == '01' || 
	         $aktMesiac == '02' ) $aktCharakteristika = 70;
       
       if ($aktMesiac == '04' || 
	         $aktMesiac == '05' || 
	         $aktMesiac == '06' || 
	         $aktMesiac == '07' ) $aktCharakteristika = 19;

     endif;
     return $aktCharakteristika;
   }

   public function setSfguardid($userDisplayName)
   {
     $em                 = $this->container->get('doctrine')->getManager();
     $sfGuardUserId = 1223; //eBird default
     if($userDisplayName):
       $oPomsfguarduser = $em->getRepository(User::class)->findOneBy(['ebdisnam' => $userDisplayName]);
       if($oPomsfguarduser) $sfGuardUserId = $oPomsfguarduser->getSfGuardUserId();
     endif;
     return $sfGuardUserId;
   }


   public function setCommentsloc($commentsloc = '', $subId = 0)
   {
     $sPomCommentsloc = " Checklist eBird $subId.";
     //ak pozorovateľ vyplnil komentár k lokalite, pridaj za pôvodný komentár  
     if($commentsloc <> ''): 
       $commentsloc  = str_ireplace("\x0D", "", $commentsloc);
       $commentsloc = trim(preg_replace('/\s+/', ' ', $commentsloc));
       $sPomCommentsloc = "$commentsloc,.. $sPomCommentsloc";
     endif;

     return $sPomCommentsloc;

   }

  public function setTyppoz($protocolId = '')
   {
     $sPomTyppoz = '';
     if($protocolId <> ''):
       switch($protocolId):
        case "P20": //Incidental, náhodné pozorovanie,  pozorovatel sa primarne nesustredil na pozorovanie, nemoze byt oznaceny ze je kompletne pozorovanie,
        $sPomTyppoz = "náhodné";
        break;
        case "P21": //Stationary, Cielené stacionárne pozorovanie, bod s max chodenim do 30m, s jasnym start stop casom a sustredenie sa na pozorovanie
        $sPomTyppoz = "stacionárne";
        break;
        case "P22": //Traveling, Cielené pohyblivé pozorovanie, sustredenie sa na pozorovanie, jasny cas startu a casu straveneho pozorovanim, pozorovatel sa pohyboval viac nez 30m od miesta zacatia, pozorovatel vie aku vzdialenost presiel
        $sPomTyppoz = "na trase";
        break;
        case "P62":
       //Historical, cielené pozorovanie s čiastočnou presnosťou, 
        $sPomTyppoz = "s čiastočnou presnosťou";
        break;

       endswitch;

     endif;
     return $sPomTyppoz;

   }
   //setNoimport
   //ak komentár obsahuje dohodnutý reťazec, potom zabezpečiť neimportovanie do Avesu

   public function setNoimport($commentsloc)
   {
     $dohodznak = 'XXX';
     if(strpos($commentsloc, $dohodznak) !== false):
       return true; //je pravda že neimportovať
     else:
       return false; //je možné pokračovať v importe
     endif;
   }
}
?>
