@extends('baseLayout')
@section('content')
<div class="table-toolbar">
    <div class="btn-group">
        @if((!$pi->isEmpty()) or (!$bankCost->isEmpty()) or (!$cnfCost->isEmpty()) or (!$otherCost->isEmpty()))
        <a class="btn blue" href="{{ URL::to('imports/editcost',$pi[0]['import_id']) }}">Edit Import Cost&nbsp;&nbsp;<i
                class="fa fa-pencil"></i></a>
        @endif
        <a class="btn green" href="{{ URL::to('imports/index') }}"> Import List&nbsp;&nbsp;<i
                class="fa fa-pencil"></i></a>

    </div>

</div>
<div class="row">

        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box light-grey">

                <div class="portlet-body">
                    <h4>Import Details List</h4>
                    <table class="table table-striped table-bordered table-hover" id="imports_detail_table">
                        <thead>
                        <tr>
                            <th class="table-checkbox"><input type="checkbox" class="group-checkable"
                                                              data-set="#user_table .checkboxes"/></th>
                            <th>Import Number</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Total Booking Price</th>
                            <th>Total CFR Price</th>
                            <th>Created By</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($imports as $importt )
                        <tr class="odd gradeX">
                            <td><input type="checkbox" class="checkboxes" value="1"/></td>
                            <td>{{$importt->import_id}}</td>
                            <td>{{$importt->product_id}}</td>
                            <td>{{$importt->quantity}}</td>
                            <td>{{$importt->total_booking_price}}</td>
                            <td>{{$importt->total_cfr_price}}</td>
                            <td>{{$importt->created_by}}</td>
                            <td>
                            </td>
                        </tr>
                        @endforeach

                        </tbody>
                    </table>

                    <br>

                    <h4>Proforma Invoice</h4>
                    <table class="table table-striped table-bordered table-hover" id="imports_pi_table">
                        <thead>
                        <tr>
                            <th class="table-checkbox"><input type="checkbox" class="group-checkable"
                                                              data-set="#user_table .checkboxes"/></th>
                            <th>Invoice No</th>
                            <th>Beneficiary Name</th>
                            <th>terms</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!$pi->isEmpty())
                        <tr class="odd gradeX">
                            <td><input type="checkbox" class="checkboxes" value="1"/></td>
                            <td>{{$pi[0]['invoice_no']}}</td>
                            <td>{{$pi[0]['beneficiary_name']}}</td>
                            <td>{{$pi[0]['terms']}}</td>

                        </tr>
                        @endif

                        </tbody>
                    </table>
                    <br>

                    <h4>Bank Cost</h4>

                    <table class="table table-striped table-bordered table-hover" id="imports_bank_cost_table">
                        <thead>
                        <tr>
                            <th class="table-checkbox"><input type="checkbox" class="group-checkable"
                                                              data-set="#user_table .checkboxes"/></th>
                            <th>LC Number</th>
                            <th>Bank Name</th>
                            <th>LC Commission</th>
                            <th>Vat Commission</th>
                            <th>Stamp Charge</th>
                            <th>Swift Charge</th>
                            <th>LCA Charge</th>
                            <th>Insurance Charge</th>
                            <th>Bank Service Charge</th>
                            <th>Others Charge</th>

                        </tr>
                        </thead>
                        <tbody>
                    @if(!$bankCost->isEmpty())
                        <tr class="odd gradeX">
                            <td><input type="checkbox" class="checkboxes" value="1"/></td>
                            <td>{{$bankCost[0]['lc_no']}}</td>
                            <td>{{$bankCost[0]['bank_name']}}</td>
                            <td>{{$bankCost[0]['lc_commission_charge']}}</td>
                            <td>{{$bankCost[0]['vat_commission']}}</td>
                            <td>{{$bankCost[0]['stamp_charge']}}</td>
                            <td>{{$bankCost[0]['swift_charge']}}</td>
                            <td>{{$bankCost[0]['lca_charge']}}</td>
                            <td>{{$bankCost[0]['insurance_charge']}}</td>
                            <td>{{$bankCost[0]['bank_service_charge']}}</td>
                            <td>{{$bankCost[0]['others_charge']}}</td>


                        </tr>
                        @endif


                        </tbody>
                    </table>
                    <br>

                    <h4>CNF Cost</h4>
                    <table class="table table-striped table-bordered table-hover" id="imports_cnf_cost_table">
                        <thead>
                        <tr>
                            <th class="table-checkbox"><input type="checkbox" class="group-checkable"
                                                              data-set="#user_table .checkboxes"/></th>
                            <th>Agent Name</th>
                            <th>Bill No</th>
                            <th>Bank No</th>
                            <th>Association Fee</th>
                            <th>PO Cash</th>
                            <th>Port Charge</th>
                            <th>Shipping Charge</th>
                            <th>NOC Charge</th>
                            <th>Labour Charge</th>
                            <th>Jetty Charge</th>
                            <th>Agency Commission</th>
                            <th>Others Charge</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!$cnfCost->isEmpty())
                        <tr class="odd gradeX">
                            <td><input type="checkbox" class="checkboxes" value="1"/></td>
                            <td>{{$cnfCost[0]['clearing_agent_name']}}</td>
                            <td>{{$cnfCost[0]['bill_no']}}</td>
                            <td>{{$cnfCost[0]['bank_no']}}</td>
                            <td>{{$cnfCost[0]['association_fee']}}</td>
                            <td>{{$cnfCost[0]['po_cash']}}</td>
                            <td>{{$cnfCost[0]['port_charge']}}</td>
                            <td>{{$cnfCost[0]['shipping_charge']}}</td>
                            <td>{{$cnfCost[0]['noc_charge']}}</td>
                            <td>{{$cnfCost[0]['labour_charge']}}</td>
                            <td>{{$cnfCost[0]['jetty_charge']}}</td>
                            <td>{{$cnfCost[0]['agency_commission']}}</td>
                            <td>{{$cnfCost[0]['others_charge']}}</td>

                        </tr>
                        @endif

                        </tbody>
                    </table>

                    <br>

                    <h4>OtherCost</h4>
                    <table class="table table-striped table-bordered table-hover" id="imports_otherCost_table">
                        <thead>
                        <tr>
                            <th class="table-checkbox"><input type="checkbox" class="group-checkable"
                                                              data-set="#user_table .checkboxes"/></th>
                            <th>Dollar to Bdt Rate</th>
                            <th>Tt Charge</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!$otherCost->isEmpty())
                        <tr class="odd gradeX">
                            <td><input type="checkbox" class="checkboxes" value="1"/></td>
                            <td>{{$pi[0]['dollar_to_bd_rate']}}</td>
                            <td>{{$pi[0]['tt_charge']}}</td>

                        </tr>
                        @endif

                        </tbody>
                    </table>

                </div>

            </div>

            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>
@stop




