import { Link } from 'react-router-dom';
import { useAuth } from '@/context/AuthContext';

export function useUserAccount(setModalMode, setIsAccountModalOpen) {

  const { user } = useAuth();

  const openAccountModal = (mode) => {
    setModalMode(mode);
    setIsAccountModalOpen(true);
  };

  const menuItems = user
    ? [
      {
        key: 'username',
        label: user.username,
        disabled: true
      },
      {
        key: 'divider',
        type: 'divider'
      },
      {
        key: 'admin',
        label: <Link to="/admin">Dashboard</Link>
      },
    ]
    : [
      {
        key: 'login',
        label: 'Login',
        onClick: () => openAccountModal('login')
      },
      {
        key: 'divider',
        type: 'divider'
      },
      {
        key: 'register',
        label: 'Register',
        onClick: () => openAccountModal('register')
      }
    ];

  return { user, menuItems };
}