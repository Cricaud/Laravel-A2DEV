<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Models\Member;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
  public function setUp()
    {
        parent::setUp();
        exec('php artisan migrate:refresh');
    }
    /**
     * Vérifie que la page index retourne un code 200
     *
     * 2 Points
     */
     public function test_Index200_Success()
     {
         // Arrange
         // Act
         $response = $this->get('/');
         // Assert
         $response->assertStatus(200);
     }

    /**
     * Vérifie que la redirection est bien / après l'ajout d'un email
     * Doit retourner le message suivant index.success
     *
     * 2 Points
     */
     public function testCreate_Addmember_EmailSuccessCase()
     {
         // Arrange
         // Act
         $response = $this->post('/emails/create', [
             Member::EMAIL => 'john.doe@domain.tld'
         ]);

         // Assert
         $response->assertRedirect('/');
         $response->assertStatus(302);
         $response->assertSessionHas('alert', [
             'message' => 'success_message',
             'type' => 'success'
         ]);
     }

    /**
     * Vérifie que l'ajout d'un email (john.doe@domain.tld) existant redirige vers /
     * Doit retourner les message suivant index.success
     * Il ne doit y avoir q'un email en base de donnée
     *
     * 2 Points
     */
    public function testIndex_AddMember_EmailAlreadyExistCase()
    {
        // Arrange
        $email = 'john.doe@domain.tld';

        factory(Member::class)->create([
            Member::EMAIL => $email
        ]);
        // Act
        // Assert
    }
    public function testIndex_AddMember_SuccessMessage()
   {
       // Arrange
       $email = 'john.doe@domain.tld';
       // Act
       $response = $this->post('/emails/create', [
           Member::EMAIL => $email
       ]);
       // Assert
       $response->assertRedirect('/');
       $response->assertSessionHas('alert', [
           'message' => 'success_message',
           'type' => 'success'
       ]);
   }

    /**
     * Vérifie que l'ajout d'un email vide retourne une erreur 500
     *
     * 2 Points
     */
     public function testIndex_RequiredFields_ErrorCase()
  {
    $response = $this->post('/', [
            Member::EMAIL => ''
        ]);

        // Assert
        $response->assertStatus(500);

  }
}
