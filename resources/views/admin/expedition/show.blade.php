@extends('admin.layouts.app')

@push('styles')
@endpush
@section('content')
    @component('admin.components.contentheader')
        @slot('title')
            Pedido
        @endslot
        @slot('small')
            Visualizando o pedido: #COD{{$order->id}}
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
                        <a href="{{route('orders-expedition')}}" onclick="localStorage.clear();" class="btn btn-sm bg-aqua margin-r-5 btn-flat"><i class="fa fa-list"></i> Listagem de Pedidos Para Expedição</a>
                        @if($order->status_id != statusOrder('canceled'))
                            <a target="_blank" href="{{route('order-timeline-show', [base64_encode($order->id)])}}" class="btn btn-sm bg-green"><i class="fa fa-align-left"></i> Timeline do pedido</a>
                        @endif

                        @if($order->status_id <= statusOrder("finished"))
                            <a href="{{route('orders-expedition-conference', [base64_encode($order->id)])}}" onclick="localStorage.clear();" class="btn btn-sm bg-fuchsia margin-r-5 btn-flat"><i class="fa fa-check-circle-o"></i> Conferir Pedido</a>
                        @endif

                        @if($order->status_id == statusOrder("expedition"))
                            <a href="{{route('orders-expedition-confirm', [base64_encode($order->id)])}}" onclick="localStorage.clear();" class="btn btn-sm bg-yellow margin-r-5 btn-flat"><i class="fa fa-list"></i> Entregar Pedido</a>
                        @endif
                        <a href="{{route('order-print', [base64_encode($order->id)])}}" onclick="localStorage.clear();" target="_blank" class="btn btn-sm bg-gray margin-r-5 btn-flat"><i class="fa fa-print"></i> Imprimir</a>
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
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" id="quote">
                                                <input type="hidden" name="id" value="{{$order->id}}">
                                                @include('admin.expedition.partials.show')
                                        </div>
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
@endpush
