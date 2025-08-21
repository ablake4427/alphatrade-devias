export interface Admin {
    id: number;
    email: string;
    role: string;
}
export interface JwtPayload {
    admin_id: number;
    role: string;
    perms: string[];
}
