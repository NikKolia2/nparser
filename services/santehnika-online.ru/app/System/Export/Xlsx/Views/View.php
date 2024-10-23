<?php

namespace App\System\Export\Xlsx\Views;

use ReflectionClass;
use App\Services\HelperService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\System\Export\Xlsx\Views\Rows\Row;
use App\System\Export\Xlsx\Views\Rows\Rows;
use App\System\Export\Xlsx\Views\Columns\Column;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\System\Export\Xlsx\Views\SettingData\SettingData;
use App\System\Export\Xlsx\Views\Actions\ActionsCollection;
use App\System\Export\Xlsx\Views\Columns\Actions\EnumRunTime;


class View
{
    protected Spreadsheet $spreadsheet;
    protected ConfigView $config;
    protected SettingData $settingData;
    protected Rows $headerData;
    protected int $countWorksheets = 0;

    protected ActionsCollection $actions;
    
    public function __construct(
        array $data
    ){
        $this->spreadsheet = new Spreadsheet();
        $this->config = ConfigView::parse($data["config"]);
        $this->settingData = new SettingData(...$data["data"]);
        $this->initActions();
    }

    public function getHeaderRows():Rows{
        $rows = $this->settingData->header->rows;

        $newRows = new Rows();
        for($i = 0; $i < $rows->length; $i++){
            $row = $rows->item($i);
            $newRow = new Row();
            
            for($j = 0; $j < $row->columns->length; $j++){
                $column = $row->columns->item($j);
                $newColumn = new Column($column->value, $column->actions);
                $newRow->addColumn($newColumn);
                //$newColumn->runActions(EnumRunTime::BEFORE_GENREATE_BODY);
            }

            $newRows->add($newRow);
        }

        return $newRows;
    }

    public function getBodyRows(array $data, Rows $settingRows):Rows{
        $newRows = new Rows();
        $columns =  $settingRows->item(0)->columns;
     
        foreach($data as $item){
            $newRow = new Row();

            for($i = 0; $i < $columns->length; $i++){
                $column = $columns->item($i);
                if(isset($item[$column->value])){
                   $value = $item[$column->value];
                }else{
                    $value = '';//$column->value;
                }

                $newColumn = new Column($value, $column->actions);
                $newRow->addColumn($newColumn);
                //$newColumn->runActions(EnumRunTime::GENERATE_BODY);
            }

            $newRows->add($newRow);
        }

        return $newRows;
    }

    public function createSheet(string $name):Worksheet{
        $this->spreadsheet->createSheet();
        $this->spreadsheet->setActiveSheetIndex($this->countWorksheets);
        $this->countWorksheets++;

        $worksheet = $this->spreadsheet->getActiveSheet();
        $worksheet->setTitle($name);

        return $worksheet;
    }

    protected function getDataBody(int $limitQuery = -1):array{
        return $this->config->builderData->execute($limitQuery = -1);
    }

    public function execute():Spreadsheet {
        $rows = $this->getHeaderRows();
        $settingRows = $this->settingData->body->rows;
      
        if($this->config->limitQuery != -1){
            do {
                $dataBody = $this->getDataBody($this->config->limitQuery);
                $rows->merge($this->getBodyRows($dataBody, $settingRows));
            }while(!empty($dataBody));
        }else{
            $dataBody = $this->getDataBody($this->config->limitQuery);
         
            $rows->merge($this->getBodyRows($dataBody, $settingRows));
        }      
        
        $rows->executeActionsByRunTime(EnumRunTime::AFTER_GENERATE_BODY);
        $worksheet = $this->createSheet($this->config->name);

        for($i = 0; $i < $rows->length; $i++){
            $row = $rows->item($i);
            $columns = $row->columns;
            $indexRow = $i + 1;
            for($j = 0; $j < $columns->length; $j++){
                $indexColumn = $j + 1;
                $column = $columns->item($j);
                $worksheet->setCellValue([$indexColumn,$indexRow], $column->value);
            }
        }

        return $this->spreadsheet;
    }

    public function initActions(){
        $userCollection = new ActionsCollection();
        if(!empty($this->config->actionsPath)){
            HelperService::getClassFiles($this->config->actionsPath, function($cls) use ($userCollection){
                if($userCollection->instanceof($cls)){
                    $userCollection->add($cls);
                }
            });
        }
        
        $collection = $this->getSystemActions();
        $collection->merge($userCollection);
     
        $this->actions = $collection;
    }

    public function getSystemActions():ActionsCollection{
        $collection = new ActionsCollection(view:$this::class);
        
        HelperService::getClassFiles(realpath(__DIR__."/Actions"), function($cls) use ($collection){
            if($collection->instanceof($cls)){
                $collection->add($cls);
            }
        });
     
        return $collection;
    }
}
