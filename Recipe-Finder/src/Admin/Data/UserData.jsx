import { Space } from 'antd';
import { EditOutlined, UserDeleteOutlined } from '@ant-design/icons';
import React from 'react';

export const columns = (onEdit, onDelete) => [
    {
        title: 'Username',
        dataIndex: 'username',
        key: 'username',
        render: text => <p>{text}</p>,
        sorter: (a, b) => a.username.localeCompare(b.username),
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
        fixed: 'right',
        render: (_, record) => (
            <Space size="middle" className='btn-space'>
                <button className='edit-btn' onClick={() => onEdit(record)}> <EditOutlined /> </button>
                <button className='delete-btn' onClick={() => onDelete(record.key)}> <UserDeleteOutlined /> </button>
            </Space>
        ),
    },
];
