<?php

namespace Infrastructure\Libraries;

use Barryvdh\DomPDF\Facade\Pdf as BasePdf;
use Illuminate\Support\Facades\App;

class Pdf
{

    public function download($form, $data)
    {
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML(view($form)
            ->with(["data" => $data])
            ->render())->setPaper([0,0,850,598], 'landscape')->setWarnings(false);
        return $pdf->download($data['file_name']);
    }

    public function stream($form, $data)
    {
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML(view($form)
            ->with(["data" => $data])
            ->render())->setPaper([0,0,850,598], 'landscape')->setWarnings(false);
        return $pdf->stream();
    }
}
