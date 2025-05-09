import '../Scss/Header.scss';
import Magnify from '../assets/MagnifyingGlass.svg';
import User from '../assets/User.svg';
import Heart from '../assets/Heart.svg';
import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { Dropdown, Menu } from 'antd';
import AccountModal from '../Templates/AccountModal';

export default function Header() {
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [modalMode, setModalMode] = useState('login'); 
  const [user, setUser] = useState(null);

  useEffect(() => {
    const storedUser = localStorage.getItem('user');
    if (storedUser) {
      setUser(JSON.parse(storedUser));
    }
  }, []);

  const handleOpenModal = (mode) => {
    setModalMode(mode); 
    setIsModalOpen(true);
  };
  const handleCloseModal = () => setIsModalOpen(false);

  const menuItems = user ? [
    { key: 'username', label: user.username },
    { key: 'divider', type: 'divider' },
    { key: 'admin', label: <Link to="/admin">Admin Panel</Link> },
  ] : [
    { key: 'login', label: 'Login', onClick: () => handleOpenModal('login') },
    { key: 'divider', type: 'divider' },
    { key: 'register', label: 'Register', onClick: () => handleOpenModal('register') },
  ];

  return (
    <>
      <div className="header">
        <div className="header__logo">
          <div className="header__logo-box">
            <i className="fas fa-bars"></i>
          </div>
          <h1><span>Recipe</span> finder</h1>
        </div>
        <div className="header__search--mobile">
          <i className="fas fa-search"></i>
          <input type="search" placeholder="Search recipes" />
        </div>
        <div className="header__widgets">
          <button><img src={Magnify} alt="magnifying" /></button>
          <button><img src={Heart} alt="heart" /></button>
          <Dropdown menu={{ items: menuItems }} placement='bottomRight'>
            <button><img src={User} alt="user" /></button>
          </Dropdown>
        </div>
      </div>

      <AccountModal
        open={isModalOpen}
        onOk={handleCloseModal}
        onCancel={handleCloseModal}
        mode={modalMode}  
      />
    </>
  );
}
