import { Avatar, message, Badge, Popover } from 'antd';
import { UserOutlined, SettingOutlined, LogoutOutlined, BellOutlined } from '@ant-design/icons';
import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import AccountDrawer from '../templates/AccountDrawer';
import NotificationsModal from '../templates/NotificationsModal';
import { useAuth } from '@/context/AuthContext';
import '../scss/DashboardHeader.scss';

export default function DashboardHeader() {
    const [isDrawerOpen, setIsDrawerOpen] = useState(false);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [pendingRequests, setPendingRequests] = useState(0);
    const auth = useAuth();
    const user = auth?.user;
    const logout = auth?.logout;
    const fetchPendingRequests = auth?.fetchPendingRequests;
    const navigate = useNavigate();

    useEffect(() => {
        if (user?.role !== 'Admin' || !fetchPendingRequests || !window.Echo) return;

        const channel = window.Echo.channel('password-resets')
            .listen('.PasswordResetRequested', async () => {
                try {
                    const count = await fetchPendingRequests();
                    setPendingRequests(count);
                } catch {
                    setPendingRequests(0);
                }
            });

        return () => {
            window.Echo.leave('password-resets');
        };
    }, [user?.role, fetchPendingRequests]);


    const getInitials = (name?: string) => {
        if (!name) return '';
        const parts = name.trim().split(' ');
        if (parts.length === 1) return parts[0][0].toUpperCase();
        return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
    };

    const handleCloseModal = async () => {
        setIsModalOpen(false);
        if (user?.role === 'Admin' && fetchPendingRequests) {
            try {
                const count = await fetchPendingRequests();
                setPendingRequests(count);
            } catch {
                setPendingRequests(0);
            }
        }
    };

    return (
        <>
            <div className="dashboard-header">
                <div className="dashboard-header__logo">
                    <h1>Recipe <span>finder</span></h1>
                </div>

                <div className="dashboard-header__profile">
                    {user?.role === 'Admin' && (
                        <div className="dashboard-header__profile-notifications" onClick={() => setIsModalOpen(true)}>
                            <Badge count={pendingRequests} showZero>
                                <BellOutlined />
                            </Badge>
                        </div>
                    )}

                    <Popover
                        content={
                            <div className='dropdown-list'>
                                <li onClick={() => setIsDrawerOpen(true)}>
                                    <p><SettingOutlined /> Account Settings</p>
                                </li>
                            </div>
                        }
                        trigger="click"
                    >
                        <Avatar>
                            {user?.username ? getInitials(user.username) : <UserOutlined />}
                        </Avatar>
                    </Popover>
                    <h5>{user?.username || 'User'}</h5>
                </div>
            </div>

            <AccountDrawer open={isDrawerOpen} onClose={() => setIsDrawerOpen(false)} />
            <NotificationsModal
                open={isModalOpen}
                onOk={handleCloseModal}
                onCancel={handleCloseModal}
            />
        </>
    );
}