<?php

return [
    'error'         =>  'No podemos realizar la consulta en este momento, por favor intente más tarde.',
    'invalid'       =>  'No se encuentra un certificado válido para este número de contrato',
    'invalid_code' =>   'El código de verificación ingresado no es válido.',
    'invalid_token' =>  'No se encuentra un certificado válido para este token :token',
    'valid_token'   =>  "Este certificado fue emitido originalmente en la fecha correspondiente a :date a nombre de :name con número de documento :document bajo el contrato :contract para el área de :area",
    'valid_trib'    =>  "Este certificado fue emitido originalmente en la fecha correspondiente a :date a nombre de :name con número de documento :document con información tributaria suministrada por el área de Contabilidad",
    'not_found'     =>  'No se encuentra el usuario con los parámetros establecidos.',
    'contract_date' =>  'La solicitud de expedición de Paz y Salvo debe realizarse desde el día de finalización de su contrato de prestación de servicios que es :date',
    'no_accounts'   =>  'Usuario sin cuenta de ORFEO Y sin Cuentas Institucionales',
    'only_email'    =>  'Usuario sin cuenta de ORFEO pero con cuenta institucional',
    'with_accounts' =>  'Usuario con cuenta de ORFEO y LDAP',
    'orfeo_exception' => 'Para generar el paz y salvo de sistemas debe tener sus bandejas de Orfeo en cero, actualmente cuenta con un radicado sin procesar.|Para generar el paz y salvo de sistemas debe tener sus bandejas de Orfeo en cero, actualmente cuenta con :count radicados sin procesar.'
];
