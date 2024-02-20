@if(! is_null($venta->url_documento))

    <iframe src="{{ asset("documents/ventas/" . $venta->url_documento.'?='.date('dmYHis')) }}" frameborder="0"
            style="height: 800px; width: 100%"></iframe>

@endif
