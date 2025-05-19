import '../scss/DashboardSider.scss';
import { Layout, Menu, Button } from 'antd';
import {
  DashboardOutlined,
  UserOutlined,
  HomeOutlined,
  LeftOutlined,
  RightOutlined,
  LockOutlined
} from '@ant-design/icons';
import { useState } from 'react';
import { Link, useLocation } from 'react-router-dom';
import useAuth from '../../hooks/useAuth';

const { Sider } = Layout;

export default function DashboardSider() {
  const [collapsed, setCollapsed] = useState(false);
  const { currentUser } = useAuth();
  const location = useLocation();

  const isUser = currentUser?.role === 'User';

  const menuItems = [
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
      icon: isUser ? <LockOutlined /> : <UserOutlined />,
      label: <Link to="/admin/users">Users</Link>,
      disabled: isUser,
    },
  ];

  const siderProps = {
    collapsible: true,
    collapsed,
    trigger: null,
    width: 200,
    style: {
      minHeight: '100vh',
      background: '#001529',
      display: 'flex',
      flexDirection: 'column',
      justifyContent: 'space-between',
    }
  };

  const toggleCollapse = () => setCollapsed(!collapsed);

  return (
    <Sider {...siderProps}>
      <div className="ant-menu-children-wrapper">
        <Menu
          theme="dark"
          mode="inline"
          selectedKeys={[location.pathname]}
          items={menuItems}
        />
      </div>

      <div style={{ padding: 16, textAlign: 'center' }}>
        <Button
          type="text"
          icon={collapsed ? <RightOutlined /> : <LeftOutlined />}
          onClick={toggleCollapse}
          style={{
            width: '100%',
            background: 'transparent',
            color: '#fff'
          }}
        />
      </div>
    </Sider>
  );
}