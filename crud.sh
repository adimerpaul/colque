#php artisan infyom:scaffold Empresa --fromTable --tableName=empresa --skip=migration,tests,api
#php artisan infyom:scaffold Personal --fromTable --tableName=personal --skip=migration,tests,api
#php artisan infyom:scaffold User --fromTable --tableName=usersselect --skip=migration,tests,api
#php artisan infyom:scaffold Acceso --fromTable --tableName=acceso --skip=migration,tests,api,views,view
#php artisan infyom:scaffold TipoCambio --fromTable --tableName=tipo_cambio --skip=migration,tests,api
#php artisan infyom:scaffold Material --fromTable --tableName=material --skip=migration,tests,api
#php artisan infyom:scaffold Ley --fromTable --tableName=ley --skip=migration,tests,api

#php artisan infyom:scaffold CotizacionOficial --fromTable --tableName=cotizacion_oficial --skip=migration,tests,api

# php artisan infyom:scaffold FormularioLiquidacion --fromTable --tableName=formulario_liquidacion --skip=migration,tests,api
# php artisan infyom:scaffold Producto              --fromTable --tableName=producto --skip=migration,tests,api
#php artisan infyom:scaffold TablaAcopiadora         --fromTable --tableName=tabla_acopiadora --skip=migration,tests,api
php artisan infyom:scaffold TablaAcopiadoraDetalle         --fromTable --tableName=tabla_acopiadora_detalle --skip=migration,tests,api
