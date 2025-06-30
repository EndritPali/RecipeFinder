import { Dropdown } from 'antd';

export default function AuthDropdown({ onLogin, onRegister, trigger, isOpen, onOpenChange }) {
    const items = [
        {
            key: 'login',
            label: 'Login',
            onClick: () => onLogin('login')
        },
        {
            key: 'register',
            label: 'Register',
            onClick: () => onRegister('register')
        }
    ];

    return (
        <Dropdown
            menu={{ items }}
            placement="topRight"
            open={isOpen}
            onOpenChange={onOpenChange}
            trigger={['click']}
        >
            {trigger}
        </Dropdown>
    );
}