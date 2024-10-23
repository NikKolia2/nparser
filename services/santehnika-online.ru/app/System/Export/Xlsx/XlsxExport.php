<?php

namespace App\System\Export\Xlsx;
use App\Export\AbstractExport;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class XlsxExport extends AbstractExport
{
    protected ConfigXlsxExport $config;
    protected Spreadsheet $spreadsheet;
    public const SHEET_PRODUCT = "product";
    protected array $sheets = [];
    protected int $countSheets = 0;
    protected $rowDataIndex = 1;
    public function __construct(
        ConfigXlsxExport|array $config
    ){
        if(is_array($config)){
            $this->config = new ConfigXlsxExport(...$config);
        }else {
            $this->config = $config;
        }

        $this->spreadsheet = new Spreadsheet();
        $this->createWorksheet(static::SHEET_PRODUCT, $this->config->templateProduct);
    }

    protected function createWorksheet(string $key, Worksheet $template){
        $this->spreadsheet->createSheet();
        $this->sheets[$key] = $this->countSheets;
        $this->spreadsheet->setActiveSheetIndex($this->countSheets);
        $worksheet = $this->spreadsheet->getActiveSheet();
        $this->countSheets++;

        $worksheet->setTitle($template->getTitle());

        $highestRow = $template->getHighestDataRow();
        $highestColumn = $template->getHighestDataColumn(); // e.g 'F'
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5

        for ($row = 1; $row <= $highestRow; ++$row) {
            for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                $cell = $template->getCell([$col, $row]);
                $worksheet->setCellValue([$col, $row], $cell->getValue());
                if($cell->getMergeRange())
                    $worksheet->mergeCells($cell->getMergeRange());
            }
            $this->rowDataIndex++;
        }
    }

    public function execute(string $filename, array $data): bool{
        $worksheetIndex = $this->getWorksheetIndex(static::SHEET_PRODUCT);
        $this->spreadsheet->setActiveSheetIndex($worksheetIndex);
        $worksheet = $this->spreadsheet->getActiveSheet();

        $rows = $data[static::SHEET_PRODUCT];

        foreach($rows as $columns){
            foreach($columns as $columnIndex => $column){
                $worksheet->setCellValue([$columnIndex+1, $this->rowDataIndex], $column);
            }
            
            $this->rowDataIndex++;
        }
      
        $pathToSave = $this->config->storagePath.$filename.".".$this->config->ext;
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($pathToSave);

        return true;
    }

    public function getWorksheetIndex(string $key):int{
        return $this->sheets[$key];
    }
}
