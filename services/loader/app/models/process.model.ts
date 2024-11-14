import { RowDataPacket } from "mysql2"

export default interface Process extends RowDataPacket {
  url: string;
  status_id: number,
  is_downloaded: number,
  type_id: number,
  create_at?: string
}