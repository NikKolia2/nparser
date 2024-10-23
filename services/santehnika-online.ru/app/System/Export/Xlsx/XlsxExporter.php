<?php

namespace App\System\Export\Xlsx;
use App\System\Export\ConfigExporter;
use App\System\Export\AbstractExporter;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\System\Export\Xlsx\Views\XlsxExporterViews;

class XlsxExporter extends AbstractExporter
{
    public array $data;


    public function __construct(array $data, ConfigXlsxExport|ConfigExporter $config){
        parent::__construct($data, $config);
    }

    public function execute(): bool{
        $spreadsheets = [];
        foreach($this->data as $key => $item){
            if(isset($item["view"])){
                if(is_string($item["view"])){
                    $view = XlsxExporterViews::from($item["view"]);
                }else{
                    $view = $item["view"];
                }
            }else{
                $view = XlsxExporterViews::view;
            }

            $view = new ($view->getClass())($item['data']);
            $spreadsheets[] = $view->execute();
        }
        
        $pathToSave = $this->config->storagePath."test.xlsx";
        $writer = new Xlsx($spreadsheets[0]);
        $writer->save($pathToSave);

        return true;
    }
}
