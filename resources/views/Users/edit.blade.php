@extends('baseLayout')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2_metro.css') }}"/>

@stop
@section('content')
<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            User Section
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{URL::to('dashboard')}}">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>

            <li>Edit User</li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>

<div class="col-md-16">
    <!-- BEGIN VALIDATION STATES-->
    <div class="portlet box purple">
        <div class="portlet-title">
            <div class="caption"><i class="fa fa-reorder"></i>Edit User</div>
            <div class="actions">
                <a class="btn dark" href="{{ URL::to('list') }}">Users List</a>
            </div>
        </div>
        <div class="portlet-body form">
            @if ($errors->has())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif
            {!!Form::model($userdata,array('action' => array('UserController@putCheckupdate', $userdata->id), 'method'
            => 'PUT', 'class'=>'form-horizontal', 'id'=>'user_form'))!!}
            <div class="form-body">
                <div class="alert alert-danger display-hide">
                    <button data-close="alert" class="close"></button>
                    You have some form errors. Please check below.
                </div>
                <div class="alert alert-success display-hide">
                    <button data-close="alert" class="close"></button>
                    Your form validation is successful!
                </div>
                <div class="form-group">
                    {!! HTML::decode(Form::label('name','Name',array('class' => 'control-label col-md-3'))) !!}
                    <div class="col-md-4">
                        {!!Form::text('name',null,array('placeholder' => 'Name', 'class' => 'form-control','id' =>
                        'name'))!!}
                    </div>
                </div>
                <div class="form-group">
                    {!!HTML::decode(Form::label('username','Username<span class="required">*</span>',array('class' =>
                    'control-label col-md-3')))!!}
                    <div class="col-md-4">
                            {!!Form::text('username',null,array('placeholder' => 'Username', 'class' =>
                            'form-control','id' => 'username'))!!}
                    </div>
                </div>
                <div class="form-group">
                    {!!HTML::decode(Form::label('email','Email<span class="required">*</span>',array('class' =>
                    'control-label col-md-3')))!!}
                    <div class="col-md-4">
                            {!!Form::text('email',null,array('placeholder' => 'Email', 'class' => 'form-control','id' =>
                            'email'))!!}
                    </div>
                </div>

                <div class="form-group">
                    {!! HTML::decode(Form::label('phone','Phone',array('class' => 'control-label col-md-3'))) !!}
                    <div class="col-md-4">
                        {!!Form::text('phone',null,array('placeholder' => 'Phone', 'class' => 'form-control','id' =>
                        'phone'))!!}
                    </div>
                </div>

                <div class="form-group">
                    {!!HTML::decode(Form::label('address','Address',array('class' => 'control-label col-md-3')))!!}
                    <div class="col-md-4">
                        {!!Form::textarea('address',null,array('class' => 'form-control','id' => 'address',
                        'rows'=>'3'))!!}
                    </div>
                </div>
                @if(Session::get('user_role')== 'admin' )
                <div class="form-group">
                    {!!HTML::decode(Form::label('role','Role<span class="required">*</span>',array('class' =>
                    'control-label col-md-3')))!!}
                    <div class="col-md-4">
                        {!! Form::select('role',[null=>'Please Select Role']+array('admin' => 'Admin', 'manager' =>
                        'Manager','user'=>'User'),$userdata->role, array('class'=>'form-control'))!!}
                    </div>
                </div>
                @endif
                <div class="form-group">
                    {!!HTML::decode(Form::label('sex','Gender',array('class' => 'control-label col-md-3')))!!}
                    <div class="col-md-4">
                        {!! Form::select('sex',[null=>'Please Select Gender']+ array('m' => 'Male', 'f' => 'Female'),$userdata->gender,
                        array('class'=>'form-control'))!!}
                    </div>
                </div>

                <div class="form-group">
                    {!!HTML::decode(Form::label('branch_id','User Branch<span class="required">*</span>',array('class'
                    => 'control-label col-md-3')))!!}
                    <div class="col-md-4">
                        {!!Form::select('branch_id',$branchAll,$userdata->branch_id, array('class'=>'form-control') )!!}
                    </div>
                </div>
            </div>
            <div class="form-actions fluid">
                <div class="col-md-offset-3 col-md-9">
                    {!!Form::button('Save',array('type' => 'submit','class' => 'btn green','id' => 'save'))!!}
                    {!!Form::button('Cancel',array('type'=>'reset', 'class' => 'btn default','id' => 'cancel'))!!}

                </div>
            </div>
            {!!Form::close()!!}
            <!-- END FORM-->
        </div>
    </div>
    <!-- END VALIDATION STATES-->
</div>
@stop

@section('javascript')

{!! HTML::script('js/users.js') !!}

@stop
