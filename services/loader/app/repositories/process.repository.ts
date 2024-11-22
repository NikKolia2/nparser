import connection from "../db";
import Process from "../models/process.model";
import { OkPacket } from "mysql2";

interface IProcessRepository {
  getNewProcesses(limit: number): Promise<Process[]>;
  setStatusWaitParsingAndFlagDownloaded(urls:Array<string>):Promise<number>;
  setStatusDownloading(urls:Array<string>):Promise<number>
}

class ProcessRepository implements IProcessRepository { 
    getNewProcesses(limit: number): Promise<Process[]> {
        let query = 'SELECT * FROM `process` WHERE status_id=1 and position <= 5 ORDER BY position ASC LIMIT 0,'+limit
        
        return new Promise((resolve, reject) => {
            connection.query<Process[]>(query, (err, res) => {
              if (err) reject(err);
              else resolve(res);
            });
        });
    }
    setStatusWaitParsingAndFlagDownloaded(urls:Array<any>):Promise<number>{
        let q:string = '("' + urls.join('","') + '")'
        let query = 'UPDATE `process` SET `status_id` = 5, `is_downloaded` = 1 WHERE `url` IN ' + q;

        return new Promise((resolve, reject) => {
            connection.query<OkPacket>(query, (err, res) => {
              if (err) reject(err);
              else resolve(res.affectedRows);
            });
        });
    }

    setStatusNewProcessAndUpdatePosition(urls:Array<any>):Promise<number>{
      let q:string = '("' + urls.join('","') + '")'
      let query = 'UPDATE `process` SET `status_id` = 1, `position`=`position`+1 WHERE `url` IN ' + q;

      return new Promise((resolve, reject) => {
          connection.query<OkPacket>(query, (err, res) => {
            if (err) reject(err);
            else resolve(res.affectedRows);
          });
      });
    }

    setStatusDownloading(urls:Array<any>):Promise<number>{
        let q:string = '("' + urls.join('","') + '")'
        let query = 'UPDATE `process` SET `status_id` = 2 WHERE `url` IN ' + q;

        return new Promise((resolve, reject) => {
            connection.query<OkPacket>(query, (err, res) => {
              if (err) reject(err);
              else resolve(res.affectedRows);
            });
        });
    }
}

export default new ProcessRepository();