import { Space } from 'antd';
import { EditOutlined, DeleteOutlined } from '@ant-design/icons';
import React from 'react';
import type { FixedType } from 'rc-table/lib/interface';

export const columns = (onEdit: (record: any) => void, onDelete: (record: any) => void) => [
    {
        title: 'Recipe Title',
        dataIndex: 'recipetitle',
        key: 'recipetitle',
        render: (text: string) => <p>{text}</p>,
        sorter: (a: any, b: any) => a.recipetitle.localeCompare(b.recipetitle),
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
        sorter: (a: any, b: any) => a.date.localeCompare(b.date),

    },
    {
        title: 'Actions',
        key: 'actions',
        fixed: 'right' as FixedType,
        render: (_: any, record: any) => (
            <Space size="middle" className='btn-space'>
                <button className='edit-btn' onClick={() => onEdit(record)}> <EditOutlined /> </button>
                <button className='delete-btn' onClick={() => onDelete(record.key)}> <DeleteOutlined /> </button>
            </Space>
        ),
    },
];
