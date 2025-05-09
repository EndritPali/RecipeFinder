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
import { useState, useEffect, useMemo } from 'react';
import { Link, useLocation } from 'react-router-dom';
import auth from '../../Services/auth';

const { Sider } = Layout;

export default function DashboardSider() {
  const [collapsed, setCollapsed] = useState(false);
  const [userInfo, setUserInfo] = useState(null);
  const [userRole, setUserRole] = useState(null);
  const [loading, setLoading] = useState(true);
  const location = useLocation();



  useEffect(() => {
    const storedUser = localStorage.getItem('user');
    if (storedUser) {
      setUserInfo(JSON.parse(storedUser));
    }

    const fetchUserRole = async () => {
      try {
        const user = await auth.getCurrentUser();
        setUserRole(user?.role || null);
      } catch (error) {
        console.error('Error fetching user role:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchUserRole();
  }, []);

  const menuItems = useMemo(() => {
    const isUser = userRole === 'User';

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
        icon: isUser ? <LockOutlined /> : <UserOutlined />,
        label: <Link to="/admin/users">Users</Link>,
        disabled: isUser,
      },
    ];
  }, [userRole]);

  if (loading) {
    return (
      <Sider
        collapsible
        collapsed={collapsed}
        trigger={null}
        width={200}
        style={{
          minHeight: '100vh',
          background: '#001529',
          display: 'flex',
          flexDirection: 'column',
          justifyContent: 'space-between',
        }}
      >
        <div className="ant-menu-children-wrapper">
          <Menu theme="dark" mode="inline" items={[]} />
        </div>
        <div style={{ padding: 16, textAlign: 'center' }}>
          <Button
            type="text"
            icon={collapsed ? <RightOutlined /> : <LeftOutlined />}
            onClick={() => setCollapsed(!collapsed)}
            style={{
              color: '#fff',
              width: '100%',
              background: 'transparent',
            }}
          />
        </div>
      </Sider>
    );
  }

  return (
    <Sider
      collapsible
      collapsed={collapsed}
      trigger={null}
      width={200}
      style={{
        minHeight: '100vh',
        background: '#001529',
        display: 'flex',
        flexDirection: 'column',
        justifyContent: 'space-between',
      }}
    >
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
          onClick={() => setCollapsed(!collapsed)}
          style={{
            color: '#fff',
            width: '100%',
            background: 'transparent',
          }}
        />
      </div>
    </Sider>
  );
}
