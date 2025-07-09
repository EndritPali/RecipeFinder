import '../scss/DashboardSider.scss';
import { Layout, Menu, Button } from 'antd';
import {
  DashboardOutlined,
  UserOutlined,
  HomeOutlined,
  LeftOutlined,
  RightOutlined,
  LockOutlined,
} from '@ant-design/icons';
import { useState } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { useAuth } from '@/context/AuthContext';

const { Sider } = Layout;

export default function DashboardSider() {
  const [collapsed, setCollapsed] = useState(false);
  const auth = useAuth();
  const user = auth?.user;
  const location = useLocation();

  const isUser = user?.role === 'User';

  const menuItems = [
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

  const siderProps = {
    collapsible: true,
    collapsed,
    trigger: null,
    width: 200,
    style: {
      minHeight: '100vh',
      background: '#001529',
      display: 'flex',
      flexDirection: 'column' as 'column',
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