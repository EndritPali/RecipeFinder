// Types for admin features
import { ReactNode } from 'react';

export interface AccountDrawerProps {
    open: boolean;
    onClose: () => void;
}

export interface UserType {
    id: string;
    username: string;
    email: string;
    role: string;
    created_at: string;
}

export interface ApproveResetModalProps {
    open: boolean;
    onOk: () => void;
    onCancel: () => void;
    resetEmail: string;
    tempPassword: string;
}

export interface DrawerInputProps {
    icon: ReactNode;
    header: string;
    information: any;
    children?: ReactNode;
    isEditing?: boolean;
    value?: string;
    onValueChange?: (value: string) => void;
}

export interface GridSortProps {
    data: any[];
    onEdit: (item: any) => void;
    onDelete?: (key: string) => void;
    pagination?: any;
    loading?: boolean;
}

export interface CardDescriptionProps {
    item: any;
}

export interface ImgUploadProps {
    form: import('antd').FormInstance;
}

export interface NotificationsModalProps {
    open: boolean;
    onOk: () => void;
    onCancel: () => void;
}

export interface ResetRequest {
    id: string;
    email: string;
    last_password: string;
    username: string;
}

export interface ResetInfo {
    user_email: string;
    temporary_password: string;
}

export interface RecipeModalProps {
    open: boolean;
    onOk: () => void;
    onCancel: () => void;
    mode?: 'create' | 'edit';
    item?: any;
}

export interface ResponsiveDrawerProps {
    open: boolean;
    onClose: () => void;
}

export interface UserModalProps {
    open: boolean;
    onOk: () => void;
    onCancel: () => void;
    mode?: 'create' | 'edit';
    item?: any;
} 