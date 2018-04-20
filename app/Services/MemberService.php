<?php

namespace App\Services;

use App\Exceptions\EmailAlreadyExistException;
use App\Exceptions\EmailNotFoundException;
use App\Models\Member;
use Illuminate\Database\Eloquent\Collection;

class MemberService
{
    /**
     * @var Member
     */
    private $member;

    public function __construct(Member $member)
    {
      $this->member = $member;
    }

    /**
         * Permet la récupération des membres et leurs emails
         *
         * @return Collection
         * @throws \Exception
         */
        public function emails(): Collection
        {
            return $this->member->all();
        }
    /**
     * Permet la création d'un nouvel email en base de donnée
     *
     * @param string
     * @throws EmailAlreadyExistException
     */
    public function create(string $email): void
    {
      // $taskMocked
        $result = $this->member->where([
            Member::EMAIL => $member
        ])->first();

        if (!is_null($result)) {
            throw new EmailAlreadyExistException();
        }

        $this->member->create([
            Member::EMAIL => $member
        ]);
      }

        /**
     * Permet la mise à jour d'un mail
     *
     * @param int $id
     * @param string $member
     * @throws \Exception
     */

    public function update(int $id, string $member)
    {
        $emailNotFoundResult = $this->email->where([
            'id' => $id
        ])->first();

        // Si l'id n'existe pas
        if (is_null($emailNotFoundResult)) {
            throw new EmailNotFoundException();
        }

        $emailFound = $this->email->where([
            Member::EMAIL => $member
        ])->first();

        // Si on trouve déjà un tâche à ce mail...
        if (!is_null($emailFound)
            && $emailFound['id'] !== $id) {
            throw new EmailAlreadyExistException();
        }

        $this->email->where([
            'id' => $id
        ])->update([
            Member::EMAIL => $member
        ]);
    }

    /**
     * Permet la suppression d'un mail
     *
     * @param int $id
     * @throws \Exception
     */
    public function delete(int $id)
    {
        // Je vérifie que ça existe
        $email = $this->email->where([
            'id' => $id
        ])->first();

        if (is_null($task)) {
            throw new EmailNotFoundException();
        }

        // Après je supprime
        $this->email->where([
            'id' => $id
        ])->delete();
    }
}
