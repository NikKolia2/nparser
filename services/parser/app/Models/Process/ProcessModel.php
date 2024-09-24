<?php

namespace App\Models\Process;

use App\Models\Model;
use Override;
use PhpOffice\PhpSpreadsheet\Calculation\LookupRef\Offset;

class ProcessModel extends Model
{
    protected $table = "process";

    #[Override]
    public static function create($data):int|null
    {
        try {
            $processNameModel = new ProcessNameModel();
            $processNameModel->create([
                "pid" => $data["processCode"],
                "name" => $data["processName"],
            ]);

            $model = new static();
            foreach ($data['urls'] as $url) {
                $query = $model->query()->insertInto($model->table)->set([
                    'url' => $url,
                    'type_id' => $data['type_id'],
                    'created_at' => $data['created_at']
                ]);
                $query->onDuplicateKeyUpdate('url=:url')->setVar('url', $url);
                $stm = $query->execute();
            }

            $processGroupModel = new ProcessGroupModel();
            $processGroupModel->create([
                "processCode" => $data["processCode"],
                "urls" => $data["urls"]
            ]);

            if($stm->rowCount()){
                return (int)$query->getLastInsertId();
            }
        } catch (\Throwable) {
        }

        return null;
    }

    public function getCollectionUrlsNewProcess(int $limit = 100): array {
        $query = $this->query()->from($this->table)->select("url");
        $query->where("status_id", 1);
        $query->limit($limit);
  
        return $query->fetchColumn();
    }

    public function getCollectionUrlsParserProcess(int $limit = 100): array {
        $query = $this->query()->from($this->table, "p")->select(["url", "namespace"]);
        $query->leftJoin(ProcessTypeModel::getTableName(), "ptype", "ptype.id=p.type_id");
        $query->where("status_id", 5);
        $query->limit($limit);

        return $query->fetchObjects();
    }

    public function setStatusDownloading(array $urls):bool {
        $query = $this->query()->update($this->table);
        $query->set("status_id", 2);
        $query->where("url", $urls);

        $stm = $query->execute();

        return (bool)$stm->rowCount();
    }

    public function setStatusWaitParsingAndFlagDownloaded(array $urls):bool {
        $query = $this->query()->update($this->table);
        $query->set(["status_id" => 5, "is_downloaded" => 1]);
        $query->where("url", $urls);

        $stm = $query->execute();

        return (bool)$stm->rowCount();
    }

    public function setStatusParsing(array $urls):bool {
        $query = $this->query()->update($this->table);
        $query->set(["status_id" => 3]);
        $query->where("url", $urls);

        $stm = $query->execute();

        return (bool)$stm->rowCount();
    }

    public function setStatusSuccess(string $url):bool {
        $query = $this->query()->update($this->table);
        $query->set(["status_id" => 4]);
        $query->where("url", $url);

        $stm = $query->execute();

        return (bool)$stm->rowCount();
    }
}
