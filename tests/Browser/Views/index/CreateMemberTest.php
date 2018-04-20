<?php

namespace Tests\Browser\Views\index;

use App\Models\Member;
use Facebook\WebDriver\WebDriverBy;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateMemberTest extends DuskTestCase
{
  use DatabaseMigrations;

    private $url = 'http://localhost:8000';
    /**
     * Un visiteur arrive sur la page /
     * - Il saisie son email
     * - Il soumet le formulaire
     * - Un message l'informe qu'il va de recevoir un email "index.success"
     *
     * 2 Points
     */
     public function testAddEmail()
         {
             $this->browse(function (Browser $browser) {
                 $browser->visit($this->url . '/')
                     ->assertMissing('.alert')
                     ->value('input[email=email]', 'john.doe@domain.tld')
                     ->press(__('emails.index.send'))
                     ->assertPathIs('/')
                     ->assertSee(__('success_message'))
                     ->assertVisible('.alert-success');

                 $elements = $browser->driver->findElements(WebDriverBy::cssSelector('ul li'));

                 $this->assertCount(1, $elements);
               });
  }

    /**
     * Un visiteur arrive sur la page /
     * - Il saisie son email (déjà existant en base de donnée)
     * - Il soumet le formulaire
     * - Un message l'informe qu'il va de recevoir un email "index.success"
     *
     * 2 Points
     */
     public function testEmailAlreadyExists()
         {
             factory(Member::class)->create([
                 Member::EMAIL => 'john.doe@domain.tld'
             ]);

             $this->browse(function (Browser $browser) {
                 $browser->visit($this->url . '/')
                     ->assertMissing('.alert')
                     ->value('input[email=email]', 'john.doe@domain.tld')
                     ->press(__('emails.index.send'))
                     ->assertPathIs('/')
                     ->assertSee(__('already_exist'))
                     ->assertVisible('.alert-warning');
             });
         }
    /**
     * Un visiteur arrive sur la page /
     * - Il ne saisi pas d'email
     * - Il soumet le formulaire
     * - Un message l'informe qu'il doit saisir son email
     *
     * 2 Points
     */

public function testCreateRequiredFields()
  {
      $this->browse(function (Browser $browser) {
          $browser->visit($this->url . '/')
              ->assertMissing('.alert')
              ->press(__('emails.index.send'))
              ->assertPathIs('/')
              ->assertSee(__('required_fields'))
              ->assertVisible('.alert-warning');
      });
  }
}
