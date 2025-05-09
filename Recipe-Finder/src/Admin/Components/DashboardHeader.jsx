import { Avatar, message, Badge } from 'antd';
import UserImg from '../../assets/pasta-four.svg';
import { UserOutlined, SettingOutlined, LogoutOutlined, BellOutlined } from '@ant-design/icons';
import { Popover } from 'antd';
import '../scss/DashboardHeader.scss';
import { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import AccountDrawer from '../Templates/AccountDrawer';
import api from '../../Services/api';
import NotificationsModal from '../Templates/NotificationsModal';

export default function DashboardHeader() {
    const [isDrawerOpen, setIsDrawerOpen] = useState(false);
    const [user, setUsers] = useState([]);
    const navigate = useNavigate();

    const showDrawer = () => setIsDrawerOpen(true);
    const closeDrawer = () => setIsDrawerOpen(false);
    const [isModalOpen, setIsModalOpen] = useState(false)

    const handleOpenModal = () => {
        setIsModalOpen(true);
    }

    const handleCloseModal = () => {
        setIsModalOpen(false);
    }

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
            console.error('Logout error:', error);
            message.error('Error logging out');
        }
    };

    useEffect(() => {
        const storedUser = localStorage.getItem('user');
        if (storedUser) {
            setUsers(JSON.parse(storedUser));
        }
    }, []);

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
                    <div className="dashboard-header__profile-notifications" onClick={handleOpenModal}>
                        <Badge dot>
                            <BellOutlined />
                        </Badge>
                    </div>
                    <Popover content={dropdown} trigger={'click'}>
                        <Avatar icon={<UserOutlined />} />
                    </Popover>
                    <h5>{user.username}</h5>
                </div>
            </div>
            <AccountDrawer profilePic={UserImg} open={isDrawerOpen} onClose={closeDrawer} />
            <NotificationsModal open={isModalOpen}
                onOk={handleCloseModal}
                onCancel={handleCloseModal} />
        </>
    );
}
