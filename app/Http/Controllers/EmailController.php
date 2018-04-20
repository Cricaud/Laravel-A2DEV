<?php

namespace App\Http\Controllers;

use App\Exceptions\EmailAlreadyExistException;
use App\Exceptions\EmailNotFoundException;
use App\Models\Email;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    /**
     * @var EmailService
     */
    private $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Il faut retourner à la vue un liste email
     */
    public function index(Request $request)
    {
        $emails = $this->emailService->lists();

        return view('tasks.index', [
            'emails' => $emails,
            'alert' => $request->session()->get('alert')
        ]);
    }

    public function create(Request $request)
    {
        $values = $request->only([
            Task::EMAIL
        ]);



        if($this->validForm($values, [
            Task::EMAIL => 'required'
        ])) {
            return redirect()->action('EmailController@index')
                ->with('alert', [
                    'message' => 'required_fields',
                    'type' => 'warning'
                ]);
        }

        try {
            $this->emailService->create($values[Task::EMAIL]);
        } catch (EmailAlreadyExistException $e) {
            return redirect()->action('EmailController@index')
                ->with('alert', [
                    'message' => 'already_exist',
                    'type' => 'warning'
                ]);
        }

        return redirect()->action('EmailController@index')
            ->with('alert', [
                'message' => 'success_message',
                'type' => 'success'
            ]);
    }

    public function update(Request $request)
    {
        $values = $request->only([
            Task::EMAIL,
            'id'
        ]);

        if($this->validForm($values, [
            Task::EMAIL => 'required',
            'id' => 'required'
        ])) {
            return redirect()->action('EmailController@index')
                ->with('alert', [
                    'message' => 'required_fields',
                    'type' => 'warning'
                ]);;
        }

        try {
            $this->emailService->update((int)$values['id'], $values[Member::EMAIL]);
        } catch (EmailAlreadyExistException $e) {
            return redirect()->action('EmailController@index')->with('alert', [
                'message' => 'email_already_exists',
                'type' => 'warning'
            ]);
        } catch (EmailNotFoundException $e) {
            abort(500);
        }

        return redirect()->action('EmailController@index')->with('alert', [
            'message' => 'update_success',
            'type' => 'success'
        ]);
    }

    public function delete($id)
    {
        try {
            $this->emailService->delete((int)$id);
        } catch (EmailNotFoundException $e) {
            abort(500);
        }

        return redirect()->action('EmailController@index')->with('alert', [
            'message' => 'delete_success',
            'type' => 'success'
        ]);
    }

    private function validForm(array $values, array $rules): bool
    {
        $validator = Validator::make($values, $rules);

        if($validator->fails()) {
            return true;
        }

        return false;
    }
}
