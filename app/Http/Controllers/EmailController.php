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

        return view('members.index', [
            'emails' => $emails,
            'alert' => $request->session()->get('alert')
        ]);
    }

    public function create(Request $request)
    {
        $values = $request->only([
            Member::EMAIL
        ]);



        if($this->validForm($values, [
            Member::EMAIL => 'required'
        ])) {
            return redirect()->action('EmailController@index')
                ->with('alert', [
                    'message' => 'required_fields',
                    'type' => 'warning'
                ]);
        }

        try {
            $this->emailService->create($values[Member::EMAIL]);
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
}
