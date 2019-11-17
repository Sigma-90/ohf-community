<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;

abstract class BaseExport implements WithTitle, ShouldAutoSize, WithEvents
{
    use Exportable, DefaultFormatting;

    private $orientation = 'portrait';
    private $margins = null;
    private $fitToWidth = 0;
    private $fitToHeight = 0;

    public function setOrientation(string $orientation) {
        $this->orientation = $orientation;
    }

    public function setMargins(float $margins) {
        $this->margins = $margins;
    }

    public function setFitToWidth(float $fitToWidth) {
        $this->fitToWidth = $fitToWidth;
    }

    public function setFitToHeight(float $fitToHeight) {
        $this->fitToHeight = $fitToHeight;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function(BeforeExport $event) {
                $spreadsheet = $event->writer->getDelegate();
                $this->setupSpreadsheet($spreadsheet);
            },
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $this->setupPage($sheet);
                $this->setupView($sheet);
                $this->applyStyles($sheet);
            },
        ];
    }
  
}
