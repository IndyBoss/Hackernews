@extends('layouts.app')

@section('content')

<?php

$title = $_POST['title'];
$url = $POST['url'];


echo $title . $url;


?>


@endsection