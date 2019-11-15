<?php
// src/Utilities/AvesExcelTools.php

namespace App\Utilities;

use App\Utilities\PrvyExcelFilter;
use App\Utilities\ExcelDatum2Unix;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Repository\LkppristupnostRepository;
use App\Repository\LkpzoospeciesAvesRepository;
use App\Repository\LkpzoocharRepository;
use App\Repository\ZoologyRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Zoology;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AvesExcelTools
{
  protected $lkppristupnostRepository;
  protected $lkpzoospeciesAvesRepository;
  protected $lkpzoocharRepository;
  protected $zoologyRepository;
  protected $em;
  protected $validator;
  private $security;

  public function __construct(LkppristupnostRepository $lkppristupnostRepository, LkpzoospeciesAvesRepository $lkpzoospeciesAvesRepository, LkpzoocharRepository $lkpzoocharRepository, EntityManagerInterface $em, Security $security, ValidatorInterface $validator, ZoologyRepository $zoologyRepository)
  {
    $this->lkppristupnostRepository    = $lkppristupnostRepository;
    $this->lkpzoospeciesAvesRepository = $lkpzoospeciesAvesRepository;
    $this->lkpzoocharRepository        = $lkpzoocharRepository;
    $this->zoologyRepository           = $zoologyRepository;
    $this->em                          = $em;
    $this->security                    = $security;
    $this->validator                   = $validator;

  }

  public function wgs84Test($bunka,$cRiadka,$charlength = 50,$cStlpca,$cNazovPolozky)
  {
    $aPomPlatnyZdroj = false;

    if( gettype($bunka) == 'float' ||  gettype($bunka) == 'double'):
      $aPomPlatnyZdroj = true;
    else:
     $aPomPlatnyZdroj = false;
    endif;
    if($aPomPlatnyZdroj):
      return '';
    else:
      return ' Chyba v bunke '.$cStlpca.$cRiadka.'! Očakávaná '.$cNazovPolozky.' (povinná), hodnota je: '.$bunka.' a typ: '.gettype($bunka);
    endif;

  } 

  public function charakterfieldTest($bunka,$cRiadka,$charlength = 50,$cStlpca,$cNazovPolozky)
  {
    $aPomPlatnyZdroj = false;
    $aPomChybaZdroja = '';

    if( gettype($bunka) == 'string' || $bunka == '' || $bunka == Null):
      if( strlen($bunka) <= $charlength):
        $aPomPlatnyZdroj = true;
      else:
        $aPomPlatnyZdroj = false;
        $aPomChybaZdroja .= ' Problém v bunke '.$cStlpca.$cRiadka.'! Očakávaná položka '.$cNazovPolozky.' dĺžky do '.$charlength.' znakov, hodnota je viac ako '.$charlength.' znakov: '.$bunka;
      endif;
    else:
     $aPomPlatnyZdroj = false;
     $aPomChybaZdroja .= ' Chyba v bunke '.$cStlpca.$cRiadka.'! Očakávaná položka '.$cNazovPolozky.' typu reťazec znakov, hodnota nie je typu string! ale '.gettype($bunka);
    endif;

    if($aPomPlatnyZdroj):
      return '';
    else:
      return $aPomChybaZdroja;
    endif;

  }
  public function datumTest($bunka,$cRiadka)
  {
    $aPomPlatnyZdroj = false;
    $aPomChybaZdroja = '';
    $ExcelDatum2Unix = new ExcelDatum2unix();

    if(is_numeric($bunka)): //excel číslo vyjadrenia dátumu alebo chyba?
      $dPomDatum =  $ExcelDatum2Unix->getExcelDate2Unix($bunka);
      if(strtotime($dPomDatum)): 
        $aPomPlatnyZdroj = true;
      else:
       $aPomPlatnyZdroj = false;

      $aPomChybaZdroja .= ' Chyba v bunke D'.$cRiadka.'! Očakávaný dátum (povinne), hodnota je číslo ale nie je to číselné vyjadrenie dátumu excelom!';
      endif; 
    else: //nie je to hodnota typu numeric, musí byť string
      if( gettype($bunka) == 'string'): //musí sa overiť či je dátum ako dohoda
        $arrPomDate = explode('.',$bunka); //dohodnutý formát den.mesiac.rok
    
        if(sizeof($arrPomDate) == 3): //vzniknuté pole musí mať tri prvky
          //ešte či je každá z troch zložená len z čísel
          if(    ctype_digit(strval($arrPomDate[0])) 
              && ctype_digit(strval($arrPomDate[1])) 
              && ctype_digit(strval($arrPomDate[2]))): 

            //ak má tri prvky, musia byť platné dokopy ako dátum
            $bPom = checkdate($arrPomDate[1], $arrPomDate[0], $arrPomDate[2]);
            if($bPom == true):
              $aPomPlatnyZdroj = true;
            else:
            $aPomPlatnyZdroj = false;
            $aPomChybaZdroja .= ' Chyba v bunke D'.$cRiadka.'! Očakávaný dátum (povinne), je to reťazec no nie je to platný dátum!: '.$bunka;
            endif;
          else:
            $aPomPlatnyZdroj = false;
            $aPomChybaZdroja .= ' Chyba v bunke D'.$cRiadka.'! Očakávaný dátum (povinne), je to reťazec no neobsahuje len čísla!: '.$bunka;
          endif;
        else:
          $aPomPlatnyZdroj = false;
          $aPomChybaZdroja .= ' Chyba v bunke D'.$cRiadka.'! Očakávaný dátum (povinne), je to reťazec ale nie je komplet!: '.$bunka;
        endif;
        
      else:
       $aPomPlatnyZdroj = false;

       $aPomChybaZdroja .= ' Chyba v bunke D'.$cRiadka.'! Očakávaný dátum (povinne), nie je to číslo ani reťažec ale typu: '.gettype($bunka);
      endif;
    endif;


    if($aPomPlatnyZdroj):
      return '';
    else:
      return $aPomChybaZdroja;
    endif;

  }

  public function pristupnostTest($bunka,$cRiadka,$charlength = 1,$cStlpca,$cNazovPolozky)
  {

    $aPomPlatnyZdroj = false;
    $aPomChybaZdroja = '';

    if( gettype($bunka) == 'string'):
      if( strlen($bunka) == $charlength):

        //otestuj či je v rozsahu lkppristupnost
        $lkppristupnost = $this->lkppristupnostRepository->findBy(['lkppristupnost_pristupnost' => $bunka]);
        if ($lkppristupnost):
          $aPomPlatnyZdroj = true;
        else:
          $aPomPlatnyZdroj = false;
          $aPomChybaZdroja .= ' Chyba v bunke '.$cStlpca.$cRiadka.'! Očakávaná '.$cNazovPolozky.'  nie je definovaná v tabuľke prípustných hodnôt: toto došlo '.$bunka;
        endif;
      else:
        $aPomPlatnyZdroj = false;
        $aPomChybaZdroja .= ' Problém v bunke '.$cStlpca.$cRiadka.'! Očakávaná '.$cNazovPolozky.' dĺžky znakov: '.$charlength.' , hodnota je viac ako '.$charlength.' znak: '.$bunka.' strlen je '.strlen($bunka) ;
      endif;
  else:

   $aPomPlatnyZdroj = false;
   $aPomChybaZdroja .= ' Chyba v bunke '.$cStlpca.$cRiadka.'! Očakávaná '.$cNazovPolozky.'  typu reťazec, hodnota nie je typu string! ale '.gettype($bunka);
  endif;

    if($aPomPlatnyZdroj):
      return '';
    else:
      return $aPomChybaZdroja;
    endif;


  }

  public function druhTest($bunka,$cRiadka,$charlength = 1,$cStlpca,$cNazovPolozky)
  {

    $aPomPlatnyZdroj = false;
    $aPomChybaZdroja = '';

     if( is_int($bunka) || gettype($bunka) == 'double' ):
        $lkpzoospeciesAves = $this->lkpzoospeciesAvesRepository->findBy(['id' => $bunka]);
        if ($lkpzoospeciesAves):
          $aPomPlatnyZdroj = true;
        else:
          $aPomPlatnyZdroj = false;
          $aPomChybaZdroja .= ' Chyba v bunke '.$cStlpca.$cRiadka.'! Očakávaný '.$cNazovPolozky.' ako id nie je definovaný v tabuľke prípustných hodnôt: toto došlo '.$bunka;
        endif;
    else: //ak nedosiel druh ako cislo, mohol dojst ako retazec a to sk ci lat.
      if( gettype($bunka) == 'string'):

        //najprv nie viac ako 255 znakov
        if( strlen($bunka <= $charlength)):

          //teraz overit ci je to sk alebo lat
          //najprv ci vedecky genus species
          $lkpzoospeciesAves = $this->lkpzoospeciesAvesRepository->findBy(
            ['lkpzoospecies_genus_species' => $bunka]);
          if ($lkpzoospeciesAves):
            $aPomPlatnyZdroj = true;
          else:
          
            //nenasiel lat hladaj sk
            $lkpzoospeciesAves = $this->lkpzoospeciesAvesRepository->findBy(
            ['lkpzoospecies_sk' => $bunka]);

            if ($lkpzoospeciesAves):
              $aPomPlatnyZdroj = true;
            else:

            //nenasiel v druhovniku ani podla lat ani sk
            $aPomPlatnyZdroj = false;
            $aPomChybaZdroja .= ' Chyba v bunke '.$cStlpca.$cRiadka.'! Očakávaný '.$cNazovPolozky.'  ako reťazec nie je definovaný v tabuľke prípustných hodnôt ani vedecky ani slovensky: toto došlo '.$bunka;

            endif;
          endif; 
        else:
          $aPomPlatnyZdroj = false;
          $aPomChybaZdroja .= ' Problém v bunke '.$cStlpca.$cRiadka.'! Očakávaný '.$cNazovPolozky.'  dĺžky do '.$charlength.' znakov, hodnota je viac ako '.$charlength.' znakov: '.$bunka;
       endif;

      else:
       $aPomPlatnyZdroj = false;
       $aPomChybaZdroja .= ' Chyba v bunke '.$cStlpca.$cRiadka.'! Očakávaný ako reťazec slov či vedeckého názvu, hodnota nie je typu číslo ani string! ale '.gettype($bunka);
      endif;
    endif;


    if($aPomPlatnyZdroj):
      return $lkpzoospeciesAves;
    else:
      return $aPomChybaZdroja;
    endif;

  }

  public function pocetTest($bunka,$cRiadka,$charlength = 1,$cStlpca,$cNazovPolozky)
  {

    $aPomPlatnyZdroj = false;
    $aPomChybaZdroja = '';

    if( ctype_digit(strval($bunka))):
      $aPomPlatnyZdroj = true;
    else:
      $aPomPlatnyZdroj = false;
      $aPomChybaZdroja .= ' Chyba v bunke '.$cStlpca.$cRiadka.'! Očakávaný '.$cNazovPolozky.' (povinná) má byť celé číslo, hodnota je: '.$bunka.' a typ: '.gettype($bunka);
    endif;


    if($aPomPlatnyZdroj):
      return '';
    else:
      return $aPomChybaZdroja;
    endif;

  }

  public function charakteristikaTest($bunka,$cRiadka,$charlength = 1,$cStlpca,$cNazovPolozky)
  {

    $aPomPlatnyZdroj = false;
    $aPomChybaZdroja = '';
    if( ctype_digit(strval($bunka))):
      $lkpzoochar = $this->lkpzoocharRepository->findBy(['id' => $bunka]);
      if ($lkpzoochar):
        $aPomPlatnyZdroj = true;
      else:
        $aPomPlatnyZdroj = false;
        $aPomChybaZdroja .= ' Chyba v bunke '.$cStlpca.$cRiadka.'! Očakávaná '.$cNazovPolozky.' ako id nie je definovaná v tabuľke prípustných hodnôt: toto došlo '.$bunka;
      endif;
    else: //ak nedosla charakteristika ako cislo, mohla dojst ako retazec
      if( gettype($bunka) == 'string'):

        if( strlen($bunka <= $charlength)):

          //teraz overit ci je spravne 
          $lkpzoochar = $this->lkpzoocharRepository->findBy(
            ['lkpzoochar_id_ch' => $bunka]);
          if ($lkpzoochar):
            $aPomPlatnyZdroj = true;
          else:
             $aPomPlatnyZdroj = false;
             $aPomChybaZdroja .= ' Chyba v bunke '.$cStlpca.$cRiadka.'! Očakávaná '.$cNazovPolozky.' ako reťazec nie je definovaná v tabuľke prípustných hodnôt, toto došlo '.$bunka;
          endif; 
        else:
          $aPomPlatnyZdroj = false;
          $aPomChybaZdroja .= ' Problém v bunke '.$cStlpca.$cRiadka.'! Očakávaná  '.$cNazovPolozky.'   dĺžky do'.$charlength.' znakov, hodnota je viac ako '.$charlength.' znakov: '.$bunka;
       endif;

      else:
       $aPomPlatnyZdroj = false;
       $aPomChybaZdroja .= ' Chyba v bunke '.$cStlpca.$cRiadka.'! Očakávaný ako reťazec, číslo ani string! ale '.gettype($bunka);
      endif;
    endif;

    if($aPomPlatnyZdroj):
   //   die(var_dump($lkpzoochar[0]->getId()));
      return $lkpzoochar;
    else:
      return $aPomChybaZdroja;
    endif;
  }


  public function overdvariadky($excelSubor)
  {
    $ExcelDatum2Unix          = new ExcelDatum2unix();
    $filterSubset             = new PrvyExcelFilter();

    $inputFileType            = IOFactory::identify($excelSubor);
    $reader                   = IOFactory::createReader($inputFileType); 

    $reader->setReadFilter($filterSubset);
    $reader->setReadDataOnly(true); 

    $spreadsheet    = $reader->load($excelSubor);
    $sheetData      = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    $aPomPlatnyZdroj = false;
    $aPomChybaZdroja = '';
    $iPom = 1;
    foreach($sheetData as $row):
      //todo: zisti, či v prvom riadku sú nadpisy alebo hodnoty
      //podľa toho, či je zemepisná dĺžka ako iné než string
      //potom je to hodnot
      if($iPom==1): //v prvom riadku sú nadpisy (možno aj hodnoty, ak niekto nepoužíva )
       $aPomSprava1r = 'nadpis'; //alebo "data"
      endif;

      if($iPom==2): //až v druhom riadku sú hodnoty

        $aPomChybaZdroja .= $this->wgs84Test($row['A'],$iPom,50,'A','Zemepisná dĺžka'); 
        $aPomChybaZdroja .= $this->wgs84Test($row['B'],$iPom,50,'B','Zemepisná šírka'); 
        $aPomChybaZdroja .= $this->charakterfieldTest($row['C'],$iPom,50,'C','Lokalita'); 
        $aPomChybaZdroja .= $this->datumTest($row['D'],$iPom); 
        $aPomChybaZdroja .= $this->charakterfieldTest($row['E'],$iPom,255,'E','Poznámka'); 
        $aPomChybaZdroja .= $this->pristupnostTest($row['F'],$iPom,1,'F','Prístupnosť');

        $aPomDruh = $this->druhTest($row['G'],$iPom,255,'G','Druh');
        if(gettype($aPomDruh) == 'string') $aPomChybaZdroja .= $aPomDruh;


        $aPomChybaZdroja .= $this->pocetTest($row['H'],$iPom,0,'H','Počet');

        $aPomChara = $this->charakteristikaTest($row['I'],$iPom,15,'I','Charakteristika');
        if(gettype($aPomChara) == 'string') $aPomChybaZdroja .= $aPomChara;

        $aPomChybaZdroja .= $this->charakterfieldTest($row['J'],$iPom,255,'J','Poznámka k druhu');

      endif;
    $iPom++; 
    endforeach; 
    if($aPomChybaZdroja != '')    $aPomChybaZdroja = ' Test druhého riadka (v prvom by mali byť nadpisy). Podľa tejto chyby ešte raz skontrolujte Váš excel súbor, či nie sú podobné chyby aj v iných riadkoch a potom skúste znovu.'.$aPomChybaZdroja;

    if($aPomChybaZdroja == ''):
      return 'OK';
    else:
      return $aPomChybaZdroja;
    endif;

  }

  public function importdata($excelSubor)
  {
    $aPomChybaZdroja = '';
    $aPomNezalozeneRiadky = array();
    $aPomZalozeneRiadky   = array();

    $inputFileType            = IOFactory::identify($excelSubor);
    $reader                   = IOFactory::createReader($inputFileType); 

    $reader->setReadDataOnly(true); 
    $spreadsheetDataKompl    = $reader->load($excelSubor);
    $sheetDataKompl  = $spreadsheetDataKompl->getActiveSheet()->toArray(null, true, true, true);

    $iPomKompl = 1;
    foreach($sheetDataKompl as $rowKompl):
     if($iPomKompl>=2): //až v druhom riadku sú hodnoty
         $zoology   = new Zoology();

         //ktorakolvek polozka ak zlyha test nastavi false
         $bPomZaloz = true; //true = založiť riadok, false = nezaložiť riadok


         $zoology->setSfGuardUserId($this->security->getUser()->getSfGuardUserId());
         $zoology->setZoologyExport('I');


         //zemdlzka
         $aPomChybaZdroja = $this->wgs84Test($rowKompl['A'],$iPomKompl,50,'A','Zemepisná dĺžka'); 
         if($aPomChybaZdroja == ''):
           $zoology->setZoologyLongitud($rowKompl['A']);
         else:
           $bPomZaloz = false; 
           $aPomNezalozeneRiadky[$iPomKompl]['A'] = $aPomChybaZdroja;
         endif;


         //zemsirka
         $aPomChybaZdroja = $this->wgs84Test($rowKompl['B'],$iPomKompl,50,'B','Zemepisná šírka'); 
         if($aPomChybaZdroja == ''):
           $zoology->setZoologyLatitud($rowKompl['B']);
         else:
           $bPomZaloz = false; 
           $aPomNezalozeneRiadky[$iPomKompl]['B'] = $aPomChybaZdroja;
         endif;


        //lokalita
         $aPomChybaZdroja = $this->charakterfieldTest($rowKompl['C'],$iPomKompl,50,'C','Lokalita'); 
         if($aPomChybaZdroja == ''):
           $zoology->setZoologyLocality($rowKompl['C']);
         else:
           $bPomZaloz = false; 
           $aPomNezalozeneRiadky[$iPomKompl]['C'] = $aPomChybaZdroja;
         endif;


        //datum
        $aPomChybaZdroja = $this->datumTest($rowKompl['D'],$iPomKompl); 
         if($aPomChybaZdroja == ''):
           $dPom = \DateTime::createFromFormat('d.m.Y', $rowKompl['D']);
           $zoology->setZoologyDate($dPom);
         else:
           $bPomZaloz = false; 
           $aPomNezalozeneRiadky[$iPomKompl]['D'] = $aPomChybaZdroja;
         endif;

  
         //poznlok
         $aPomChybaZdroja = $this->charakterfieldTest($rowKompl['E'],$iPomKompl,255,'E','Poznámka'); 
         if($aPomChybaZdroja == ''):
           $zoology->setZoologyDescription($rowKompl['E']);
         else:
           $bPomZaloz = false; 
           $aPomNezalozeneRiadky[$iPomKompl]['E'] = $aPomChybaZdroja;
         endif;


  
         //pristupnost
        $aPomChybaZdroja = $this->pristupnostTest($rowKompl['F'],$iPomKompl,1,'F','Prístupnosť');
         if($aPomChybaZdroja == ''):
           $lkppristupnost = $this->lkppristupnostRepository->findBy(['lkppristupnost_pristupnost' => $rowKompl['F']]);
           $zoology->setZoologyAccessibility($lkppristupnost[0]);
         else:
           $bPomZaloz = false; 
           $aPomNezalozeneRiadky[$iPomKompl]['F'] = $aPomChybaZdroja;
         endif;



         //druh
         $aPomChybaZdroja = $this->druhTest($rowKompl['G'],$iPomKompl,255,'G','Druh');

         if(gettype($aPomChybaZdroja) != 'string'):

           $zoology->setLkpzoospeciesId($aPomChybaZdroja[0]);

         else:
           $bPomZaloz = false; 
           $aPomNezalozeneRiadky[$iPomKompl]['G'] = $aPomChybaZdroja;
         endif;

         //pocet
         $aPomChybaZdroja = $this->pocetTest($rowKompl['H'],$iPomKompl,0,'H','Počet');

         if($aPomChybaZdroja == ''):
           $zoology->setCount($rowKompl['H']);
         else:
           $bPomZaloz = false; 
           $aPomNezalozeneRiadky[$iPomKompl]['H'] = $aPomChybaZdroja;
         endif;


         //char
          $aPomChybaZdroja = $this->charakteristikaTest($rowKompl['I'],$iPomKompl,15,'I','Charakteristika');

         if(gettype($aPomChybaZdroja) != 'string'):

           // $lkpzoochar = $lkpzoocharRepository->findBy(
           //  ['id' => $rowKompl['I']]);
           $zoology->setLkpzoocharId($aPomChybaZdroja[0]);

         else:
           $bPomZaloz = false; 
           $aPomNezalozeneRiadky[$iPomKompl]['I'] = $aPomChybaZdroja;
         endif;

         //pozndruh
          $aPomChybaZdroja = $this->charakterfieldTest($rowKompl['J'],$iPomKompl,255,'J','Poznámka k druhu');
         if($aPomChybaZdroja == ''):
           $zoology->setDescription($rowKompl['J']);
         else:
           $bPomZaloz = false; 
           $aPomNezalozeneRiadky[$iPomKompl]['J'] = $aPomChybaZdroja;
         endif;
 

         $errors = $this->validator->validate($zoology);
         if (count($errors) > 0) {
             return new Response((string) $errors, 400);
         };
        
         if($bPomZaloz):
           //todo kontrolu či nie je také zoology už založené 
           //napr. opätovným načítaním excel súbor, kde zopár chybných opravil
           //a chce ich poslať znovu
           //x y datum sf_guard_user ak sa rovnajú tak continue;
           $kontrolaZoo = $this->zoologyRepository->findOneBy(
             [ 
               'sf_guard_user_id' => $this->security->getUser()->getSfGuardUserId(), 
               'zoology_longitud' => $rowKompl['A'],  
               'zoology_latitud'  => $rowKompl['B'],
               'zoology_date'     => $dPom,  
               'lkpzoospecies_id' => $zoology->getLkpzoospeciesId()->getId(),
               'count'                 => $zoology->getCount(),
               'lkpzoochar_id'         => $zoology->getLkpzoocharId()->getId()
             ]
           );
           if($kontrolaZoo):
             $aPomNezalozeneRiadky[$iPomKompl]['riadok'] = 'Už bol založený.';
           else:
           $this->em->persist($zoology);
           $this->em->flush();
           //riadok v excel a id založeného riadka
           $aPomZalozeneRiadky[$iPomKompl] =  $zoology->getId();
           endif;
         endif;
      
     endif;
     $iPomKompl++;
   endforeach;

   $aPomResult = array();
   if(!$this->isEmpty($aPomNezalozeneRiadky))
     $aPomResult['Nezaložené'] = $aPomNezalozeneRiadky;

   if(!$this->isEmpty($aPomZalozeneRiadky))
     $aPomResult['Založené']  = $aPomZalozeneRiadky;   

   return $aPomResult; //vrati pole plne informacii o zalozenych i nezalozenych riadkoch
  }

  //https://stackoverflow.com/questions/18401584/how-to-check-if-a-multidimensional-array-is-empty-or-not
  public function isEmpty(array $array): bool
  {
      $empty = true;

      array_walk_recursive($array, function ($leaf) use (&$empty) {
          if ($leaf === [] || $leaf === '') {
              return;
          }

          $empty = false;
      });

      return $empty;
  }
}

?>
