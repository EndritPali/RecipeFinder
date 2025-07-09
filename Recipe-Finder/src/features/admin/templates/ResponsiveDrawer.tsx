import { Drawer, Menu } from "antd"
import { useMemo } from 'react';
import { Link, useLocation } from 'react-router-dom';
import '../scss/ResponsiveDrawer.scss'
import { ResponsiveDrawerProps } from '@/types/admin';
import { useAuth } from '@/context/AuthContext';

import {
    DashboardOutlined,
    UserOutlined,
    HomeOutlined,
    LockOutlined
} from '@ant-design/icons';

export default function ResponsiveDrawer({ open, onClose }: ResponsiveDrawerProps) {
    const location = useLocation();
    const auth = useAuth();
    const user = auth?.user;

    const isUser = user?.role === 'User';

    const menuItems = useMemo(() => {
        return [
            {
                key: '/',
                icon: <HomeOutlined />,
                label: <Link to="/">Home</Link>,
            },
            {
                key: '/dashboard',
                icon: <DashboardOutlined />,
                label: <Link to="/dashboard">Dashboard</Link>,
            },
            {
                key: '/dashboard/users',
                icon: isUser ? <LockOutlined /> : <UserOutlined />,
                label: <Link to="/dashboard/users">Users</Link>,
                disabled: isUser,
            },
        ];
    }, []);

    return (
        <Drawer
            title='RecipeFinder'
            className="responsive-only"
            open={open}
            onClose={onClose}
            placement="left"
            width={200}

        >
            <Menu
                className="responsive-only-menu"
                theme="dark"
                mode="inline"
                selectedKeys={[location.pathname]}
                items={menuItems}
                style={{ height: '100%' }}
            />
        </Drawer>
    );
}
