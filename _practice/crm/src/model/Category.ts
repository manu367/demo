export interface CategoryProps {
    categoryId: number;
    categoryName: string;
    categoryOrder: number;
    creating_date: string;
    ip: string;
    update_by: string;
    updated_date: string;
    isActive: boolean;
    isAccess: boolean;
}

class Category {
    private _categoryId: number;
    private _categoryName: string;
    private _categoryOrder: number;
    private _creating_date: string;
    private _ip: string;
    private _update_by: string;
    private _updated_date: string;
    private _isActive: boolean;
    private _isAccess: boolean;

    constructor(props: CategoryProps) {
        this._categoryId = props.categoryId;
        this._categoryName = props.categoryName;
        this._categoryOrder = props.categoryOrder;
        this._creating_date = props.creating_date;
        this._ip = props.ip;
        this._update_by = props.update_by;
        this._updated_date = props.updated_date;
        this._isActive = props.isActive;
        this._isAccess = props.isAccess;
    }

    get categoryId(): number {
        return this._categoryId;
    }

    set categoryId(value: number) {
        this._categoryId = value;
    }

    get categoryName(): string {
        return this._categoryName;
    }

    set categoryName(value: string) {
        this._categoryName = value;
    }

    get categoryOrder(): number {
        return this._categoryOrder;
    }

    set categoryOrder(value: number) {
        this._categoryOrder = value;
    }

    get creating_date(): string {
        return this._creating_date;
    }

    set creating_date(value: string) {
        this._creating_date = value;
    }

    get ip(): string {
        return this._ip;
    }

    set ip(value: string) {
        this._ip = value;
    }

    get update_by(): string {
        return this._update_by;
    }

    set update_by(value: string) {
        this._update_by = value;
    }

    get updated_date(): string {
        return this._updated_date;
    }

    set updated_date(value: string) {
        this._updated_date = value;
    }

    get isActive(): boolean {
        return this._isActive;
    }

    set isActive(value: boolean) {
        this._isActive = value;
    }

    get isAccess(): boolean {
        return this._isAccess;
    }

    set isAccess(value: boolean) {
        this._isAccess = value;
    }
}