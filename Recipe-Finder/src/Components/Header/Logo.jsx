import { Dropdown } from 'antd';

export default function Logo({ menuItems }) {
    return (
        <div className="header__logo">
            <div className="header__logo-box">
                <Dropdown menu={{ items: menuItems }} placement='bottomRight'>
                    <i className="fas fa-bars"></i>
                </Dropdown>
            </div>
            <h1><span>Recipe</span> finder</h1>
        </div>
    );
}