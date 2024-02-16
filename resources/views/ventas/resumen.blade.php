<table class="table table-bordered" style="background-color: #f9f9f9">
    <tr style="height: 20px">
        <td>
            <strong>Producto: </strong> @{{ venta.producto }}
        </td>

        <td >
            <strong>Documentos faltantes: </strong> @{{ venta.documento_que_falta }}
        </td>
    </tr>
    <tr style="height: 20px">

        <td >
            <strong>Fecha creación: </strong> @{{ getDateOnly(venta.created_at) }}
        </td>

        <td >
            <strong>Fecha despacho: </strong> @{{ venta.fecha_despacho!='' ? getDateOnly(venta.fecha_despacho):'' }}
        </td>


    </tr>
</table>
<hr style="margin-top: -15px">
