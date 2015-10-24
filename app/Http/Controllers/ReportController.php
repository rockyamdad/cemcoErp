<?php namespace App\Http\Controllers;

use App\Category;
use App\Party;
use App\Product;
use App\ProformaInvoice;
use App\Report;
use App\Search;
use App\Stock;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\JsonResponse;

class ReportController extends Controller{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getIndex()
    {
       var_dump("ss");exit;
        return view('Parties.list',compact('parties'));
    }
    public function getStocks()
    {
        return view('Reports.stockReportSearch');
    }
    public function postReportResult()
    {
        $date1 = Input::get('from_date');
        $date2 = Input::get('to_date');
        $report = new Report();
        $results = $report->getStockReport($date1,$date2);
        return view('Reports.stockReport',compact('results'))
            ->with('date1',$date1)
            ->with('date2',$date2);
    }

    public function getPrint($date1,$date2)
    {
        $startDate = date('Y/m/d',strtotime($date1));
        $endDate = date('Y/m/d',strtotime($date2));
        $report = new Report();
        $results = $report->getStockReport($startDate,$endDate);
        return view('Reports.stockReportPrint',compact('results'))
            ->with('date1',$startDate)
            ->with('date2',$endDate);
    }


}