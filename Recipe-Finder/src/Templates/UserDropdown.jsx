import { Dropdown } from 'antd';

export default function UserDropdown({ menuItems, placement = 'bottomRight', trigger }) {
    return (
        <Dropdown
            menu={{ items: menuItems }}
            placement={placement}
            trigger={['click']}
        >
            {trigger}
        </Dropdown>
    );
}