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

class ImportController extends Controller{

    public function getIndex()
    {
        $imports = Import::all();
        return view('Imports.list',compact('imports'));
    }
    public function getCreate()
    {
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        $products = new Product();
        $productAll = $products->getProductsDropDownForeign();
        $imports = new Import();
        $importAll = $imports->getImportsDropDown();

        return view('Imports.add',compact('branchAll'))
            ->with('productAll',$productAll)
            ->with('importAll',$importAll);
    }
    public function postSaveImport()
    {
        $ruless = array(
            'import_num' => 'required',
            'branch_id' => 'required',
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('imports/create')
                ->withErrors($validate);
        }
        else{
            $import = new Import();
            $this->setImportData($import);
            $import->save();
            Session::flash('message', 'Import has been Successfully Created.');
            return Redirect::to('imports/index');
        }
    }
    public function postSaveImportDetail()
    {
        $ruless = array(
            'import_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('imports/create')
                ->withErrors($validate);
        }
        else{
            $importDetail = new ImportDetail();
            $this->setImportDetailsData($importDetail);
            $importDetail->save();
            Session::flash('message', 'Import Details has been Successfully Created.');
            return Redirect::to('imports/index');
        }
    }
    public function postSaveBankCost()
    {
        $ruless = array(
            'lc_no' => 'required',
            'bank_name' => 'required',
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('imports/list')
                ->withErrors($validate);
        }
        else{
            $bankCost = new BankCost();
            $this->setBankCostData($bankCost);
            $bankCost->save();
            Session::flash('message', 'Bank Cost has been Successfully Created.');
            return Redirect::to('imports/index');
        }
    }
    public function postUpdateBankCost($id)
    {
        $ruless = array(
            'lc_no' => 'required',
            'bank_name' => 'required',
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('imports/editCost',$id)
                ->withErrors($validate);
        }
        else{
            $bankCost =  BankCost::find($id);
            $this->setBankCostData($bankCost);
            $bankCost->save();
            Session::flash('message', 'Bank Cost has been Successfully Updated.');
            return Redirect::to('imports/index');
        }
    }
    public function postSaveCnfCost()
    {
        $ruless = array(
            'clearing_agent_name' => 'required'
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('imports/list')
                ->withErrors($validate);
        }
        else{
            $cnfCost = new CnfCost();
            $this->setCnfCostData($cnfCost);
            $cnfCost->save();
            Session::flash('message', 'CNF Cost has been Successfully Created.');
            return Redirect::to('imports/index');
        }
    }
    public function postUpdateCnfCost($id)
    {
        $ruless = array(
            'clearing_agent_name' => 'required'
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('imports/editCost',$id)
                ->withErrors($validate);
        }
        else{
            $cnfCost = CnfCost::find($id);
            $this->setCnfCostData($cnfCost);
            $cnfCost->save();
            Session::flash('message', 'CNF Cost has been Successfully Updated.');
            return Redirect::to('imports/index');
        }
    }
    public function postProformaInvoice()
    {
        $ruless = array(
            'invoice_no' => 'required'
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('imports/index')
                ->withErrors($validate);
        }
        else{
            $pi = new ProformaInvoice();
            $this->setProformaInvoiceData($pi);
            $pi->save();
            Session::flash('message', 'Proforma Invoice has been Successfully Created.');
            return Redirect::to('imports/index');
        }
    }
    public function postUpdateProformaInvoice($id)
    {
        $ruless = array(
            'invoice_no' => 'required'
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('imports/editCost',$id)
                ->withErrors($validate);
        }
        else{
            $pi = ProformaInvoice::find($id);
            $this->setProformaInvoiceData($pi);
            $pi->save();
            Session::flash('message', 'Proforma Invoice has been Successfully Updated.');
            return Redirect::to('imports/index');
        }
    }
    public function postOtherCost()
    {
        $ruless = array(
            'tt_charge' => 'required',
            'dollar_to_bd_rate' => 'required'
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('imports/list')
                ->withErrors($validate);
        }
        else{
            $otherCost = new OtherCost();
            $this->setOtherCostData($otherCost);
            $otherCost->save();
            Session::flash('message', 'Others Cost has been Successfully Created.');
            return Redirect::to('imports/index');
        }
    }
    public function postUpdateOtherCost($id)
    {
        $ruless = array(
            'tt_charge' => 'required',
            'dollar_to_bd_rate' => 'required'
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('imports/editCost',$id)
                ->withErrors($validate);
        }
        else{
            $otherCost = OtherCost::find($id);
            $this->setOtherCostData($otherCost);
            $otherCost->save();
            Session::flash('message', 'Others Cost has been Successfully Updated.');
            return Redirect::to('imports/index');
        }
    }
    public function getDetails($id)
    {
        $imports  = ImportDetail::where('import_id','=',$id)->get();
        $bankCost = BankCost::where('import_id','=',$id)->get();
        $cnfCost  = CnfCost::where('import_id','=',$id)->get();
        $pi       =  ProformaInvoice::where('import_id','=',$id)->get();
        $otherCost     = OtherCost::where('import_id','=',$id)->get();
        return view('Imports.details',compact('imports'))
            ->with('bankCost',$bankCost)
            ->with('pi',$pi)
            ->with('otherCost',$otherCost)
            ->with('cnfCost',$cnfCost);
    }
    public function getLandingcost($id)
    {
        $importDetails = new ImportDetail();

        $imports = $importDetails->getLandingCostData($id);
        $totalBankCost = BankCost::where('import_id','=',$id)->get();
        $totalCnfCost  = CnfCost::where('import_id','=',$id)->get();
        $ttCharge      = OtherCost::where('import_id','=',$id)->get();
        return view('Imports.landingCost',compact('imports'))
            ->with('totalBankCost',$totalBankCost)
            ->with('totalCnfCost',$totalCnfCost)
            ->with('ttCharge',$ttCharge);
    }
    public function getCosts($id)
    {
        $imports = Import::find($id);
        $importBankCost = BankCost::where('import_id','=',$id)->get();
        $importCnfCost = CnfCost::where('import_id','=',$id)->get();
        $importOtherCost = OtherCost::where('import_id','=',$id)->get();
        $importProformaInvoice = ProformaInvoice::where('import_id','=',$id)->get();
        return view('Imports.costs',compact('imports'))
            ->with('importBankCost',$importBankCost)
            ->with('importCnfCost',$importCnfCost)
            ->with('importOtherCost',$importOtherCost)
            ->with('importProformaInvoice',$importProformaInvoice);
    }
    public function getEdit($id)
    {
        $branches = new Branch();
        $branchAll = $branches->getBranchesDropDown();
        $import = Import::find($id);
        return view('Imports.edit',compact('import'))
            ->with('branchAll',$branchAll);
    }
    public function getEditdetails($id)
    {
        $import = ImportDetail::find($id);
        $products = new Product();
        $productAll = $products->getProductsDropDownForeign();
        $imports = new Import();
        $importAll = $imports->getImportsDropDown();

        return view('Imports.editDetails',compact('import'))
            ->with('productAll',$productAll)
            ->with('importAll',$importAll);
    }
    public function getEditcost($id)
    {
        $imports = Import::find($id);
        $proformaInvoice = ProformaInvoice::where('import_id','=',$id)->get();
        $bankCost    = BankCost::where('import_id','=',$id)->get();
        $cnfCost     = CnfCost::where('import_id','=',$id)->get();
        $otherCost   = OtherCost::where('import_id','=',$id)->get();
        return view('Imports.editCost',compact('imports'))
            ->with('importProformaInvoice',$proformaInvoice)
            ->with('importBankCost',$bankCost)
            ->with('importCnfCost',$cnfCost)
            ->with('importOtherCost',$otherCost);
    }
    public function postUpdate($id)
    {
        $ruless = array(
            'import_num' => 'required',
            'branch_id' => 'required',
        );
        $validate = Validator::make(Input::all(), $ruless);

        if($validate->fails())
        {
            return Redirect::to('imports/edit/'.$id)
                ->withErrors($validate);
        }
        else{
            $import = Import::find($id);
            $this->setImportData($import);
            $import->save();
            Session::flash('message', 'Import has been Successfully Updated.');
            return Redirect::to('imports/index');
        }
    }
    public function getChangeStatus($status,$id)
    {
        $import = Import::find($id);
        if($import['status'] == $status) {
            $import->status = ($status == 'Activate' ? 'Deactivate': 'Activate');
            $import->save();

        }
        return new JsonResponse(array(
            'id' => $import['id'],
            'status' => $import['status']
        ));
    }
    private function setImportData($import)
    {
        $import->import_num = Input::get('import_num');
        $import->branch_id = Input::get('branch_id');
        $import->consignment_name = Input::get('consignment_name');
        $import->description = Input::get('description');
        $import->created_by = Session::get('user_id');
        $import->status = "Activate";
    }
    private function setImportDetailsData($importDetail)
    {
        $importDetail->import_id = Input::get('import_id');
        $importDetail->product_id = Input::get('product_id');
        $importDetail->quantity = Input::get('quantity');
        $importDetail->total_booking_price = Input::get('total_booking_price');
        $importDetail->total_cfr_price = Input::get('total_cfr_price');
        $importDetail->created_by = Session::get('user_id');
    }
    private function setProformaInvoiceData($pi)
    {
        $pi->invoice_no = Input::get('invoice_no');
        $pi->beneficiary_name = Input::get('beneficiary_name');
        $pi->terms = Input::get('terms');
        $pi->import_id = Input::get('import_id');
    }
    private function setOtherCostData($otherCost)
    {
        $otherCost->dollar_to_bd_rate = Input::get('dollar_to_bd_rate');
        $otherCost->tt_charge = Input::get('tt_charge');
        $otherCost->import_id = Input::get('import_id');
    }
    private function setBankCostData($bankCost)
    {
        $bankCost->lc_no = Input::get('lc_no');
        $bankCost->bank_name = Input::get('bank_name');
        $bankCost->lc_commission_charge = Input::get('lc_commission_charge');
        $bankCost->vat_commission = Input::get('vat_commission');
        $bankCost->stamp_charge = Input::get('stamp_charge');
        $bankCost->swift_charge = Input::get('swift_charge');
        $bankCost->lca_charge = Input::get('lca_charge');
        $bankCost->insurance_charge = Input::get('insurance_charge');
        $bankCost->bank_service_charge = Input::get('bank_service_charge');
        $bankCost->others_charge = Input::get('others_charge');
        $bankCost->import_id = Input::get('import_id');

        $totalBankCost = Input::get('lc_commission_charge') + Input::get('vat_commission') + Input::get('stamp_charge') +  Input::get('swift_charge') +
                         Input::get('lca_charge') + Input::get('insurance_charge')+   Input::get('bank_service_charge') + Input::get('others_charge');

        $bankCost->total_bank_cost = $totalBankCost;
    }
    private function setCnfCostData($cnfCost)
    {
        $cnfCost->clearing_agent_name = Input::get('clearing_agent_name');
        $cnfCost->bill_no = Input::get('bill_no');
        $cnfCost->bank_no = Input::get('bank_no');
        $cnfCost->association_fee = Input::get('association_fee');
        $cnfCost->po_cash = Input::get('po_cash');
        $cnfCost->port_charge = Input::get('port_charge');
        $cnfCost->shipping_charge = Input::get('shipping_charge');
        $cnfCost->noc_charge = Input::get('noc_charge');
        $cnfCost->labour_charge = Input::get('labour_charge');
        $cnfCost->jetty_charge = Input::get('jetty_charge');
        $cnfCost->agency_commission = Input::get('agency_commission');
        $cnfCost->others_charge = Input::get('others_charge');
        $cnfCost->import_id = Input::get('import_id');

        $totalCnfCost = Input::get('association_fee') + Input::get('port_charge') + Input::get('shipping_charge') +  Input::get('noc_charge') +
                        Input::get('labour_charge') + Input::get('jetty_charge')+   Input::get('agency_commission') + Input::get('others_charge');

        $cnfCost->total_cnf_cost = $totalCnfCost;
    }
}