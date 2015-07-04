<div class="tab-pane fade " id="otherCost">

    {!!Form::open(array('url' => '/saveOtherCost', 'method' => 'post', 'class'=>'form-horizontal',
    'id'=>'imports_other_costs_form'))!!}
    <div class="form-body">
        <div class="alert alert-danger display-hide">
            <button data-close="alert" class="close"></button>
            You have some form errors. Please check below.
        </div>
        <div class="alert alert-success display-hide">
            <button data-close="alert" class="close"></button>
            Your form validation is successful!
        </div>

        {!! Form::hidden('import_id',$imports->id) !!}

        <div class="form-group">
            {!! HTML::decode(Form::label('dollar_to_bd_rate','Dollar Rate',array('class' => 'control-label col-md-3')))
            !!}
            <div class="col-md-4">
                {!!Form::text('dollar_to_bd_rate',null,array('placeholder' => 'Present Dollar Rate', 'class' =>
                'form-control','id' => 'dollar_to_bd_rate'))!!}
            </div>
        </div>
        <div class="form-group">
            {!! HTML::decode(Form::label('tt_charge','Tt Charge',array('class' => 'control-label col-md-3'))) !!}
            <div class="col-md-4">
                {!!Form::text('tt_charge',null,array('placeholder' => 'Tt Charge', 'class' => 'form-control','id' =>
                'tt_charge'))!!}
            </div>
        </div>


        <div class="form-actions fluid">
            <div class="col-md-offset-3 col-md-9">
                {!!Form::button('Save',array('type' => 'submit','class' => 'btn green','id' => 'save'))!!}
                {!!Form::button('Cancel',array('type'=>'reset', 'class' => 'btn default','id' => 'cancel'))!!}

            </div>
        </div>

        <!-- END FORM-->
    </div>
    {!!Form::close()!!}
</div>