import { Space } from 'antd';
import { EditOutlined, DeleteOutlined } from '@ant-design/icons';
import React from 'react';

export const columns = (onEdit, onDelete) => [
    {
        title: 'Recipe Title',
        dataIndex: 'recipetitle',
        key: 'recipetitle',
        render: text => <a>{text}</a>,
        sorter: (a, b) => a.recipetitle.localeCompare(b.recipetitle),
    },
    {
        title: 'Category',
        dataIndex: 'category',
        key: 'category',
    },
    {
        title: 'Created By',
        dataIndex: 'createdby',
        key: 'createdby',
    },
    {
        title: 'Date Added',
        key: 'date',
        dataIndex: 'date',
        sorter: (a, b) => a.date.localeCompare(b.date),

    },
    {
        title: 'Action',
        key: 'action',
        render: (_, record) => (
            <Space size="middle" className='btn-space'>
                <button className='edit-btn' onClick={() => onEdit(record)}> <EditOutlined /> </button>
                <button className='delete-btn' onClick={() => onDelete(record.key)}> <DeleteOutlined /> </button>
            </Space>
        ),
    },
];
