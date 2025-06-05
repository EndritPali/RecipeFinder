import React, { useState, useEffect, useCallback } from 'react';
import { Drawer, Card, Space, Avatar, message, Button } from 'antd';
import { useDeleteUsers } from '../../hooks/useDeleteUsers';
import { useFetchUsers } from '../../hooks/useFetchUsers';
import {
    BookOutlined, CalendarOutlined, UserOutlined,
    SafetyCertificateOutlined, IdcardOutlined,
    MailOutlined, KeyOutlined, EditOutlined,
    DeleteOutlined
} from '@ant-design/icons';

import api from '../../Services/api';
import useAuth from '../../hooks/useAuth';
import DrawerInput from './DrawerInputs';
import '../scss/AccountDrawer.scss';
import { useNavigate } from 'react-router-dom';

export default function AccountDrawer({ open, onClose }) {
    const { currentUser, logout } = useAuth();
    const [recipes, setRecipes] = useState([]);
    const [editing, setEditing] = useState(null);
    const [loading, setLoading] = useState(false);
    const [editedEmail, setEditedEmail] = useState('');
    const [editedPassword, setEditedPassword] = useState('');

    const { fetchUsers } = useFetchUsers();
    const { deleteUser } = useDeleteUsers(fetchUsers);
    const navigate = useNavigate();

    const handleDelete = useCallback(async (id) => {
        const msg =
            'Are you sure you want to delete your account?'

        if (window.confirm(msg)) {
            try {
                await deleteUser(id);
                await logout()
                navigate('/')
            } catch (err) {
                console.error("Deletion error:", err);
                alert("Something went wrong during deletion");
            }
        }
    }, [deleteUser, logout, navigate]);

    useEffect(() => {
        if (open) {
            setLoading(true);
            api.get('v1/my-recipes')
                .then(({ data }) => setRecipes(data.data))
                .finally(() => setLoading(false));
        }
    }, [open]);

    const updateUser = async (payload) => {
        try {
            await api.put(`v1/users/${currentUser.id}`, payload);

            const updatedUser = { ...currentUser, ...payload };
            localStorage.setItem('user', JSON.stringify(updatedUser));

            setEditing(null);
            message.success('Updated successfully');
        } catch (error) {
            message.error(error.response?.data?.message || 'Update failed');
        }
    };

    const handleEdit = (field) => {
        setEditing(field);
        if (field === 'email') setEditedEmail(currentUser.email || '');
        else setEditedPassword('');
    };

    return (
        <Drawer
            title="Account Settings"
            open={open}
            onClose={onClose}
            loading={loading ? 1 : 0}
        >
            <div className="user">
                <div className="user__header">
                    <Avatar icon={<UserOutlined />} />
                    <h2>{currentUser?.username}</h2>
                </div>
                <Button
                    className='delete-btn'
                    onClick={() => handleDelete(currentUser?.id)}
                >
                    <DeleteOutlined />
                </Button>
            </div>

            <Card title={<Space><UserOutlined /> User Profile</Space>}>
                <DrawerInput
                    icon={<IdcardOutlined />}
                    header="Role:"
                    information={currentUser?.role || 'Loading...'}
                />
                <DrawerInput
                    icon={<CalendarOutlined />}
                    header="Date Created:"
                    information={currentUser?.created_at}
                />
                <DrawerInput
                    icon={<BookOutlined />}
                    header="Recipes Created:"
                    information={recipes.map(r => r.title).join(', ')}
                />
            </Card>

            <Card title={<Space><SafetyCertificateOutlined /> Security</Space>}>
                <DrawerInput
                    icon={<MailOutlined />}
                    header="Email:"
                    information={currentUser?.email}
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
        </Drawer>
    );

    function renderButtons(field, value) {
        return editing === field ? (
            <div className="btns">
                <button onClick={() => setEditing(null)}>Cancel</button>
                <button onClick={() => updateUser({ [field]: value })}>Save</button>
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