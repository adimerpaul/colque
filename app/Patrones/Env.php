<?php

namespace App\Patrones;

abstract class Env
{
    const url = "https://pilotosdfdsfssiatservicios.impuestos.gob.bo/v2/";
    const urlQr = "https://pilotosiat.impuestos.gob.bo/consulta/";
//    const url = "https://siatrest.impuestos.gob.bo/v2/";
//    const urlQr = "https://siat.impuestos.gob.bo/consulta/";

    const urlServerDbf = "http://192.168.1.124/selasisBancoImpuestos/api/index.php/";

    const nit = 370883022;
    const razonSocial = "COLQUECHACA MINING LTDA.";
        const login = "lukomekp";

        const password = "5232823Bo";
    const municipio = "Oruro";
    const telefono = "67200160";
    const direccion = "AVENIDA MARIA BARZOLA ENTRE CORIHUAYRA Y CELESTINO GUTIERREZ NRO. S\/N ZONA\/BARRIO: ESTE";
    const ciudad = "Oruro";
    const zona = "Este";

    const codigoAmbiente = 2; //1: produccion, 2: pruebas
    const codigoModalidad = 1; //1: ELECTRONICA EN LINEA 2: COMPUTARIZADA EN LINEA
    //const codigoSistema = "773964CCB845811293A6CFE";
    const codigoSistema = "7C233BEA4210BD4AE7D2CFE";
    const codigoSucursal = 0; //sucursal: 0,1,m

    const codigoProductoSin = 99100; // FALTA consultar
    const codigoProductoSinLibre = 99100; // FALTA consultar

    const unidadMedida = 58; //Unidad de servicio
    const unidadMedidaDescripcion = 'UNIDAD (SERVICIOS)'; //Unidad de servicio
    const unidadMedidaOtro = 62; //Unidad de otro ingreso
    const unidadMedidakILOGRAMO = 22; //Unidad de otro ingreso
    const unidadMedidaOtroDescripcion = 'OTRO'; //Unidad de otro ingreso

    const codigoExcepcion = 1; // enviar cero (0) o nulo y uno (1) cuando se autorice el registro.

    const codigoMetodoPago = 1;
    const codigoMoneda = 1;
    const codigoMonedaDolar = 2;
    const cantidad = 1;

    const carpetaBackups = "C:\impuestos/";
    const cafc = '1136CE62378D';  //facturacion fuera de linea
}
