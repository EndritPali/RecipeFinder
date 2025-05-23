import { Drawer, Menu } from "antd"
import { useMemo } from 'react';
import { Link, useLocation } from 'react-router-dom';
import '../scss/ResponsiveDrawer.scss'

import {
    DashboardOutlined,
    UserOutlined,
    HomeOutlined,
    LockOutlined
} from '@ant-design/icons';

export default function ResponsiveDrawer({ open, onClose }) {
    const location = useLocation();

    const menuItems = useMemo(() => {
        return [
            {
                key: '/',
                icon: <HomeOutlined />,
                label: <Link to="/">Home</Link>,
            },
            {
                key: '/admin',
                icon: <DashboardOutlined />,
                label: <Link to="/admin">Dashboard</Link>,
            },
            {
                key: '/admin/users',
                icon: <UserOutlined />,
                label: <Link to="/admin/users">Users</Link>,
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
