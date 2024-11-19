"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const db_1 = __importDefault(require("../db"));
class ProcessRepository {
    getNewProcesses(limit) {
        let query = 'SELECT * FROM `process` WHERE status_id=1 ORDER BY position ASC LIMIT 0,' + limit;
        return new Promise((resolve, reject) => {
            db_1.default.query(query, (err, res) => {
                if (err)
                    reject(err);
                else
                    resolve(res);
            });
        });
    }
    setStatusWaitParsingAndFlagDownloaded(urls) {
        let q = '("' + urls.join('","') + '")';
        let query = 'UPDATE `process` SET `status_id` = 5, `is_downloaded` = 1 WHERE `url` IN ' + q;
        return new Promise((resolve, reject) => {
            db_1.default.query(query, (err, res) => {
                if (err)
                    reject(err);
                else
                    resolve(res.affectedRows);
            });
        });
    }
    setStatusNewProcessAndUpdatePosition(urls) {
        let q = '("' + urls.join('","') + '")';
        let query = 'UPDATE `process` SET `status_id` = 1, `position`=`position`+1 WHERE `url` IN ' + q;
        return new Promise((resolve, reject) => {
            db_1.default.query(query, (err, res) => {
                if (err)
                    reject(err);
                else
                    resolve(res.affectedRows);
            });
        });
    }
    setStatusDownloading(urls) {
        let q = '("' + urls.join('","') + '")';
        let query = 'UPDATE `process` SET `status_id` = 2 WHERE `url` IN ' + q;
        return new Promise((resolve, reject) => {
            db_1.default.query(query, (err, res) => {
                if (err)
                    reject(err);
                else
                    resolve(res.affectedRows);
            });
        });
    }
}
exports.default = new ProcessRepository();
