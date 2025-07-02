import { Dropdown } from 'antd';

export default function UserDropdown({ menuItems, placement = 'bottomRight', trigger }: any) {
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