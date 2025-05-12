import { Avatar, message, Badge, Popover } from 'antd';
import { UserOutlined, SettingOutlined, LogoutOutlined, BellOutlined } from '@ant-design/icons';
import { useState, useEffect, useCallback } from 'react';
import { useNavigate } from 'react-router-dom';
import AccountDrawer from '../Templates/AccountDrawer';
import NotificationsModal from '../Templates/NotificationsModal';
import api from '../../Services/api';
import auth from '../../Services/auth';
import '../scss/DashboardHeader.scss';

export default function DashboardHeader() {
    const [isDrawerOpen, setIsDrawerOpen] = useState(false);
    const [user, setUser] = useState(null);
    const [pendingRequests, setPendingRequests] = useState(0);
    const [userBasicInfo, setUserBasicInfo] = useState({});
    const [userFullInfo, setUserFullInfo] = useState(null);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const navigate = useNavigate();

    const showDrawer = () => setIsDrawerOpen(true);
    const closeDrawer = () => setIsDrawerOpen(false);

    const handleOpenModal = () => {
        setIsModalOpen(true);
    };

    const handleCloseModal = () => {
        setIsModalOpen(false);
        fetchPendingRequestsCount();
    };

    const handleLogout = async () => {
        try {
            const token = localStorage.getItem('token');
            if (token) {
                api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                await api.post('/auth/logout');
            }
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            message.success('Logged out successfully');
            navigate('/');
        } catch (error) {
            message.error(error);
        }
    };

    useEffect(() => {
        const storedUser = localStorage.getItem('user');
        if (storedUser) {
            setUserBasicInfo(JSON.parse(storedUser));
        }

        const fetchUserData = async () => {
            try {
                const userData = await auth.getCurrentUser();
                setUserFullInfo(userData);
            } catch (error) {
                console.error('Error fetching user data:', error);
            }
        };

        Promise.all([fetchUserData(),]);
    }, []);

    const mainuser = {
        ...userBasicInfo,
        role: userFullInfo?.role || 'Loading...'
    };

    const fetchPendingRequestsCount = useCallback(async () => {
        if (mainuser.role === 'Admin') {
            try {
                const token = localStorage.getItem('token');
                if (!token) return;

                api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                const response = await api.get('/auth/password-reset/pending');

                if (response.data?.data && Array.isArray(response.data.data)) {
                    setPendingRequests(response.data.data.length);
                } else {
                    setPendingRequests(0);
                }
            } catch {
                setPendingRequests(0);
            }
        }
    }, [mainuser.role]);

    useEffect(() => {
        const storedUser = localStorage.getItem('user');
        if (storedUser) {
            try {
                setUser(JSON.parse(storedUser));
            } catch {
                setUser(null);
            }
        }
    }, []);

    useEffect(() => {
        fetchPendingRequestsCount();
        const interval = setInterval(fetchPendingRequestsCount, 30000);
        return () => clearInterval(interval);
    }, [fetchPendingRequestsCount]);

    const dropdown = (
        <div className='dropdown-list'>
            <li onClick={showDrawer}>
                <p><SettingOutlined /> Account Settings</p>
            </li>
            <li className='logout' onClick={handleLogout}>
                <p><LogoutOutlined /> Logout</p>
            </li>
        </div>
    );

    return (
        <>
            <div className="dashboard-header">
                <div className="dashboard-header__logo">
                    <h1>Recipe <span>finder</span></h1>
                </div>

                <div className="dashboard-header__profile">
                    {mainuser.role === 'Admin' ? (<div className="dashboard-header__profile-notifications" onClick={handleOpenModal}>
                        <Badge count={pendingRequests}>
                            <BellOutlined />
                        </Badge>
                    </div>) : ('')}

                    <Popover content={dropdown} trigger="click">
                        <Avatar icon={<UserOutlined />} />
                    </Popover>
                    <h5>{user?.username || 'User'}</h5>
                </div>
            </div>

            <AccountDrawer open={isDrawerOpen} onClose={closeDrawer} />
            <NotificationsModal open={isModalOpen} onOk={handleCloseModal} onCancel={handleCloseModal} />
        </>
    );
}
