@extends('admin.layouts.app')

@push('styles')
@endpush
@section('content')
    @component('admin.components.contentheader')
        @slot('title')
            Pedido
        @endslot
        @slot('small')
            Editando o pedido: #{{$order->id}}
        @endslot
        @slot('link')
            Detalhes do pedido
        @endslot
    @endcomponent

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        @include('admin.order.partials.menu')
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                @include('admin.messages.messages_register')
                            </div>


                            <div class="col-md-12">
                                <div class="nav-tabs-custom">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs" role="tablist" id="tabs">
                                        <li role="presentation" class="active"><a href="#quote" aria-controls="quote" role="tab" data-toggle="tab">Detalhes do Pedido</a></li>
                                        <li role="presentation"><a href="#quote-item" aria-controls="quote-item" role="tab" data-toggle="tab">Itens do Pedido</a></li>
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" id="quote">
                                            <form action="{{route('order-update')}}" method="post" class="panels" id="formsubmit">
                                                <input type="hidden" name="id" value="{{$order->id}}">
                                                @include('admin.order.partials.form')
                                            </form>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="quote-item">@include('admin.order.partials.formitem')</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.editor').summernote({
                lang: 'pt-BR',
                height: 100,
                minHeight: 100,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['paragraph']]
                ]
            });

            //money
            masMoney();
            maskZipCode();
        });
    </script>
@endpush
