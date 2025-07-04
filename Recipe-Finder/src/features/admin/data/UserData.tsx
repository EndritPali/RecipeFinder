import { Space } from 'antd';
import { EditOutlined, UserDeleteOutlined } from '@ant-design/icons';
import type { FixedType } from 'rc-table/lib/interface';

export const columns = (onEdit: (record: any) => void, onDelete: (record: any) => void) => [
    {
        title: 'Username',
        dataIndex: 'username',
        key: 'username',
        render: (text: string) => <p>{text}</p>,
        sorter: (a: any, b: any) => a.username.localeCompare(b.username),
    },
    {
        title: 'Email',
        dataIndex: 'email',
        key: 'email',
    },
    {
        title: 'Role',
        dataIndex: 'role',
        key: 'role',
    },
    {
        title: 'Date Created',
        dataIndex: 'date',
        key: 'date',
    },
    {
        title: 'Actions',
        key: 'actions',
        fixed: 'right' as FixedType,
        render: (_: any, record: any) => (
            <Space size="middle" className='btn-space'>
                <button className='edit-btn' onClick={() => onEdit(record)}> <EditOutlined /> </button>
                <button className='delete-btn' onClick={() => onDelete(record.key)}> <UserDeleteOutlined /> </button>
            </Space>
        ),
    },
];
