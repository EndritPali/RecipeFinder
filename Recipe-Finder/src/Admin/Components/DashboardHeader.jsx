import { Avatar, message, Badge, Popover } from 'antd';
import { UserOutlined, SettingOutlined, LogoutOutlined, BellOutlined } from '@ant-design/icons';
import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import AccountDrawer from '../Templates/AccountDrawer';
import NotificationsModal from '../Templates/NotificationsModal';
import useAuth from '../../hooks/useAuth';
import '../scss/DashboardHeader.scss';

export default function DashboardHeader() {
    const [isDrawerOpen, setIsDrawerOpen] = useState(false);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [pendingRequests, setPendingRequests] = useState(0);
    const { user, logout, fetchPendingRequests } = useAuth();
    const navigate = useNavigate();

    useEffect(() => {
        if (user.role !== 'Admin') return;

        const updatePendingRequests = async () => {
            const count = await fetchPendingRequests();
            setPendingRequests(count);
        };

        updatePendingRequests();
        const interval = setInterval(updatePendingRequests, 30000);
        return () => clearInterval(interval);
    }, [user.role, fetchPendingRequests]);

    const handleLogout = async () => {
        const success = await logout();
        if (success) {
            message.success('Logged out successfully');
            navigate('/');
        } else {
            message.error('Logout failed. Please try again.');
        }
    };

    const handleCloseModal = async () => {
        setIsModalOpen(false);
        if (user.role === 'Admin') {
            const count = await fetchPendingRequests();
            setPendingRequests(count);
        }
    };

    return (
        <>
            <div className="dashboard-header">
                <div className="dashboard-header__logo">
                    <h1>Recipe <span>finder</span></h1>
                </div>

                <div className="dashboard-header__profile">
                    {user.role === 'Admin' && (
                        <div className="dashboard-header__profile-notifications" onClick={() => setIsModalOpen(true)}>
                            <Badge count={pendingRequests}>
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
                                <li className='logout' onClick={handleLogout}>
                                    <p><LogoutOutlined /> Logout</p>
                                </li>
                            </div>
                        }
                        trigger="click"
                    >
                        <Avatar icon={<UserOutlined />} />
                    </Popover>
                    <h5>{user.username || 'User'}</h5>
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