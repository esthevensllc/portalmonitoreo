@extends('errors.layout')

@php
	$error_number = 'Pagina en mantenimiento';
	$headingIcon = true;
@endphp

@section('title')
Pagina en mantenimiento.
@endsection

@section('description')
	@php
	  $default_error_message = "El equipo de desarrollo ya ha sido notificado y se encuentra trabajando.";
	@endphp
	{!! $default_error_message !!}
	<br>
	Por favor <a href="javascript:history.back()" '="">regresar</a> luego
@endsection