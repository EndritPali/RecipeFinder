import { useState, useCallback } from 'react';
import { Drawer, Card, Space, Avatar, Button, Modal, message } from 'antd';
import { useDeleteUsers } from '../hooks/useDeleteUsers';
import {
    BookOutlined, CalendarOutlined, UserOutlined,
    SafetyCertificateOutlined, IdcardOutlined,
    MailOutlined, KeyOutlined, EditOutlined,
    UserDeleteOutlined,
    LogoutOutlined
} from '@ant-design/icons';
import { useAuth } from '@/context/AuthContext';
import DrawerInput from './DrawerInputs';
import '../scss/AccountDrawer.scss';
import { useNavigate } from 'react-router-dom';
import { useFetchRecipes } from "@/features/recipes/hooks/useFetchRecipes";
import { useUpdateUser } from '@/features/admin/hooks/useUpdateUser';
import { AccountDrawerProps, UserType } from '@/types/admin';
import { AuthContextType } from '@/types/auth';

export default function AccountDrawer({ open, onClose }: AccountDrawerProps) {
    const auth = useAuth() as AuthContextType;
    const { user, logout } = auth;
    const [editing, setEditing] = useState<string | null>(null);
    const [editedEmail, setEditedEmail] = useState('');
    const [editedPassword, setEditedPassword] = useState('');
    const deleteUser = useDeleteUsers();
    const navigate = useNavigate();
    const { data, isLoading } = useFetchRecipes({ onlyMine: true, paginate: false });
    const recipeTitles = data?.recipes?.map((r: any) => r.recipetitle).join(', ') || 'None';
    const updateUser = useUpdateUser();

    const handleDelete = useCallback(async (id: string) => {
        const msg =
            'Are you sure you want to delete your account?'

        if (window.confirm(msg)) {
            try {
                await deleteUser.mutateAsync(id);
                navigate('/')
                await logout()
            } catch (err) {
                console.error("Deletion error:", err);
                alert("Something went wrong during deletion");
            }
        }
    }, [deleteUser, logout, navigate]);

    const handleEdit = (field: string) => {
        setEditing(field);
        if (field === 'email') setEditedEmail(user?.email || '');
        else setEditedPassword('');
    };

    const handleLogout = async () => {
        if (!logout) return;
        try {
            await logout();
            message.success('Logged out successfully');
            navigate('/');
        } catch {
            message.error('Logout failed. Please try again.');
        }
    };

    const getInitials = (name?: string) => {
        if (!name) return '';
        const parts = name.trim().split(' ');
        if (parts.length === 1) return parts[0][0].toUpperCase();
        return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
    };

    const handleUpdateUser = (field: string, value: string) => {
        if (!user) return;
        updateUser.mutate({ id: user.id, payload: { [field]: value } });
        setEditing(null);
    };

    return (
        <Drawer
            title="Account Settings"
            open={open}
            onClose={onClose}
            loading={isLoading}
        >
            <div className="user">
                <div className="user__header">
                    <Avatar>
                        {user?.username ? getInitials(user.username) : <UserOutlined />}
                    </Avatar>
                    <div className="user__header--info">
                        <h2>{user?.username}</h2>
                        <h3>{user?.role || 'Loading...'}</h3>
                    </div>
                </div>
                <Button
                    className='delete-btn'
                    onClick={() => user && handleDelete(user.id)}
                >
                    <UserDeleteOutlined />
                </Button>
            </div>

            <Card
                title={<Space>
                    <UserOutlined />
                    User Profile
                </Space>}
                className='profile-data'>
                <DrawerInput
                    icon={<CalendarOutlined />}
                    header="Date Created:"
                    information={user?.created_at}
                    isEditing={editing === 'created_at'}
                    value={''}
                    onValueChange={() => { }}
                />
                <DrawerInput
                    icon={<BookOutlined />}
                    header="Recipes Created:"
                    information={recipeTitles}
                    isEditing={editing === 'recipes'}
                    value={''}
                    onValueChange={() => { }}
                />
            </Card>

            <Card title={<Space><SafetyCertificateOutlined /> Security</Space>}>
                <DrawerInput
                    icon={<MailOutlined />}
                    header="Email:"
                    information={user?.email}
                    isEditing={editing === 'email'}
                    value={editedEmail}
                    onValueChange={setEditedEmail}
                >
                    {renderButtons('email', editedEmail)}
                </DrawerInput>

                <DrawerInput
                    icon={<KeyOutlined />}
                    header="Password:"
                    information="********"
                    isEditing={editing === 'password'}
                    value={editedPassword}
                    onValueChange={setEditedPassword}
                >
                    {renderButtons('password', editedPassword)}
                </DrawerInput>
            </Card>
            <Button
                icon={<LogoutOutlined />}
                onClick={handleLogout}
                className='btn__logout'
            >
                Log Out
            </Button>
        </Drawer>
    );

    function renderButtons(field: string, value: string) {
        return editing === field ? (
            <div className="btns">
                <button onClick={() => setEditing(null)}>Cancel</button>
                <button onClick={() => handleUpdateUser(field, value)}>Save</button>
            </div>
        ) : (
            <div className="btns">
                <button onClick={() => handleEdit(field)}>
                    <EditOutlined />
                </button>
            </div>
        );
    }
}