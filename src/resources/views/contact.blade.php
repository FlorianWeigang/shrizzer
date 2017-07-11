@extends('layouts.app')

@section('title', 'Contact')

@include('components.header-small')

@section('content')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-xs-6 col-md-offset-2">
                    <h2>Contact</h2>
                    <p>
                        Just write a mail to: <a href="mailto:info@shrizzer.com">info@shrizzer.com</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection