@if(! is_null($formularioLiquidacion->url_documento))

    <iframe src="{{ asset("documents/" . $formularioLiquidacion->url_documento.'?='.date('dmYHis')) }}" frameborder="0"
            style="height: 800px; width: 100%"></iframe>

@endif
