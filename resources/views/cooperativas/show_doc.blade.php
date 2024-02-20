@if(! is_null($cooperativa->url_documento))

    <iframe src="{{ asset("documents/cooperativas/" . $cooperativa->url_documento) }}" frameborder="0"
            style="height: 800px; width: 100%"></iframe>

@endif
