@extends('layouts.printables')

@section('head')
    <style>
        span {font-size: 5.5em; font-weight: bold; margin-top: -1.5rem;}
        hr {border: 1px solid #000; width: 100%;}
    </style>
@endsection

@section('content')
    <div class="row justify-content-center">
        @if(!empty($printable))
            @foreach($printable as $tagNumber)
                <div class="col-7 d-flex flex-column align-items-center">
                        <?php
                        $barcode = new \Picqer\Barcode\BarcodeGeneratorSVG();
                        echo $barcode->getBarcode($tagNumber,$barcode::TYPE_CODE_128, 4, 230);
                        ?>
                    <span>{{$tagNumber}}</span>
                    <hr class="mb-5">
                </div>
            @endforeach
        @endif
    </div>
@endsection

@section('scripts')
    <script>
    </script>
@endsection
