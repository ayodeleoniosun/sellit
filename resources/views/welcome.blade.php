@extends('email.layout')

@section('content')
    <p> Hi {{ $first_name }}, </p>
    <p> Welcome to {{ $app_name }} </p>
    Thanks.
@endsection
