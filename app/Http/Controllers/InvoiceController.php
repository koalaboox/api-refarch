<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * InvoiceController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('api.errors');
    }

    /**
     * View all the invoices for the current user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $invoices = $request->user()->api->invoices();

        return view('invoices.index', compact('invoices'));
    }

    /**
     * View a single invoice for the current user.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $id)
    {
        $invoice = $request->user()->api->invoice($id);

        return view('invoices.show', compact('invoice'));
    }
}
