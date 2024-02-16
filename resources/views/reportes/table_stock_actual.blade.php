<div class="table-responsive ">
    <table style="border: 1px solid black;" class="table table-striped" id="materiales-table">
        <thead>
        <tr>
            <th colspan="5" style="text-align: center; border: 0px white !important">COLQUECHACA MINING LTDA.
                <br>REPORTE DE STOCK ACTUAL
                <br>
                FECHA: {{date('d/m/Y')}}
                <br>
            </th>
        </tr>
        <tr>
            <th style=" border: 1px solid black;">#</th>
            <th style=" border: 1px solid black;">Lote Compra</th>
            <th style=" border: 1px solid black;">Lote Venta</th>
            <th style=" border: 1px solid black;">PNS</th>
            <th style=" border: 1px solid black;">VNV</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $row = 1;
        ?>
        @foreach($materiales as $material)
            <tr>
                <td style=" border: 1px solid black;">{{ ($row++) }}</td>
                <td style=" border: 1px solid black;">{{ $material->lote_compra }}</td>
                <td style=" border: 1px solid black;"> @if($material->lote_de_venta!='/'){{ $material->lote_de_venta }}@endif </td>
                <td style=" border: 1px solid black;">{{ $material->peso_seco }}</td>
                <td style=" border: 1px solid black;">{{ $material->neto_venta }}</td>
            </tr>
        @endforeach

        @foreach($ingenios as $ingenio)
            <tr>
                <td style=" border: 1px solid black;">{{ ($row++) }}</td>
                <td style=" border: 1px solid black;">{{ $ingenio->nombre }}</td>
                <td style=" border: 1px solid black;"> {{ $ingenio->venta->lote }} </td>
                <td style=" border: 1px solid black;">{{ $ingenio->peso_neto_seco }}</td>
                <td style=" border: 1px solid black;">{{ $ingenio->valor_neto_venta }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<script>

    function exportarAExcel() {
        var nombreArchivo = 'reporteStock'+"_{{date('Y-m-d')}}"+"_{{$producto_id}}"+'.xls';
        var htmlExport = jQuery('#materiales-table').prop('outerHTML')
        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");

        //other browser not tested on IE 11
        // If Internet Explorer
        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
            jQuery('body').append(" <iframe id=\"iframeExport\" style=\"display:none\"></iframe>");
            iframeExport.document.open("txt/html", "replace");
            iframeExport.document.write(htmlExport);
            iframeExport.document.close();
            iframeExport.focus();
            sa = iframeExport.document.execCommand("SaveAs", true, nombreArchivo);
        } else {
            var link = document.createElement('a');

            document.body.appendChild(link); // Firefox requires the link to be in the body
            link.download = nombreArchivo;
            link.href = 'data:application/vnd.ms-excel,' + escape(htmlExport);
            link.click();
            document.body.removeChild(link);
        }
    }

</script>
