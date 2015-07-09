<?php namespace App\Http\Controllers;

use App\BankCost;
use App\Branch;
use App\CnfCost;
use App\Import;
use App\ImportDetail;
use App\OtherCost;
use App\Product;
use App\ProformaInvoice;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\JsonResponse;

class SettingsController extends Controller{

    public function getIndex()
    {
        //$settings = Settings::all();
        return view('Settings.partyList');
    }
    public function getCreate()
    {
        return view('Settings.partyList');
    }

}