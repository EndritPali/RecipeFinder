import { User } from './user';

export interface LoginPayload {
    email: string;
    password: string;
}

export interface RegisterPayload {
    username: string;
    email: string;
    password: string;
}

export interface AuthResponse {
    token: string;
    user: User;
}

export interface AuthContextType {
    user: User | null;
    loginMutation: any;
    registerMutation: any;
    logout: () => void;
    isAuthenticated: boolean;
    isLoadingAuth: boolean;
    fetchPendingRequests: () => Promise<number>;
}

export type AccountModalMode = 'login' | 'register';

export interface AccountModalProps {
    open: boolean;
    onOk: () => void;
    onCancel: () => void;
    mode?: AccountModalMode;
}

export type LoginFormValues = {
    email: string;
    password: string;
    username?: string;
}

export type PasswordResetPayload = {
    username: string;
    email: string;
    last_password: string;
};

// If you need to type loginMutation/registerMutation for context, you can add:
// loginMutation: any;
// registerMutation: any;