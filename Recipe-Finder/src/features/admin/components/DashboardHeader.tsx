import { Avatar, Badge, Popover } from 'antd';
import { UserOutlined, SettingOutlined, BellOutlined } from '@ant-design/icons';
import { useState } from 'react';
import AccountDrawer from '../templates/AccountDrawer';
import NotificationsModal from '../templates/NotificationsModal';
import { useAuth } from '@/context/AuthContext';
import '../scss/DashboardHeader.scss';
import { useFetchResetRequests } from '../hooks/useFetchRequests';
import { usePasswordResetEvents } from '../hooks/usePasswordResetEvents';

export default function DashboardHeader() {
    const [isDrawerOpen, setIsDrawerOpen] = useState(false);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const auth = useAuth();
    const user = auth?.user;
    const isAdmin = user?.role === 'Admin';

    const { data: resetRequests, refetch } = useFetchResetRequests(isAdmin);
    const pendingRequests = resetRequests?.length || 0;

    usePasswordResetEvents(async () => { await refetch(); }, isAdmin);

    const getInitials = (name?: string) => {
        if (!name) return '';
        const parts = name.trim().split(' ');
        if (parts.length === 1) return parts[0][0].toUpperCase();
        return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
    };

    const handleCloseModal = async () => {
        setIsModalOpen(false);
        if (user?.role === 'Admin') {
            await refetch();
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