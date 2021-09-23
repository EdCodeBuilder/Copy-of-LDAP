<!-- START_{{$route['id']}} -->
@if($route['title'] != '')## {{ $route['title']}}
@else## {{$route['uri']}}@endif
@if($route['authenticated'])

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>@endif
@if($route['description'])

{!! $route['description'] !!}
@endif

> Solicitud de ejemplo:

@foreach($settings['languages'] as $language)
@include("apidoc::partials.example-requests.$language")

@endforeach

@if(in_array('GET',$route['methods']) || (isset($route['showresponse']) && $route['showresponse']))
@if(is_array($route['response']))
@foreach($route['response'] as $response)
> Respuesta de ejemplo ({{$response['status']}}):

```json
@if(is_object($response['content']) || is_array($response['content']))
{!! json_encode($response['content'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
@else
{!! json_encode(json_decode($response['content']), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
@endif
```
@endforeach
@else
> Respuesta de ejemplo:

```json
@if(is_object($route['response']) || is_array($route['response']))
{!! json_encode($route['response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
@else
{!! json_encode(json_decode($route['response']), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
@endif
```
@endif
@endif
@php
$validation = [
    'message'   => __('validation.handler.validation_failed'),
    'errors'    => [
        'atributo' => [
            __('validation.required'),
            '...'
        ],
        '...' => ['...']
    ]
];
$validation = json_encode($validation, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
$error = [
    'message' =>  'Mensaje de error. Ejemplo: '.__('validation.handler.unauthenticated'),
    'details' => 'Detalles del error (Si está disponible)',
    'code'  =>  "4xx - 5xx",
    'requested_at'  =>  now()->toIso8601String()
];
$error = json_encode($error, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
@endphp

> Respuesta de error de validación de datos formularios o parámetros:

```json
{!! $validation !!}
```

> Respuesta de error 4xx y 5xx:

```json
{!! $error !!}
```

### HTTP Request
@foreach($route['methods'] as $method)
`{{$method}} {{$route['uri']}}`

@endforeach
@if(count($route['bodyParameters']))
#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
@foreach($route['bodyParameters'] as $attribute => $parameter)
    {{$attribute}} | {{$parameter['type']}} | @if($parameter['required']) obligatorio @else opcional @endif | {!! $parameter['description'] !!}
@endforeach
@endif
@if(count($route['queryParameters']))
#### Query Parameters

Parámetro | Estado | Descripción
--------- | ------- | ------- | -----------
@foreach($route['queryParameters'] as $attribute => $parameter)
    {{$attribute}} | @if($parameter['required']) obligatorio @else opcional @endif | {!! $parameter['description'] !!}
@endforeach
@endif

<!-- END_{{$route['id']}} -->
