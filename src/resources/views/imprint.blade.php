@extends('layouts.app')

@section('title', 'About')

@include('components.header-small')

@section('content')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h2>About</h2>
                    <p>
                        Some informations about you.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection