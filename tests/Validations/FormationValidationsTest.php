<?php
namespace App\Tests\Validations;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * [Description FormationValidationsTest]
 */
class FormationValidationsTest extends KernelTestCase {

   /**
     * @var FormationRepository
     */
    private $repository;

    /**
     * Récupère le repository de Visite
     * @return FormationRepository
     * 
     */
    public function recupRepository(): FormationRepository
    {
        self::bootKernel();
        $repository = self::getContainer()->get(FormationRepository
        ::class);
        return $repository;
    }

   

    /**
     * @return Formation
     */
    public function getFormation() : Formation {
    return (new Formation())
    ->setTitle("TestFormation");
    
}

 /**
     * Utilisaiton du Kernel pour tester une règle de validation
     * @param Formation $formation
     * @param int $nbErreursAttendues
     * @param string $message
     */
    public function assertErrors(Formation $formation, int $nbErreursAttendues, string $message=""){
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($formation);
        $this->assertCount($nbErreursAttendues, $error, $message);
        //$this->assertCount( $error, $message);
    }

public function testNoValidDatePlublishedAT()  {
    $hier = (new \DateTime())->sub(new \DateInterval("P1D"));
    $this->assertErrors($this->getFormation()->setPublishedAt($hier), 1, 
      "La date de publication doit etre incorrect");
    
}
public function testValidDatePlublishedAT()  {
    $demain = ( new \DateTime()) ->add(new \DateInterval("P1D"));
    $this->assertErrors($this->getFormation()->setPublishedAt($demain), 0, 
      "La date de publication doit etre correct");
    
}

public function testAddNotValidateFormationPuslihedDate (){
    self::bootKernel();
    $formationadd =  new Formation();
    $validator = self::getContainer()->get(ValidatorInterface::class);
    $error = $validator->validate($formationadd,null,['add_formation']);

    


    $repository = $this->recupRepository();
    $nbFormation = $repository->count([]);
    $hier = (new \DateTime())->sub(new \DateInterval("P1D"));
   
    $formationadd->setTitle("TestFormationAddNot");
    $formationadd->setPublishedAt($hier);
    $repository->add($formationadd,true);
    $this->assertCount(2, $error, 'La validation de publishedAt a échoué');
   
    $this->assertEquals($nbFormation+1 , $repository->count([]),
     "L'ajout de la formation impossible car contrainte date antérieure pas appliqué");


   
 }

public function testAddValidateFormationPuslihedDate (){
   $repository = $this->recupRepository();
   $demain = ( new \DateTime()) ->add(new \DateInterval("P1D"));
   $formationadd =  new Formation();
   $formationadd->setTitle("TestFormationAdd");
   $formationadd->setPublishedAt($demain);
   $nbFormation = $repository->count([]);
   $repository->add($formationadd,true);
   $this->assertNotNull($formationadd, "L'instance de Formation ne doit pas être nulle");
   $this->assertEquals($nbFormation+1 , $repository->count([]),
    "L'ajout de la formation possible car contrainte date antérieure pas appliqué");


}





   





}