<?php
// src/Command/NastAktCharCommand.php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\Lkpzoochar;
use App\Repository\LkpzoocharRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NastAktCharCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'cas:nastavAvesCharakteristiku';

    private $container;

    public function __construct(ContainerInterface $container)
    {

    		$this->container = $container;
    		parent::__construct();
    }
    protected function configure()
    {
	$this->setDescription('Podľa aktuálneho dátumu nastaví v charakteristike položku comborder u takej charakteristiky, ktorá je pre Aves v danom čase aktuálna');
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
       $aktCharakteristika = 'A0';
       $dPomDnes           = date('Y-m-d');
       $aktMesiac          = date('m');
       $em                 = $this->container->get('doctrine')->getManager();

       //doposiel bola na prvom mieste ktora charakteristika?
       //zistit nez odhadovat podla mesiaca, vratit comborder
       $oPomTerajsiaChar = $em->getRepository(Lkpzoochar::class)->findOneBy(['lkpzoochar_comborder' => 0]);
       if($oPomTerajsiaChar):
	 switch($oPomTerajsiaChar->getId()):
	   case 36:
	     $oPomTerajsiaChar->setLkpzoocharComborder(20);
	     break;
	   case 19:
	     $oPomTerajsiaChar->setLkpzoocharComborder(1);
	     break;
	   case 70:
	     $oPomTerajsiaChar->setLkpzoocharComborder(32);
	     break;
	   endswitch;
         $em->persist($oPomTerajsiaChar);
         $em->flush();
       endif;
       if ($aktMesiac == '08' || 
	   $aktMesiac == '09' || 
	   $aktMesiac == '10' || 
	   $aktMesiac == '11' || 
	   $aktMesiac == '03' ) 
	  $aktCharakteristika = 36;
       if ($aktMesiac == '12' || 
	   $aktMesiac == '01' || 
	   $aktMesiac == '02' ) 
	  $aktCharakteristika = 70;
       if ($aktMesiac == '04' || 
	   $aktMesiac == '05' || 
	   $aktMesiac == '06' || 
	   $aktMesiac == '07' ) 
	  $aktCharakteristika = 19;

       $oPomChar = $em->getRepository(Lkpzoochar::class)->find($aktCharakteristika);
       $oPomChar->setLkpzoocharComborder(0);
       $em->persist($oPomChar);
       $em->flush();

       $output->writeln('Podľa pravidiel nastavil charakteristiku: '.$aktCharakteristika);

       return Command::SUCCESS;
    }
}
?>
