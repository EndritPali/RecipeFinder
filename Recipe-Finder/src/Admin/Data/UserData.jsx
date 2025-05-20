import { Space } from 'antd';
import { EditOutlined, DeleteOutlined } from '@ant-design/icons';
import React from 'react';

export const columns = (onEdit, onDelete) => [
    {
        title: 'Username',
        dataIndex: 'username',
        key: 'username',
        render: text => <a>{text}</a>,
        sorter: (a, b) => a.username.localeCompare(b.username),
    },
    {
        title: 'Role',
        dataIndex: 'role',
        key: 'role',
    },
    {
        title: 'Email',
        dataIndex: 'email',
        key: 'email',
    },
    {
        title: 'Date Created',
        dataIndex: 'date',
        key: 'date',
    },
    {
        title: 'Action',
        key: 'action',
        fixed: 'right',
        render: (_, record) => (
            <Space size="middle" className='btn-space'>
                <button className='edit-btn' onClick={() => onEdit(record)}> <EditOutlined /> </button>
                <button className='delete-btn' onClick={() => onDelete(record.key)}> <DeleteOutlined /> </button>
            </Space>
        ),
    },
];
