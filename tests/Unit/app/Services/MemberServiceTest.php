<?php

namespace Tests\Unit\app\Services;

use App\Exceptions\EmailAlreadyExistException;
use App\Exceptions\EmailNotFoundException;
use App\Models\Email;
use App\Services\EmailService;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MemberServiceTest extends TestCase
{
  private $emailMocked;

  /**
   * La méthode "setUp" est appelée à chaque excecution de test
   */
  public function setUp()
  {
      parent::setUp();

      $this->emailMocked = \Mockery::mock(Email::class);
  }
    /**
     * Doit ajouter un nouvel email en base de donnée
     * Doit aussi vérifier qu'un email est en cours d'envoi dans le gestionnaire de queue
     *
     * 2 Points
     */

     public function Email(){

}


    /**
     * Doit retourner une exception de type EmailAlreadyExistException
     * si l'email est déjà existant
     *
     * 2 Points
     */

     public function testCreate_ExpectException_ExceptionCase()
    {
        // Arrange
        $email = 'First task';

        // SELECT email FROM tasks WHERE email = 'john.doe@domain.tldk' LIMIT 1;
        $this->emailMocked->shouldReceive('where')
            ->once()
            ->with([
                Member::EMAIL => $email
            ])
            ->andReturn($this->taskMocked);

        $this->emailMocked->shouldReceive('first')
            ->once()
            ->andReturn(new Email());

        $this->emailMocked->shouldReceive('create')
            ->times(0);

        $emailService = new EmailService($this->EmailMocked);

        // Assert
        $this->expectException(EmailAlreadyExistException::class);

        // Act
        $emailService->create($email);
    }
}
