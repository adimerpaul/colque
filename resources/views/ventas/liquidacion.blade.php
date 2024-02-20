<div>
    <input type="button" value="Genera una tabla" onclick="genera_tabla()">
    <table id="table">
        <thead>
        <tr>
            <th>uno</th>
            <th>dos</th>
        </tr>
        </thead>
        <tbody id="tbody" name="body">
        <tr>
            <td>
                <div contenteditable>I'm editable</div>
            </td>
            <td>
                <div contenteditable>I'm also editable</div>
            </td>
        </tr>
        <tr>
            <td>I'm not editable</td>
        </tr>
        </tbody>
        <tfoot id="foot"></tfoot>
    </table>
</div>
<script type="application/javascript">
    function genera_tabla() {
        var tabla = document.getElementById("table");
        var tblFoot = document.getElementById("foot");

        // Crea las celdas
        for (var i = 0; i < 2; i++) {
            // Crea las hileras de la tabla
            var hilera = document.createElement("tr");
            for (var j = 0; j < 1; j++) {
                // Crea un elemento <td> y un nodo de texto, haz que el nodo de
                // texto sea el contenido de <td>, ubica el elemento <td> al final
                // de la hilera de la tabla
                var celda = document.createElement("td");
                var textoCelda = document.createTextNode("celda en la hilera " + i + ", columna " + j);
                celda.appendChild(textoCelda);
                celda.setAttribute('contenteditable', true);
                hilera.appendChild(celda);
            }
            // agrega la hilera al final de la tabla (al final del elemento tblbody)
            tblFoot.appendChild(hilera);
        }
        // posiciona el <tbody> debajo del elemento <table>
        tabla.appendChild(tblFoot);
        // modifica el atributo "border" de la tabla y lo fija a "2";
         tabla.setAttribute("border", "2");
    }
</script>
