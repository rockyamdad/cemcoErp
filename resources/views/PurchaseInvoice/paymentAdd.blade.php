<div class="modal-dialog shape">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h3>Make Payment</h3>
        </div>
        <div class="modal-body">
            {!!Form::open(array('url' => '/saveMake', 'method' => 'post', 'class'=>'form-horizontal payment_form',
                     ))!!}
            <div class="form-body">

                <div class="alert alert-danger display-hide">
                    <button data-close="alert" class="close"></button>
                    You have some form errors. Please check below.
                </div>


                <div class="portlet-body form" id="testt">
                    <!-- BEGIN FORM-->
                    <div class="form-body">
                        <div class="form-group">
                            {!!HTML::decode(Form::label('account_category_id','Account Category<span class="required">*</span>',array('class'
                            => 'control-label col-md-4')))!!}
                            <div class="col-md-7">
                                {!!Form::select('account_category_id',[null=>'Please Select Account Category'] +$accountCategoriesAll,'null', array('class'=>'form-control') )!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Choose Category Name<span class="required">*</span></label>

                            <div class="col-md-7">
                                <select id="account_name_id" name="account_name_id" class="form-control">
                                    <option value="">Select Category Name</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            {!!HTML::decode(Form::label('payment_method','Payment Method',array('class' => 'control-label col-md-4')))!!}
                            <div class="col-md-7">
                                {!! Form::select('payment_method',[null=>'Please Select Payment Method'] + array('Cash' => 'On Cash', 'Check' => 'On Check'),'null',
                                array('class'=>'form-control'))!!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!!HTML::decode(Form::label('amount','Amount<span class="required">*</span>',array('class' =>
                                           'control-label col-md-4')))!!}
                            <div class="col-md-7">
                                {!!Form::text('amount',null,array('placeholder' => 'Amount', 'class' =>
                                               'form-control','id'=>'amount'))!!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!!HTML::decode(Form::label('remarks','Remarks',array('class' =>
                                          'control-label col-md-4')))!!}
                            <div class="col-md-7">
                                {!!Form::text('remarks',null,array('placeholder' => 'Remarks', 'class' =>
                                'form-control','id'=>'remarks'))!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-5">
                                {!!Form::hidden('invoice_id',null,array('class' => 'form-control ','id'=>'invoice_id'))!!}
                            </div>
                        </div>
                        <div class="form-actions fluid">
                            <div class="col-md-offset-3 col-md-9">
                                {!!Form::button('Save',array('type' => 'submit','class' => 'btn blue','id' => 'savePayment'))!!}
                                <button type="button" data-dismiss="modal" class="btn">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            {!!Form::close()!!}

            {{--          <div class="modal-footer">
                           <button type="button" data-dismiss="modal" class="btn">Close</button>
                           <button type="button" class="btn blue">Save changes</button>
                       </div>--}}

        </div>
    </div>
</div>