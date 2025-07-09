import { Link } from 'react-router-dom';
import { useAuth } from '@/context/AuthContext';
import { AccountModalMode } from '@/types/auth';

export function useUserAccount(
  setModalMode: React.Dispatch<React.SetStateAction<AccountModalMode>>,
  setIsAccountModalOpen: React.Dispatch<React.SetStateAction<boolean>>
) {

  const auth = useAuth();
  const user = auth?.user;

  const openAccountModal = (mode: AccountModalMode) => {
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
        label: <Link to="/dashboard">Dashboard</Link>
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

  return { user, menuItems, openAccountModal };
}