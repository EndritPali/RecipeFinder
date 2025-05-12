import React, { useState, useEffect } from 'react';
import { Drawer, Card, Space, Avatar, message } from 'antd';
import {
    BookOutlined, CalendarOutlined, UserOutlined,
    SafetyCertificateOutlined, IdcardOutlined,
    MailOutlined, KeyOutlined, EditOutlined
} from '@ant-design/icons';

import api from '../../Services/api';
import auth from '../../Services/auth';
import DrawerInput from './DrawerInputs';
import '../scss/AccountDrawer.scss';

export default function AccountDrawer({ open, onClose }) {
    const [state, setState] = useState({
        user: JSON.parse(localStorage.getItem('user') || '{}'),
        fullUser: null,
        recipes: [],
        editing: null,
        loading: false
    });

    useEffect(() => {
        if (open) {
            setState(prev => ({ ...prev, loading: true }));
            Promise.all([
                auth.getCurrentUser().then(fullUser =>
                    setState(prev => ({ ...prev, fullUser }))
                ),
                api.get('/my-recipes').then(({ data }) =>
                    setState(prev => ({ ...prev, recipes: data.data }))
                )
            ]).finally(() =>
                setState(prev => ({ ...prev, loading: false }))
            );
        }
    }, [open]);

    const updateUser = async (payload) => {
        try {
            const userId = state.user.id || state.fullUser?.id;
            await api.put(`/user/${userId}`, payload);

            const updatedUser = { ...state.user, ...payload };
            localStorage.setItem('user', JSON.stringify(updatedUser));

            setState(prev => ({
                ...prev,
                user: updatedUser,
                editing: null
            }));

            message.success('Updated successfully');
        } catch (error) {
            message.error(error.response?.data?.message || 'Update failed');
        }
    };

    const user = {
        ...state.user,
        ...(state.fullUser || {}),
        role: state.fullUser?.role || state.user.role || 'Loading...'
    };

    return (
        <Drawer
            title="Account Settings"
            open={open}
            onClose={onClose}
            loading={state.loading ? 1 : 0}
        >
            <div className="user">
                <Avatar icon={<UserOutlined />} />
                <h2>{user.username}</h2>
            </div>

            <Card title={<Space><UserOutlined /> User Profile</Space>}>
                <DrawerInput
                    icon={<IdcardOutlined />}
                    header="Role:"
                    information={user.role}
                />
                <DrawerInput
                    icon={<CalendarOutlined />}
                    header="Date Created:"
                    information={user.date}
                />
                <DrawerInput
                    icon={<BookOutlined />}
                    header="Recipes Created:"
                    information={state.recipes.map(r => r.title).join(', ')}
                />
            </Card>

            <Card title={<Space><SafetyCertificateOutlined /> Security</Space>}>
                {['email', 'password'].map(field => (
                    <DrawerInput
                        key={field}
                        icon={field === 'email' ? <MailOutlined /> : <KeyOutlined />}
                        header={`${field.charAt(0).toUpperCase() + field.slice(1)}:`}
                        information={field === 'email' ? user.email : '********'}
                        isEditing={state.editing === field}
                        value={state[`edited${field.charAt(0).toUpperCase() + field.slice(1)}`] || ''}
                        onValueChange={(value) => setState(prev => ({
                            ...prev,
                            [`edited${field.charAt(0).toUpperCase() + field.slice(1)}`]: value
                        }))}
                    >
                        {state.editing === field ? (
                            <div className="btns">
                                <button onClick={() => setState(prev => ({ ...prev, editing: null }))}>
                                    Cancel
                                </button>
                                <button onClick={() => updateUser({
                                    [field]: state[`edited${field.charAt(0).toUpperCase() + field.slice(1)}`]
                                })}>
                                    Save
                                </button>
                            </div>
                        ) : (
                            <div className="btns">
                                <button onClick={() => setState(prev => ({ ...prev, editing: field }))}>
                                    <EditOutlined />
                                </button>
                            </div>
                        )}
                    </DrawerInput>
                ))}
            </Card>
        </Drawer>
    );
}