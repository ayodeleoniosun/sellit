@extends('email.layout')

@section('content')
    <p> Hi {{ $first_name }}, </p>
    <p> We received a request to reset the password for your account. </p>
    <p> Click the button below to reset your password </p>

    <p><a href="{{ $url }}">
            <button class="btn btn-primary"> Reset Password</button>
        </a></p>

    <p> or copy and paste the URL into your browser </p>

    <p><a href="{{ $url }}"> {{ $url }} </a></p><br/>

    <p> Ensure that the password is reset within the next ten minutes, else, the link becomes invalid. </p>

    Thanks.
@endsection

