<?php

namespace App\Models\Process;
use App\Models\Model;
use HemiFrame\Lib\SQLBuilder\Query;

class ProcessNameModel extends Model
{
    protected $table = "process_name";


    public function getCollectionByStatusProcess($limit = 10, $offset = 0){
        $query = $this->query()->from($this->table, "g")->select(["pid"]);
        $query->where("status_id", 1);
        $query->limit($limit);

        return $query->fetchArrays();
    }

    public function getQuerySuccessProcess($page = 1, $limit = 10): Query{
        $qpc = $this->query()->select("COUNT(*)")->from(ProcessModel::getTableName(), "pc");
        $qpc->leftJoin(ProcessGroupModel::getTableName(), "pg1", "pc.url=pg1.url");
        $qpc->leftJoin(ProcessNameModel::getTableName(), "pn1", "pn1.pid=pg1.pid");
        $qpc->where("pc.status_id", 4);
        $qpc->andWhere("pn1.parsing_success", 1, "!=");
        $qpc->andWhere("pn1.pid = pg2.pid");
        $qpc->groupBy("pg1.pid");
        $qpc->paginationLimit($page, $limit);

        $query = $this->query()->select("pn.pid")->from(
            $this->query()->select([
                "pg2.pid", 
                "(COUNT(*) = (".$qpc->getQueryString(true).")) as success"
            ])->from(ProcessModel::getTableName(), "pc1")
            ->leftJoin(ProcessGroupModel::getTableName(), "pg2", "pc1.url=pg2.url")
            ->leftJoin(ProcessNameModel::getTableName(), "pn2", "pn2.pid=pg2.pid")
            ->where("pn2.parsing_success", 1, "!=")
            ->groupBy("pg2.pid")
            ->paginationLimit($page, $limit),
            "pn"
        );
        $query->where("pn.success", 1);

        return $query;
    }

    public function updateProcessStatusOnSuccess($page = 1, $limit = 10):int{
        $query =$this->query()->update($this->table)->set([
            "parsing_success" => 1,
            "parsing_update_at" => date("Y-m-d H:i:s")
        ]);
        $query->where("pid IN (".$this->getQuerySuccessProcess($page, $limit)->getQueryString(true).")");
        $stm = $query->execute();

        return $stm->rowCount();
    }

    public function getCountProcessesNoSuccess():int{
        $result = $this->query()->select("COUNT(*) as total")->from($this->table)->where("parsing_success", 1, "!=")->fetchFirstArray();
        if(!empty($result)){
            return (int)$result["total"];
        }

        return 0;
    }
}
