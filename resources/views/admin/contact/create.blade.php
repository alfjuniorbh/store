@extends('admin.layouts.app')

@push('styles')
@endpush
@section('content')
    @component('admin.components.contentheader')
        @slot('title')
            Contato
        @endslot
        @slot('small')
            Novo contato
        @endslot
        @slot('link')
            Novo  contato
        @endslot
    @endcomponent

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        @include('admin.contact.partials.menu')
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                @include('admin.messages.messages_register')
                            </div>


                            <div class="col-xs-12 col-md-3">

                                <form action="{{route('contact-store')}}" method="post" id="formsubmit">
                                    <input type="hidden" name="formreset" id="formreset" value="reset">
                                    @include('admin.contact.partials.form')
                                </form>

                            </div>

                            <div class="col-xs-12 col-md-9">
                                @include('admin.contact.partials.tabs')
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection