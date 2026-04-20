import mysql from "mysql2/promise";
import type { Pool } from "mysql2/promise";

const pool: Pool = mysql.createPool({
    host: "localhost",
    user: "root",
    password: "",
    database: "crm",
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0
});

export default pool;