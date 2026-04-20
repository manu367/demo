import pool from "../db/db.js";
import type { CategoryProps } from "../model/Category.js";
import type { ResultSetHeader, RowDataPacket } from "mysql2/promise";

class CategoryRepo {

    // CREATE
    async create(category: CategoryProps) {
        const query = `
            INSERT INTO categories
            (categoryName, categoryOrder, creating_date, ip, update_by, updated_date, isActive, isAccess)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        `;

        const values = [
            category.categoryName,
            category.categoryOrder,
            category.creating_date || new Date().toISOString(),
            category.ip || "",
            category.update_by || "",
            category.updated_date || new Date().toISOString(),
            category.isActive,
            category.isAccess
        ];

        return 0;
    }

    // READ ALL
    async getAll(): Promise<CategoryProps[]> {
        return [];
    }

    // READ BY ID
    async getById(id: number): Promise<CategoryProps | null> {

        return null;
    }

    // UPDATE
    async update(id: number, category: Partial<CategoryProps>): Promise<boolean> {
        return true;
    }

    // DELETE
    async delete(id: number): Promise<boolean> {

        return true;
    }
}

export default new CategoryRepo();