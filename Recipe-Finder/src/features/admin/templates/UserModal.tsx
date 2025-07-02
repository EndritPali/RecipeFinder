import React from 'react';
import { Modal, Form, Input, Select, FormInstance } from 'antd';
import { useEffect } from 'react';
import { useCreateUser } from '@/features/admin/hooks/useCreateUser';
import { useUpdateUser } from '@/features/admin/hooks/useUpdateUser';
import '../scss/UserModal.scss';
import { UserModalProps } from '@/types/admin';
import type { Rule } from 'antd/es/form';

export default function UserModal({ open, onOk, onCancel, mode = 'create', item }: UserModalProps) {
    const isEdit = mode === 'edit';
    const [form] = Form.useForm();
    const createUser = useCreateUser();
    const updateUser = useUpdateUser();

    useEffect(() => {
        if (open) {
            if (isEdit && item) {
                form.setFieldsValue({
                    username: item.username,
                    email: item.email,
                    role: item.role
                });
            } else {
                form.resetFields();
            }
        }
    }, [form, isEdit, item, open]);

    const handleFinish = async (values: any) => {
        if (isEdit) {
            await updateUser.mutateAsync({ id: item.key, payload: values });
        } else {
            await createUser.mutateAsync(values);
        }
        onOk();
    };

    const formFields: Array<{
        name: string;
        label: string;
        rules: Rule[];
        input: any;
    }> = [
            {
                name: 'username',
                label: 'Username',
                rules: [{ required: true, message: 'Please input username!' }],
                input: <Input placeholder={`${isEdit ? 'Edit' : 'Enter'} username`} />
            },
            {
                name: 'email',
                label: 'Email',
                rules: [
                    { required: true, message: 'Please input email!' },
                    { type: 'email', message: 'Please enter a valid email!' }
                ],
                input: <Input placeholder={`${isEdit ? 'Edit' : 'Enter'} email`} />
            },
            {
                name: 'password',
                label: 'Password',
                rules: [{ required: !isEdit, message: 'Please input password!' }],
                input: <Input.Password placeholder={`${isEdit ? 'Change' : 'Enter'} password`} />
            },
            {
                name: 'role',
                label: 'Role',
                rules: [{ required: true, message: 'Please select role!' }],
                input: (
                    <Select placeholder={`${isEdit ? 'Change' : 'Select'} role`}>
                        <Select.Option value='Admin'>Admin</Select.Option>
                        <Select.Option value='User'>User</Select.Option>
                    </Select>
                )
            }
        ];

    return (
        <Modal
            className='create-user-modal'
            open={open}
            onOk={() => form.submit()}
            onCancel={() => {
                form.resetFields();
                onCancel();
            }}
            okText={isEdit ? 'Save changes' : 'Create user'}
            title={isEdit ? 'Edit User Information' : 'Create New User'}
            confirmLoading={isEdit ? updateUser.status === 'pending' : createUser.status === 'pending'}
        >
            <Form form={form} className='create-user-form' layout="vertical" onFinish={handleFinish}>
                {formFields.map(field => (
                    <Form.Item
                        key={field.name}
                        className='user-form-item'
                        label={field.label}
                        name={field.name}
                        rules={field.rules as Rule[]}
                    >
                        {field.input}
                    </Form.Item>
                ))}
            </Form>
        </Modal>
    );
}