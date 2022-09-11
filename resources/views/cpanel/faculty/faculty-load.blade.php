@extends('layouts.app')

@section('content')
    <faculty-load prop-acad-years='@json($acadYears)'>
    </faculty-load>
@endsection
