<?php
namespace App\Tests;

use App\Entity\Formation;
use DateTime;
use PHPUnit\Framework\TestCase ;


/**
 * [Description FormationTest]
 */
class FormationTest extends TestCase {
    
    /**
     * Test unitaire getPublishedAtString
     */
    public function testgetPublishedAtString(){
         //Création d'instance de Formation 
         $formation = new Formation ;
         // Iniatialisation d'une valeur test pour la propriété PlublishedAt
         $formation->setPublishedAt(new DateTime("2021-01-04 17:00:12"));
         // Vérifié si la methode appellé retourne le resultat attendu
         $this->assertEquals("04/01/2021", $formation->getPublishedAtString(),"Formatage du test echec");
    }
}

