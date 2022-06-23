<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        $user = Auth::user()->getData()->include('ticket');

        dd($user->toArray());

        return view('invoice.index');
    }
}
